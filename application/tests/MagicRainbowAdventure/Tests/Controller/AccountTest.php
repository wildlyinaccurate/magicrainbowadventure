<?php

namespace MagicRainbowAdventure\Tests\Controller;

class AccountTest extends \MagicRainbowAdventure\Tests\ControllerTestCase
{

	public function testSignup()
	{
		$response = $this->call('account@signup', array(), 'POST');
		$this->assertInstanceOf('Laravel\\Response', $response);
		$this->assertEquals('302', $response->foundation->getStatusCode());
	}

}
