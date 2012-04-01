<?php

class Home extends Front_controller {

	/** @var int */
	private $page;

	/** @var int */
	private $entries_per_page;

	/** @var int */
	private $offset;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->page = $this->input->get('page') ?: 1;
		$this->entries_per_page = $this->config->item('entries_per_page');
		$this->offset = $this->entries_per_page * ($this->page - 1);
	}

	public function _display_entries($entries)
	{
		// Build an array of entry_ratings for each Entry
		$this->load->library('ratings');
		$entry_ratings = array();

		foreach ($entries as $index => $entry)
		{
			$entry_ratings[$index] = $this->ratings->find_by_entry($entry);
		}

		// Set up the pagination library
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->em->getRepository('\Entity\Entry')->getTotalQueryResults();
		$config['per_page'] = $this->entries_per_page;

		$this->load->library('pagination');
		$this->pagination->initialize($config);

		$this->template->addScript('entry.js')
				->build('home/index', array(
					'user' => $this->user,
					'entries' => $entries,
					'entry_ratings' => $entry_ratings
				));
	}

	/**
	 * Home page - show latest entries
	 *
	 * @return	void
	 */
	public function index()
	{
		// Load the latest entries
		$entries = $this->em->getRepository('\Entity\Entry')->getLatestEntries($this->offset, $this->entries_per_page);

		// Set the template title
		$this->template->title('Latest Entries');

		// Display the Entries
		$this->_display_entries($entries);
	}

	/**
	 * Show entries ordered by 'cute' EntryRatings
	 *
	 * @return  void
	 */
	public function cutest()
	{
		// Load the latest entries
		$entries = $this->em->getRepository('\Entity\Entry')->getByRating($this->offset, $this->entries_per_page, 'cute');

		// Set the template title
		$this->template->title('Cutest Entries');

		// Display the Entries
		$this->_display_entries($entries);
	}

	/**
	 * Show entries ordered by 'cute' EntryRatings
	 *
	 * @return  void
	 */
	public function funniest()
	{
		// Load the latest entries
		$entries = $this->em->getRepository('\Entity\Entry')->getByRating($this->offset, $this->entries_per_page, 'funny');

		// Set the template title
		$this->template->title('Funniest Entries');

		// Display the Entries
		$this->_display_entries($entries);
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */