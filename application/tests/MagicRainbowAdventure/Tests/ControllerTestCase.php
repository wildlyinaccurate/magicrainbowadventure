<?php

namespace MagicRainbowAdventure\Tests;

/**
 * Magic Rainbow Adventure Entity Test Case
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
abstract class ControllerTestCase extends BaseTestCase
{

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

		return \Laravel\Routing\Controller::call('account@signup', $parameters);
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
		\Laravel\Request::foundation()->request->add($post_data);

		return $this->call($destination, $parameters, 'POST');
	}

}
