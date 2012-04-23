<?php

/**
 * Entry Controller
 *
 * View, submit and edit entries.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Entry_Controller extends Base_Controller
{

	public $restful = true;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Show the entry submission form
	 *
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_submit()
	{
		$this->layout->title = 'Submit an Entry';

		$this->layout->content = View::make('entry/submit', array(
			'max_upload_size' => Config::get('magicrainbowadventure.max_upload_size'),
		));
	}

	/**
	 * Save a new Entry
	 *
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function post_submit()
	{
		$validation_messages = array(
			'required' => 'You forgot to enter the :attribute!',
			'valid_image_url' => 'You need to either upload an image or enter an image URL.',
		);

		$validation_rules = array(
			'title' => 'required|max:140',
			'description' => 'max:2000',
		);

		$entry_image_error = Input::file('entry_image.error');

		if ($entry_image_error === null || $entry_image_error === 4)
		{
			// Validate the image URL
			$validation_rules['image_url'] = 'required|valid_image_url';
		}
		else
		{
			// Validate the uploaded file
			$validation_rules['entry_image'] = 'image|max:' . Config::get('magicrainbowadventure.max_upload_size');
		}

		$validation = \Validators\EntryValidator::make(Input::all(), $validation_rules, $validation_messages);

		if ($validation->fails())
		{
			if (isset($validation->errors->messages['image_url']) && count($validation->errors->messages['image_url']) === 1)
			{
				// In this case we know that the image_url field has failed
				// the 'required' validation. We'll set a custom message instead.
				$validation->errors->messages['image_url'][0] = $validation_messages['valid_image_url'];
			}

			return Redirect::to('entry/submit')->with_input()->with_errors($validation);
		}

		$entry = new \Entity\Entry;
		$entry->setTitle(Input::get('title'))
			->setDescription(Input::get('description'))
			->setUser($this->user);

		if (Input::get('image_url') !== '')
		{
			$handle = tmpfile();
			$curl = new \EasyCurl(Input::get('image_url'));
			$curl->execute(true, $handle);
			$content_type = $curl->get_content_type();
		}
		else
		{
			$handle = fopen(Input::file('entry_image.tmp_name'), 'rb');
			$content_type = Input::file('entry_image.type');
		}

		$mimes = Config::get('mimes');
		$extension = \Helpers\ArrayHelper::recursive_array_search($content_type, $mimes);

		$entry->uploadFile($handle, $extension);

		if ($this->user->isAdmin())
		{
			// Administrators don't need their entries approved
			$entry->setApproved(true)
				->setModeratedBy($this->user);
		}

		$this->em->persist($entry);
		$this->em->flush();
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
			$entry = false;
		}

		$this->layout->title = 'Thanks!';

		View::make('entry/thank-you', array(
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
			$this->layout->setVar('entry_rating', $this->ratings->find_by_entry($entry));
		}

		// Only show the entry if it has been approved, or if the user is the owner or an administrator
		if ( ! $entry || ( ! $entry->isApproved() && ! $this->user->isAdmin() && $entry->getUser() != $this->user))
		{
			$this->output->set_status_header(404);
			$this->layout->title = lang('not_found');
				View::make('entry/not-found');
		}
		elseif ($entry->isApproved() || $this->user->isAdmin())
		{
			$this->layout->title = $entry->getTitle();

			View::make('entry/view', array(
				'entry' => $entry
			));
		}
        else
        {
			$this->layout->title = $entry->getTitle();
			View::make('entry/view-preview', array(
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
				return false;
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
			if ( ! $this->easy_curl->url_to_file(Input::get('image_url'), $file_path))
			{
				$this->upload_error = lang('url_to_file_error');
				return false;
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
			return false;
		}

		// Everything went ok. Return the file name to be stored against the entry
		return $file_name;
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
