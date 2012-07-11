<?php

/**
 * Auth Driver Mock Class
 *
 * Always authenticates and returns a new User instance.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
namespace MagicRainbowAdventure\Tests\Mocks;

class AuthDriverMock extends \Laravel\Auth\Drivers\Driver
{

	/**
	 * Log In
	 *
	 * @param	array	$arguments
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function attempt($arguments = array())
	{
		return $this->login(1, false);
	}

	/**
	 * Get a user entity
	 *
	 * @param	int		$id
	 * @return	\Entity\User
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function retrieve($id)
	{
		return new \Entity\User;
	}

}
