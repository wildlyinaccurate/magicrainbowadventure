<?php

namespace MagicRainbowAdventure\Encryption;

/**
 * Bcrypt Encryption Library
 *
 *
 * @author  Marco Arment <me@marco.org>
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 * @link	https://gist.github.com/1053158/
 */
class Bcrypt
{

	const DEFAULT_WORK_FACTOR = 10;

	/**
	 * Hash a password
	 *
	 * @param	string	$password		Password to be hashed
	 * @param	int		$work_factor	Work factor used when generating the salt.
	 * 									Higher work factor takes longer to encrypt.
	 * @return	string
	 * @author  Marco Arment <me@marco.org>
	 */
	public static function hash($password, $work_factor = 0)
	{
		if (version_compare(PHP_VERSION, '5.3') < 0)
		{
			throw new Exception('Bcrypt requires PHP 5.3 or above');
		}

		if ( ! function_exists('openssl_random_pseudo_bytes'))
		{
			throw new Exception('Bcrypt requires openssl PHP extension');
		}

		if ($work_factor < 4 || $work_factor > 31)
		{
			$work_factor = self::DEFAULT_WORK_FACTOR;
		}

		$salt = '$2a$' . str_pad($work_factor, 2, '0', STR_PAD_LEFT) . '$' . substr(strtr(base64_encode(openssl_random_pseudo_bytes(16)), '+', '.'), 0, 22);

		return crypt($password, $salt);
	}

	/**
	 * Check if a plain-text password is the same as a hash
	 *
	 * @param	string	$password		Plain-text password to check
	 * @param	string	$stored_hash	Hash to check against
	 * @param	mixed	$legacy_handler	If the password is a legacy hash, use
	 * 									this handler to generate the hash
	 * @return	bool
	 * @author  Marco Arment <me@marco.org>
	 */
	public static function check($password, $stored_hash, $legacy_handler = NULL)
	{
		if (version_compare(PHP_VERSION, '5.3') < 0)
		{
			throw new Exception('Bcrypt requires PHP 5.3 or above');
		}

		if (self::is_legacy_hash($stored_hash))
		{
			if ($legacy_handler)
			{
				return call_user_func($legacy_handler, $password, $stored_hash);
			}
			else
			{
				throw new Exception('Unsupported hash format');
			}
		}

		return (crypt($password, $stored_hash) === $stored_hash);
	}

	/**
	 * Determine whether a hash is legacy, i.e. was not generated with hash()
	 *
	 * @param	string	$hash
	 * @return	bool
	 * @author  Marco Arment <me@marco.org>
	 */
	public static function is_legacy_hash($hash)
	{
		return (substr($hash, 0, 4) !== '$2a$');
	}

}
