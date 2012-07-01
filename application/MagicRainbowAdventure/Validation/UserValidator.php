<?php

namespace MagicRainbowAdventure\Validation;

/**
 * User Validation Class
 *
 * Provides validation methods relevant to creating and editing users and user settings.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class UserValidator extends \Laravel\Validator
{

	/**
	 * Validate that the password provided is correct for the User
	 * with ID = $parameters[0]
	 *
	 * @param	string	$attribute
	 * @param	string	$value
	 * @param	array	$parameters
	 * @return	bool
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function validate_current_password_correct($attribute, $value, $parameters)
	{
		$em = \IoC::resolve('doctrine::manager');
		$user = $em->find('Entity\User', $parameters[0]);

		if ($user === null)
		{
			$log = \IoC::resolve('log.global');
			$log->addError(sprintf(\Laravel\Lang::line('account.invalid_user_id'), $parameters[0]));
		}

		if ($user === null || $user->getPassword() !== $user->encryptPassword($value))
		{
			$this->errors->messages[$attribute][] = \Laravel\Lang::line('account.validation_current_password');

			return false;
		}

		return true;
	}

}
