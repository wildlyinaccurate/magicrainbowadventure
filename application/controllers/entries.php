<?php

use MagicRainbowAdventure\Validation\EntryValidator,
	MagicRainbowAdventure\Processors\EntryImageProcessor,
	MagicRainbowAdventure\Exception\EntryImageProcessorException,
	MagicRainbowAdventure\Tools\EntryThumbnailTool,
	MagicRainbowAdventure\Helpers\ImageHelper;

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
			'favourite',
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
		$page = Input::get('page', 1);
		$entries_per_page = Config::get('magicrainbowadventure.entries_page_page');
		$offset = $entries_per_page * ($page - 1);

		$entries = $this->em->getRepository('Entity\Entry')->getLatestEntries($offset, $entries_per_page);

		Basset::inline('assets')->add('jquery-lazyload', 'assets/js/jquery-lazyload/jquery.lazyload.js')
			->add('activity-indicator', 'assets/js/jquery.activity-indicator-1.0.0.min.js')
			->add('bootstrap-transition', 'assets/js/vendor/bootstrap/transition.js')
			->add('bootstrap-tooltip', 'assets/js/vendor/bootstrap/tooltip.js')
			->add('bootstrap-popover', 'assets/js/vendor/bootstrap/popover.js')
			->add('lazyload', 'assets/js/lazyload.js')
			->add('entries', 'assets/js/entries.js');

		$this->layout->title = Lang::line('general.latest_entries');
		$this->layout->content = View::make('entries/index', array(
			'entries' => $entries,
		));
	}

	/**
	 * GET alias for post_favourite
	 *
	 * @param	int		$id
	 * @return	\Laravel\Redirect|\Laravel\Response
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_favourite($id)
	{
		return $this->post_favourite($id);
	}

	/**
	 * Add or remove an entry from the user's favourites
	 *
	 * @param	int		$id
	 * @return	\Laravel\Redirect|\Laravel\Response
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function post_favourite($id)
	{
		$entry = $this->em->find('Entity\Entry', $id);

		if ( ! $entry)
		{
			return Response::error('404');
		}

		$favourite = Input::get('favourite');

		if ($favourite)
		{
			$return_message = Lang::line('entries.added_to_favourites');
			$this->user->addFavourite($entry);
		}
		else
		{
			$return_message = Lang::line('entries.removed_from_favourites');
			$this->user->getFavourites()->removeElement($entry);
			$entry->getFavouritedBy()->removeElement($this->user);
		}

		$this->em->flush();

		if (Request::ajax())
		{
			return Response::json(array(
				'message' => sprintf($return_message, $entry->getTitle()),
				'favourites_count' => $entry->getFavouritedBy()->count(),
				'favourite' => (bool) $favourite,
			));
		}
		else
		{
			return Redirect::to("{$entry->getId()}/{$entry->getUrlTitle()}");
		}
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

		$validation = EntryValidator::make(Input::all(), $validation_rules, $validation_messages);

		if ($validation->fails())
		{
			if (isset($validation->errors->messages['image_url']) && count($validation->errors->messages['image_url']) === 1)
			{
				// The image_url field has failed the 'required' validation, so we'll set a custom message.
				$validation->errors->messages['image_url'][0] = $validation_messages['valid_image_url'];
			}

			return Redirect::to('entries/submit')->with_input()->with_errors($validation);
		}

		$entry = new \Entity\Entry;
		$entry->setTitle(Input::get('title'))
			->setDescription(Input::get('description'))
			->setUser($this->user);

		if ($this->user->isAdmin())
		{
			// Automatically approve anything sumitted by an admin
			$entry->setApproved(true)
				->setModeratedBy($this->user);
		}

		$processor = new EntryImageProcessor(Config::get('magicrainbowadventure.entry_uploads_path'));

		try
		{
			if (Input::get('image_url') !== '')
			{
				$this->logger->addInfo('Processing entry image from URL: ' . Input::get('image_url'));
				$processor->fromUrl(Input::get('image_url'));
			}
			else
			{
				$this->logger->addInfo('Processing entry image from file: ' . serialize(Input::file()));
				$processor->fromFile(Input::file('entry_image.tmp_name'));
			}
		}
		catch (EntryImageProcessorException $e)
		{
			if ($e->getCode() === EntryImageProcessorException::DUPLICATE_ENTRY)
			{
				$this->logger->addInfo(sprintf('Duplicate entry uploaded. Existing entry ID: %d', $e->getEntry()->getId()));
				$error = Lang::line('entries.duplicate_entry');
			}
			else
			{
				$this->logger->addError('Unable to process entry image.');
				$error = Lang::line('entries.process_error');
			}

			return Redirect::to('entries/submit')->with_input()->with('alert.error', $error);
		}

		$image_dimensions = $processor->getImageDimensions();

		$entry->setFilePath($processor->getFilePath())
			->setHash($processor->getFileHash())
			->setImageWidth($image_dimensions['width'])
			->setImageHeight($image_dimensions['height']);

		// Generate the thumbnails
		$thumbnail_sizes = Config::get('magicrainbowadventure.entry_thumbnails');
		$thumbnail_tool = new EntryThumbnailTool($entry);
		$thumbnail_tool->generateFromArray($thumbnail_sizes);

		$this->em->persist($entry);
		$this->em->flush();

		return Redirect::to("{$entry->getId()}/{$entry->getUrlTitle()}")->with('success_message', Lang::line('entries.entry_submit_success'));
	}

	/**
	 * View an Entry
	 *
	 * @param	integer	$id
	 * @return	\Laravel\Response
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_view($id)
	{
		$this->layout->with('content_layout', 'layouts/one-column');

		// Try and find the Entry
		$entry = $this->em->find('\Entity\Entry', $id);

		// Only show the entries if it has been approved, or if the user is the owner or an administrator
		if ( ! $entry || ( ! $entry->isApproved() && ! $this->user->isAdmin() && $entry->getUser() !== $this->user))
		{
			$this->layout->title = Lang::line('general.not_found');
			$this->layout->content = View::make('entries/not-found');
		}
		elseif ($entry->isApproved() || $this->user->isAdmin())
		{
			Basset::inline('assets')->add('bootstrap-transition', 'assets/js/bootstrap/bootstrap-transition.js')
				->add('bootstrap-tooltip', 'assets/js/bootstrap/bootstrap-tooltip.js')
				->add('bootstrap-popover', 'assets/js/bootstrap/bootstrap-popover.js')
				->add('entries', 'assets/js/entries.js');

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

}
