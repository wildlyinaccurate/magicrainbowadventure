<?php

namespace MagicRainbowAdventure\Tests;

/**
 * Magic Rainbow Adventure Controller Test Case
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
abstract class ControllerTestCase extends FunctionalTestCase
{

	/**
	 * The Laravel session must be re-loaded before each test, otherwise
	 * the session state is retained across multiple tests.
	 */
	public function setUp()
	{
		\Laravel\Session::load();
	}

	/**
	 * Call a controller method.
	 *
	 * This is basically an alias for Laravel's Controller::call() with the
	 * option to specify a request method.
	 *
	 * @param	string	$destination
	 * @param	array	$parameters
	 * @param	string	$method
	 * @return	\Laravel\Response
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function call($destination, $parameters = array(), $method = 'GET')
	{
		\Laravel\Request::foundation()->server->add(array(
			'REQUEST_METHOD' => $method,
		));

		return \Laravel\Routing\Controller::call($destination, $parameters);
	}

	/**
	 * Alias for call()
	 *
	 * @param	string	$destination
	 * @param	array	$parameters
	 * @param	string	$method
	 * @return	\Laravel\Response
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function get($destination, $parameters = array())
	{
		return $this->call($destination, $parameters, 'GET');
	}

	/**
	 * Make a POST request to a controller method
	 *
	 * @param	string	$destination
	 * @param	array	$post_data
	 * @param	array	$parameters
	 * @return	\Laravel\Response
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function post($destination, $post_data, $parameters = array())
	{
		$this->clean_request();
		\Laravel\Request::foundation()->request->add($post_data);

		return $this->call($destination, $parameters, 'POST');
	}

	/**
	 * Ensure the ParameterBag is clean before calling the controller
	 *
	 * @return	void
	 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	private function clean_request()
	{
		$request = \Laravel\Request::foundation()->request;

		foreach ($request->keys() as $key)
		{
			$request->remove($key);
		}
	}

}
