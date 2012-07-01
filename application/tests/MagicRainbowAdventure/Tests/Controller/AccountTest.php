<?php

namespace MagicRainbowAdventure\Tests\Controller;

class AccountTest extends \MagicRainbowAdventure\Tests\ControllerTestCase
{

	public function testSignupWithNoInput()
	{
		$response = $this->post('account@signup', array());
		$this->assertInstanceOf('Laravel\\Redirect', $response);
		$this->assertEquals('302', $response->foundation->getStatusCode());
	}

	public function testSignupWithBadInput()
	{
		$response = $this->post('account@signup', array(
			'username' => 'someusername',
			'email' => 'notvalid',
			'password' => 'passw0rd',
		));

		$this->assertInstanceOf('Laravel\\Redirect', $response);
	}

	/**
	 * @depends testSignupWithBadInput
	 */
	public function testSignupWithBadInputHasErrors()
	{
		$session_errors = \Laravel\Session::instance()->get('errors')->all();
		$this->assertNotEmpty($session_errors);
	}

	public function testSignupWithGoodInput()
	{
		$response = $this->post('account@signup', array(
			'username' => 'validusername',
			'email' => 'some@validemail.com',
			'password' => 'passw0rd',
			'password_confirm' => 'passw0rd',
		));

		$this->assertInstanceOf('Laravel\\Redirect', $response);
	}

	/**
	 * @depends testSignupWithGoodInput
	 */
	public function testSignupWithGoodInputHasNoErrors()
	{
		$session_errors = \Laravel\Session::instance()->get('errors')->all();
		$this->assertEmpty($session_errors);
	}

}
