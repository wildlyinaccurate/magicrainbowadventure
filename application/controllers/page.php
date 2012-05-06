<?php

/**
 * Page Controller
 *
 * Delivers the more plain, 'static' pages
 */
class Page_Controller extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * About Magic Rainbow Adventure
	 */
	public function about()
	{
		$this->layout->title = 'About Magic Rainbow Adventure';
			->build('page/about');
	}

	/**
	 * Privacy Statement
	 */
	public function privacy()
	{
		$this->layout->title = 'Privacy Statement';
			->build('page/privacy');
	}

}

/* End of file rate.php */
/* Location: ./application/controllers/rate.php */
