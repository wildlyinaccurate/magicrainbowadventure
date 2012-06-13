<?php

/**
 * Admin Entries Controller
 *
 * Moderate entries.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Admin_Entries_Controller extends \MagicRainbowAdmin\Controllers\AdminBaseController
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
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

		Basset::inline('assets')->add('entries', 'bundles/admin/js/entries.js');

		$this->layout->title = Lang::line('admin::entries.moderate_entries');
		$this->layout->content = View::make('admin::entries/index', array(
			'entries' => $entries,
		));
	}

}
