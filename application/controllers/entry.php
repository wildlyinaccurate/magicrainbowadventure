<?php

class Entry_Controller extends Base_Controller {

	/**
	 * Keep track of any errors that occurred during a file upload
	 * @var string
	 */
	private $upload_error;

	/**
	 * The content type of a remote image submitted by the user. This is set in _valid_image_url
	 * @var string
	 */
	private $image_content_type;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Submit a new entry
	 */
	public function submit()
	{
		$this->auth->require_login();

		$this->form_validation->set_rules('title', 'a title', 'required|max_length[140]');
		$this->form_validation->set_rules('description', 'a description', 'max_length[2000]');

		if ( ! $this->_uploaded_file())
		{
			// No file was uploaded; check the remote image URL
			$this->form_validation->set_rules('image_url', 'a link', 'callback__valid_image_url');
		}

		if ($this->form_validation->run() && $entry_file_path = $this->_process_file())
		{
			// Create the new Entry
			$entry = new \Entity\Entry;
			$entry->setTitle($this->input->post('title'))
				->setDescription($this->input->post('description'))
				->setUser($this->user)
				->setFilePath($entry_file_path);

			// Administrators don't need their entries approved
			if ($this->user->isAdmin())
			{
				$entry->setApproved(TRUE);
				$entry->setModeratedBy($this->user);
			}

			$this->em->persist($entry);
			$this->em->flush();

			redirect('entry/thank-you');
		}
		else
		{
			// We need the number helper for its byte_format function
			$this->load->helper('number');

			$this->template->title('Submit an Entry')
			->build('entry/submit', array(
			  	'upload_error' => $this->upload_error,
			    'max_upload_size' => $this->config->item('max_upload_size') * 1024 // Max upload size in bytes
			));
		}
	}

	/**
	 * Display the thank-you message
	 *
	 * @return void
	 */
	public function thank_you()
	{
		$entries = $this->user->getEntries();

		if ($entries->count() > 0)
		{
			// Get the user's latest entry
			$entry = $entries->last();
		}
		else
		{
			// The user has no Entries
			$entry = FALSE;
		}

		$this->template->title('Thanks!')
			->build('entry/thank-you', array(
				 'entry' => $entry
			 ));
	}

	/**
	 * View an Entry
	 *
	 * @param	integer	$id
	 * @param	string	$url_title
	 * @return	void
	 */
	public function view($id = NULL, $url_title = '')
	{
		if ( ! $id)
		{
			show_404();
		}

		// Try and find the Entry
		$entry = $this->em->find('\Entity\Entry', $id);

		// See if the user has rated this entry
		if ($entry)
		{
			$this->load->library('ratings');
			$this->template->setVar('entry_rating', $this->ratings->find_by_entry($entry));
		}

		// Only show the entry if it has been approved, or if the user is the owner or an administrator
		if ( ! $entry || ( ! $entry->isApproved() && ! $this->user->isAdmin() && $entry->getUser() != $this->user))
		{
			$this->output->set_status_header(404);
			$this->template->title(lang('not_found'))
					->build('entry/not-found');
		}
		elseif ($entry->isApproved() || $this->user->isAdmin())
		{
			$this->template->title($entry->getTitle())
					->addScript('entry.js')
					->build('entry/view', array(
						'entry' => $entry
					));
		}
        else
        {
			$this->template->title($entry->getTitle())
				->build('entry/view-preview', array(
					'entry' => $entry
				));
        }
	}

	/**
	 * Output the attachment of an Entry
	 *
	 * @param	integer	$entry_id
	 * @return	void
	 */
	public function thumbnail($entry_id = NULL)
	{
		// Make sure the Entry exists
		if ( ! $entry_id || ! $entry = $this->em->find('\Entity\Entry', $entry_id))
		{
			show_404();
		}

		// Also make sure that this Entry belongs to the current User, or they are an Administrator
		// This is to prevent unnecessary strain on the server from guests or bots generating thumbnails
		if ($entry->getUser()->getId() != $this->user->getId() && ! $this->user->isAdmin())
		{
			show_404();
		}

		// Load the Image_moo library for resizing and cropping
		$this->load->library('image_moo');

        // Load the caching driver and see if the thumbnail is already cached
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$cache_id = "entry/thumbnail/{$entry_id}";

		if ( ! $cache = $this->cache->get($cache_id))
		{
			$image_dir = $this->config->item('upload_directory');
            $thumb_width = $this->config->item('thumb_width');
            $thumb_height = $this->config->item('thumb_height');
            $thumb_quality = $this->config->item('thumb_quality');

            // Capture the output of Image_moo::save_dynamic() so that we can cache the thumbnail
            ob_start();

            $this->image_moo->load("{$image_dir}/{$entry->getFilePath()}")
					->set_jpeg_quality($thumb_quality)
					->resize_crop($thumb_width, $thumb_height)
                    ->save_dynamic();

            $thumbnail = ob_get_clean();

            // We need to cache not only the thumbnail content, but the HTTP headers set
            $cache = array(
                'thumbnail' => $thumbnail,
                'headers' => headers_list()
            );

            // Save the thumbnail to cache
            $this->cache->save($cache_id, $cache, 7200);
        }

        // Set some headers and we're good to go!
        foreach ($cache['headers'] as $header)
        {
            header($header);
        }

		die($cache['thumbnail']);
	}

