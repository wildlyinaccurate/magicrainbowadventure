<?php

namespace MagicRainbowAdventure\Tests\Controller;

class AccountTest extends \MagicRainbowAdventure\Tests\ControllerTestCase
{

	public function testCantSignupWithNoInput()
	{
		$response = $this->post('account@signup', array());
		$this->assertEquals('302', $response->foundation->getStatusCode());

		$session_errors = \Laravel\Session::instance()->get('errors');
		$this->assertNotEmpty($session_errors);
	}


	public function testCantSignupWithInvalidInput()
	{
		$response = $this->post('account@signup', array(
			'username' => 'someusername',
			'email' => 'notvalid',
			'password' => 'passw0rd',
		));
		$this->assertEquals('302', $response->foundation->getStatusCode());

		$session_errors = \Laravel\Session::instance()->get('errors');
		$this->assertNotEmpty($session_errors);
	}

	public function testSignupWithGoodInputHasNoErrors()
	{
		$response = $this->post('account@signup', array(
			'username' => 'validusername',
			'email' => 'some@validemail.com',
			'password' => 'passw0rd',
			'password_confirm' => 'passw0rd',
		));
		$this->assertEquals('302', $response->foundation->getStatusCode());

		$session_errors = \Laravel\Session::instance()->get('errors');
		$this->assertNull($session_errors);
	}

	/**
	 * @depends testSignupWithGoodInputHasNoErrors
	 */
	public function testSignupWithGoodInputCreatedUser()
	{

		$user = $this->_em->getRepository('Entity\User')->findOneByUsername('validusername');
		$this->assertInstanceOf('Entity\User', $user);

		$this->assertEquals('validusername', $user->getUserName());
	}

}
