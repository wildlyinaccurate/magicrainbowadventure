<?php

namespace MagicRainbowAdventure\Tests;

/**
 * Magic Rainbow Adventure Base Test Case
 *
 * Provides access to common components
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{

	protected $em;

	public function __construct()
	{
		$this->em = \IoC::resolve('doctrine::manager');
	}

}