	/**
	 * Process the Entry file, whether it be an uploaded file or external link
	 *
	 * @return	array|bool
	 */
	private function _process_file()
	{
		if ($this->_uploaded_file())
		{
			if ( ! $upload_data = $this->_do_upload())
			{
				return FALSE;
			}
			else
			{
				$file_name = $upload_data['file_name'];
				$file_path = $upload_data['full_path'];
			}
		}
		else
		{
			// _valid_image_url() has already done some of the work for us, including
			// getting the content-type and loading the easy_curl library

			// Figure out the extension to use based on the content type
			require APPPATH . 'config/mimes.php';
			$this->load->helper('advanced_array');
			$extension = recursive_array_search($this->image_content_type, $mimes);

			// Build a random file name
			mt_srand();
			$filename = md5(uniqid(mt_rand()));
			$directory = $this->config->item('upload_tmp_directory');
			$file_path = "{$directory}/{$filename}.{$extension}";

			// Create a unique filename
			// Easy_curl is already loaded from _valid_image_url()
			if ( ! $this->easy_curl->url_to_file($this->input->post('image_url'), $file_path))
			{
				$this->upload_error = lang('url_to_file_error');
				return FALSE;
			}
			else
			{
				$file_name = "{$filename}.{$extension}";
			}
		}

		// The image has been uploaded successfully - now see if it needs to be resized
		if ( ! $this->_resize_image($file_path))
		{
			// Errors ocurred during the resize
			return FALSE;
		}

		// Everything went ok. Return the file name to be stored against the entry
		return $file_name;
	}

	/**
	 * Upload a file
	 *
	 * Returns upload_data on success, or returns FALSE and sets $this->upload_error if the upload fails
	 *
	 * @return	array|bool
	 */
	private function _do_upload()
	{
		$config['upload_path'] = $this->config->item('upload_tmp_directory');
		$config['allowed_types'] = $this->config->item('allowed_upload_types');
		$config['max_size']	= $this->config->item('max_upload_size');
		$config['encrypt_name']	= TRUE;

		$this->load->library('upload', $config);
		$this->upload_error = NULL;

		if ( ! $this->upload->do_upload())
		{
			$this->upload_error = $this->upload->display_errors();
			return FALSE;
		}
		else
		{
			// Successful upload!
			return $this->upload->data();
		}
	}

	/**
	 * See if an image is bigger than the maximum dimensions. If it is, resize it.
	 *
	 * @param	string	$file_path
	 * @return	bool
	 */
	private function _resize_image($file_path)
	{
		$max_width = $this->config->item('max_image_width');
		$max_height = $this->config->item('max_image_height');

		if ( ! $image_size = getimagesize($file_path))
		{
			// If getimagesize failed, this probably isn't a valid image...
			$this->upload_error = 'The image you uploaded is corrupted.';
			return FALSE;
		}

		// If the width or height are bigger than the maximum dimensions, resize the image
		if ($image_size[0] > $max_width || $image_size[1] > $max_height)
		{
			$this->load->library('image_moo');

			$this->image_moo->load($file_path)
				->resize($max_width, $max_height)
				->set_jpeg_quality($this->config->item('jpeg_quality'))
				->save($file_path, TRUE);

			if ($this->image_moo->errors)
			{
				// Image moo was unable to resize the image
				$this->upload_error = $this->image_moo->display_errors();
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Make sure a URL is pointing to a valid image
	 *
	 * @param	string	$url
	 * @return	bool
	 */
	public function _valid_image_url($url)
	{
		if ( ! $url)
		{
			$this->form_validation->set_message('_valid_image_url', 'Please either upload a file or paste a link to an image.');
			return FALSE;
		}

		// Make sure the supplied URL is actually an image
		$this->load->library('easy_curl');
		$this->image_content_type = $this->easy_curl->get_content_type($url);

		if ( ! $this->image_content_type || ! preg_match('/image\/(.+)/i', $this->image_content_type))
		{
			$this->form_validation->set_message('_valid_image_url', 'The link you entered does not appear to be an image.');
			return FALSE;
		}

		// Make sure the image is not bigger than the maximum upload size
		// Convert max_upload_size to bytes
		$max_upload_size = $this->config->item('max_upload_size') * 1024;

		if ($this->easy_curl->get_content_length($url) > $max_upload_size)
		{
			$this->load->helper('number');
			$this->form_validation->set_message('_valid_image_url', 'This image is too big! Choose something that is smaller than ' . byte_format($max_upload_size, 0));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Determine whether the user has uploaded a file
	 *
	 * @return	bool
	 */
	private function _uploaded_file()
	{
		return (isset($_FILES['userfile']) && $_FILES['userfile']['error'] != 4);
	}

}

/* End of file entry.php */
/* Location: ./application/controllers/entry.php */