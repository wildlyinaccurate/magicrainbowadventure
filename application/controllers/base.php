<?php

class Base_Controller extends Controller
{

	protected $em;

	/**
	 * Constructor ahoy!
	 */
	public function __construct()
	{
		parent::__construct();

		$this->em = IoC::resolve('doctrine::manager');
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