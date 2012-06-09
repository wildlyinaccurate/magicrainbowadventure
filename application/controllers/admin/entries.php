<?php

/**
 * Admin Entries Controller
 *
 * Moderate entries.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Admin_Entries_Controller extends Base_Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->layout->with('content_layout', 'layouts/one-column');
	}

	/**
	 * Show a paginated list of all entries
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

		$this->layout->title = Lang::line('admin/entries.moderate_entries');
		$this->layout->content = View::make('admin/entries/index', array(
			'entries' => $entries,
		));
	}

}
