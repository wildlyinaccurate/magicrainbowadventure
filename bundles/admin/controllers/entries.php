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
		$per_page = 1;
		$entries = $this->em->getRepository('Entity\Entry')->getAllEntries(0, $per_page);
		$paginator = Paginator::make(array(), $entries->count(), $per_page);

		Basset::inline('assets')->add('entries', 'bundles/admin/js/entries.js');

		$this->layout->title = Lang::line('admin::entries.moderate_entries');
		$this->layout->content = View::make('admin::entries/index', array(
			'paginator' => $paginator,
			'per_page' => $per_page,
		));
	}

}
