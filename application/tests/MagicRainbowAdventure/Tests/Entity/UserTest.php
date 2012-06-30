<?php

namespace MagicRainbowAdventure\Tests\Entity;

use \Entity\User;
use \Entity\Administrator;

class UserTest extends \MagicRainbowAdventure\Tests\BaseTestCase
{

	protected $user;

	public function setUp()
	{
		$this->user = new User;
	}

	public function testPasswordHash()
	{
		$password = 'password';
		$this->user->setPassword($password);
		$this->assertEquals($password, $this->user->checkPassword($password));
	}

	public function testDisplayName()
	{
		$username = 'username';
		$this->user->setUsername($username);
		$this->assertEquals($this->user->getDisplayName(), $username);

		$display_name = 'User Name';
		$this->user->setDisplayName($display_name);
		$this->assertEquals($this->user->getDisplayName(), $display_name);
	}

	public function testIsAdmin()
	{
		$this->assertFalse($this->user->isAdmin());

		$admin = new Administrator;
		$this->assertTrue($admin->isAdmin());
	}

}
