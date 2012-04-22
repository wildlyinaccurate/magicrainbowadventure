<?php

class Home_Controller extends Base_Controller
{

	/**
	 * The current page of entries
	 * @var int
	 */
	private $page;

	/**
	 * The number of entries to display per page
	 * @var int
	 */
	private $entries_per_page = 10;

	/**
	 * The query limit offset based on the current page and entries per page
	 * @var int
	 */
	private $offset;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->page = Input::get('page') ?: 1;
		$this->offset = $this->entries_per_page * ($this->page - 1);
	}

	public function _display_entries($entries)
	{
		// Build an array of entry_ratings for each Entry
		$entry_ratings = array();

		foreach ($entries as $index => $entry)
		{
			$entry_ratings[$index] = array();
		}

		$this->layout->content = View::make('home/index', array(
			'entries' => $entries,
			'entry_ratings' => $entry_ratings,
		));
	}

	/**
	 * Home page - show latest entries
	 *
	 * @return	void
	 */
	public function action_index()
	{
		// Load the latest entries
		$entries = $this->em->getRepository('Entity\Entry')->getLatestEntries($this->offset, $this->entries_per_page);

		// Set the template title
		$this->layout->title = 'Latest Entries';

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
		$entries = $this->em->getRepository('Entity\Entry')->getByRating($this->offset, $this->entries_per_page, 'cute');

		// Set the template title
		$this->layout->title = 'Cutest Entries';

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
		$entries = $this->em->getRepository('Entity\Entry')->getByRating($this->offset, $this->entries_per_page, 'funny');

		// Set the template title
		$this->layout->title = 'Funniest Entries';

		// Display the Entries
		$this->_display_entries($entries);
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
