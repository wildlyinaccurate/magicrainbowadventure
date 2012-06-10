<?php

namespace MagicRainbowAdventure\Logging\Processor;

/**
 * Session Processor
 *
 * Injects session and user data to Monolog log messages.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class SessionProcessor
{

	/**
	 * @param	array	$record
	 * @return	array
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function __invoke(array $record)
	{
		$user = \Laravel\Auth::user() ?: new \Entity\User;

		$record['extra'] = array_merge(
			$record['extra'],
			array(
				'user_id' => $user->getId(),
			)
		);

		return $record;
	}

}
