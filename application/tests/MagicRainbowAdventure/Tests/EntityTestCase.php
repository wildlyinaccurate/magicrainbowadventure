<?php

namespace MagicRainbowAdventure\Tests;

/**
 * Magic Rainbow Adventure Entity Test Case
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
abstract class EntityTestCase extends BaseTestCase
{

	protected $em;

	public function __construct()
	{
		$this->em = \Laravel\IoC::resolve('doctrine::manager');
	}

}
