<?php

class Base_Controller extends Controller
{

	public $restful = true;

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
		if (Request::ajax())
		{
			$this->layout = 'layouts/ajax';
		}

		parent::__construct();

		$this->em = IoC::resolve('doctrine::manager');
		$this->dropbox = IoC::resolve('dropbox::api');

		if (Auth::check())
		{
			$this->user = Auth::user();
			$this->layout->nest('account_menu', 'navigation/account-user');
		}
		else
		{
			$this->user = new \Entity\User;
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
