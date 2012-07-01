<?php

namespace MagicRainbowAdventure\Tests;

use MagicRainbowAdventure\Tests\Mocks\DropboxApiMock,
	MagicRainbowAdventure\Tests\Mocks\AuthDriverMock;

/**
 * Magic Rainbow Adventure Base Test Case
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {

	public function __construct()
	{
		parent::__construct();

		// \Laravel\Auth::extend('mock', function() {
		// 	return new AuthDriverMock;
		// });

		// \Laravel\Config::set('auth.driver', 'mock');

		\Laravel\Session::load();
		\Laravel\IoC::instance('dropbox::api', new DropboxApiMock);
	}

}
