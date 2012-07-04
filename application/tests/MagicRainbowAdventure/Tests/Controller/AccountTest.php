<?php

namespace MagicRainbowAdventure\Tests\Controller;

class AccountTest extends \MagicRainbowAdventure\Tests\ControllerTestCase
{

	public function getUsers()
	{
		return $this->getEntityManager()->getRepository('Entity\User')->findAll();
	}

	public function testSignupWithNoInput()
	{
		echo 'There are ' . count($this->getUsers()) . " users\n\n";
		$response = $this->post('account@signup', array());
		$this->assertInstanceOf('Laravel\\Redirect', $response);
		$this->assertEquals('302', $response->foundation->getStatusCode());
	}

	/**
	 * @depends testSignupWithNoInput
	 */
	public function testSignupWithNoInputHasErrors()
	{
		echo 'There are ' . count($this->getUsers()) . " users\n\n";
		$session_errors = \Laravel\Session::instance()->get('errors')->all();
		$this->assertNotEmpty($session_errors);
	}

	public function testSignupWithInvalidInput()
	{
		echo 'There are ' . count($this->getUsers()) . " users\n\n";
		$response = $this->post('account@signup', array(
			'username' => 'someusername',
			'email' => 'notvalid',
			'password' => 'passw0rd',
		));

		$this->assertInstanceOf('Laravel\\Redirect', $response);
		$this->assertEquals('302', $response->foundation->getStatusCode());
	}

	/**
	 * @depends testSignupWithInvalidInput
	 */
	public function testSignupWithInvalidInputHasErrors()
	{
		echo 'There are ' . count($this->getUsers()) . " users\n\n";
		$session_errors = \Laravel\Session::instance()->get('errors')->all();
		$this->assertNotEmpty($session_errors);
	}

	public function testSignupWithGoodInput()
	{
		echo 'There are ' . count($this->getUsers()) . " users\n\n";
		$response = $this->post('account@signup', array(
			'username' => 'validusername',
			'email' => 'some@validemail.com',
			'password' => 'passw0rd',
			'password_confirm' => 'passw0rd',
		));

		$this->assertInstanceOf('Laravel\\Redirect', $response);
		$this->assertEquals('302', $response->foundation->getStatusCode());
	}

	/**
	 * @depends testSignupWithGoodInput
	 */
	public function testSignupWithGoodInputHasNoErrors()
	{
		echo 'There are ' . count($this->getUsers()) . " users\n\n";
		$session_errors = \Laravel\Session::instance()->get('errors')->all();
		print_r($session_errors);
		$this->assertEmpty($session_errors);
	}

}
