<?php

namespace Auth\Drivers;

/**
 * MagicRainbowAdventure Auth Driver
 *
 * Provides the authentication methods required by Laravel's
 * Auth class.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class MagicRainbowAuthDriver extends \Laravel\Auth\Drivers\Driver
{

	/**
	 * Attempt to log a user in
	 *
	 * @param	array	$arguments
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function attempt($arguments = array())
	{
		$user = \Laravel\IoC::resolve('doctrine::manager')->getRepository('Entity\User')->findUser($arguments['username']);

		if ($user !== null && $user->checkPassword($arguments['password']))
		{
			return $this->login($user->getId(), array_get($arguments, 'remember'));
		}
	}

	/**
	 * Retrieve the current user's entity. Returns NULL
	 * if the current user is a guest.
	 *
	 * @param	int		$id
	 * @return	\Entity\User|null
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function retrieve($id)
	{
		$user = null;

		if (filter_var($id, FILTER_VALIDATE_INT) !== false)
		{
			$user = \Laravel\IoC::resolve('doctrine::manager')->find('Entity\User', $id);
		}

		return $user;
	}

}
