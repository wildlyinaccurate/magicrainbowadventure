<?php

/**
 * Entries Controller
 *
 * View, submit and edit entries.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Entries_Controller extends Base_Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->filter('before', 'auth')->only(array(
			'submit',
		));
	}

	/**
	 * Index/home page - show latest entries
	 *
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_index()
	{
		$page = Input::get('page') ?: 1;
		$entries_per_page = Config::get('magicrainbowadventure.entries_page_page');
		$offset = $entries_per_page * ($page - 1);

		$entries = $this->em->getRepository('Entity\Entry')->getLatestEntries($offset, $entries_per_page);

		Basset::inline('assets')->add('lazyload', 'assets/js/lazyload.js');

		$this->layout->title = Lang::line('general.latest_entries');
		$this->layout->content = View::make('entries/index', array(
			'entries' => $entries,
		));
	}

	/**
	 * Show the entries submission form
	 *
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_submit()
	{
		$this->layout->title = 'Submit an Entry';
		$this->layout->content = View::make('entries/submit', array(
			'max_upload_size' => Config::get('magicrainbowadventure.max_upload_size'),
		));
	}

	/**
	 * Save a new Entry
	 *
	 * @return Laravel\Redirect
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function post_submit()
	{
		$validation_rules = array(
			'title' => 'required|max:140',
			'description' => 'max:2000',
		);

		$validation_messages = array(
			'valid_image_url' => 'You need to either upload an image or enter an image URL.',
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

			return Redirect::to('entries/submit')->with_input()->with_errors($validation);
		}

		$entry = new \Entity\Entry;
		$entry->setTitle(Input::get('title'))
			->setDescription(Input::get('description'))
			->setUser($this->user);

		if (Input::get('image_url') !== '')
		{
			// Retrieve the image with cURL and store it in a temporary file
			$entry_file_path = tempnam(sys_get_temp_dir(), Config::get('magicrainbowadventure.temp_file_prefix'));
			$handle = fopen($entry_file_path, 'w+b');

			$curl = new \EasyCurl(Input::get('image_url'));
			$curl->execute(true, $handle);
			$content_type = $curl->get_content_type();
		}
		else
		{
			$entry_file_path = Input::file('entry_image.tmp_name');
			$content_type = Input::file('entry_image.type');
		}

		// Determine the extension of the file so that we can save it correctly
		$mimes = Config::get('mimes');
		$extension = \Helpers\ArrayHelper::recursive_array_search($content_type, $mimes);

		// Upload the file to Dropbox
		$entry->uploadFile($entry_file_path, $extension);

		if ($this->user->isAdmin())
		{
			// Administrators don't need their entries approved
			$entry->setApproved(true)
				->setModeratedBy($this->user);
		}

		$this->em->persist($entry);
		$this->em->flush();

		return Redirect::to("{$entry->getId()}/{$entry->getUrlTitle()}")
						->with('success_message', Lang::line('entries.entry_submit_success'));
	}

	/**
	 * View an Entry
	 *
	 * @param	integer	$id
	 * @param	string	$url_title
	 * @return	\Laravel\Response
	 */
	public function get_view($id = null, $url_title = null)
	{
		if ( ! $id)
		{
			return Response::error(404);
		}

		// Try and find the Entry
		$entry = $this->em->find('\Entity\Entry', $id);

		// Only show the entries if it has been approved, or if the user is the owner or an administrator
		if ( ! $entry || ( ! $entry->isApproved() && ! $this->user->isAdmin() && $entry->getUser() != $this->user))
		{
			$this->layout->title = Lang::line('general.not_found');
			$this->layout->content = View::make('entries/not-found');
		}
		elseif ($entry->isApproved() || $this->user->isAdmin())
		{
			$this->layout->title = $entry->getTitle();
			$this->layout->content = View::make('entries/view', array(
				'entry' => $entry
			));
		}
		else
		{
			$this->layout->title = $entry->getTitle();
			$this->layout->content = View::make('entries/view-preview', array(
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
		$cache_id = "entries/thumbnail/{$entry_id}";

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
				$this->upload_error = Lang::line('general.url_to_file_error');
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
