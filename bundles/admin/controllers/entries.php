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

		Basset::scripts('default', function($basset)
		{
			$basset->add('models/Entry', 'assets/js/models/Entry.js');
		});
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

		Basset::inline('assets')->add('Entry', 'bundles/admin/js/models/Entry.js');

		$this->layout->title = Lang::line('admin::entries.moderate_entries');
		$this->layout->content = View::make('admin::entries/index', array(
			'entries' => $entries,
		));
	}

	/**
	 * Delete an entry
	 *
	 * @param	int		$id
	 * @return	\Laravel\Response
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get_approve($id)
	{
		$entry = $this->em->find('Entity\Entry', $id);

		if ( ! $entry)
		{
			return \Laravel\Response::json(array(
				'message' => Lang::line('admin::entries.entry_not_found'),
			), 500);
		}

		$approved = Input::get('approved', false);
		$entry->setApproved($approved);
		$entry->setModeratedBy(Auth::user());
		$this->em->flush();

		$message = ($approved) ? Lang::line('admin::entries.entry_approved') : Lang::line('admin::entries.entry_rejected');

		return \Laravel\Response::json(array(
			'message' => sprintf($message, $entry->getTitle()),
		));
	}

}
