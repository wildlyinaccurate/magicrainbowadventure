<?php

use \MagicRainbowAdventure\Tools\Pagination\Paginator;

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
		$page = Input::get('page', 1);
		$per_page = 10;
		$offset = $per_page * ($page - 1);

		$paginator = Paginator::make(array(), $this->em->getRepository('Entity\Entry')->countAllEntries(), $per_page);

		Basset::inline('assets')->add('models/entry', 'bundles/admin/js/models/entry.js')
			->add('models/user', 'bundles/admin/js/models/user.js')
			->add('entries', 'bundles/admin/js/entries.js');

		$this->layout->title = Lang::line('admin::entries.moderate_entries');
		$this->layout->content = View::make('admin::entries/index', array(
			'paginator' => $paginator,
			'per_page' => $per_page,
		));
	}

}
