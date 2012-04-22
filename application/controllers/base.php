<?php

class Base_Controller extends Controller
{

	/**
	 * The template layout to use
	 * @var \Laravel\View
	 */
	public $layout = 'layouts/default';

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
