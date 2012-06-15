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
		Basset::inline('assets')->add('entries', 'bundles/admin/js/entries.js');

		$this->layout->title = Lang::line('admin::entries.moderate_entries');
		$this->layout->content = View::make('admin::entries/index');
	}

}
