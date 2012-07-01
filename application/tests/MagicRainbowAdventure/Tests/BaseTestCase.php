<?php

namespace MagicRainbowAdventure\Tests;

use MagicRainbowAdventure\Tests\Mocks\Dropbox\DropboxApiMock;

/**
 * Magic Rainbow Adventure Base Test Case
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {

	public function __construct()
	{
		\Laravel\IoC::instance('dropbox::api', new DropboxApiMock);
	}

}
