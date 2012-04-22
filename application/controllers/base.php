<?php

class Base_Controller extends Controller
{

	/**
	 * The template layout to use
	 * @var \Laravel\View
	 */
	public $layout = 'layouts/default';

	/**
	 * The currently logged-in user
	 * @var \Entity\User
	 */
	protected $user;

	/**
	 * Dropbox API
	 * @var \Dropbox\API
	 */
	protected $dropbox;

	/**
	 * Doctrine Entity Manager
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * Constructor ahoy!
	 */
	public function __construct()
	{
		parent::__construct();

		$this->em = IoC::resolve('doctrine::manager');
		$this->dropbox = IoC::resolve('dropbox::api');
		$this->user = Auth::user();

		print_r($this->dropbox->accountinfo()); exit;

		if (Auth::check())
		{
			$this->layout->nest('account_menu', 'navigation/account-user');
		}
		else
		{
			$this->layout->nest('account_menu', 'navigation/account-guest');
		}

		$this->layout->nest('navigation', 'navigation/default');
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

}
