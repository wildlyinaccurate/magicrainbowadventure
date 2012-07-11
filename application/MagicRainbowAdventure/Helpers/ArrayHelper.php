<?php

namespace MagicRainbowAdventure\Helpers;

/**
 * Array Helper Class
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class ArrayHelper
{

	/**
	 * Recursively search an array for a given value. Returns the root element key if $needle
	 * is found, or FALSE if $needle cannot be found.
	 *
	 * @param	mixed	$needle
	 * @param	array	$haystack
	 * @param	bool	$strict
	 * @return	mixed|bool
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public static function recursive_array_search($needle, $haystack, $strict = true)
	{
		$iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($haystack), \RecursiveIteratorIterator::SELF_FIRST);

		while ($iterator->valid())
		{
			if ($iterator->getDepth() === 0)
			{
				$current_key = $iterator->key();
			}

			if ($strict && $iterator->current() === $needle)
			{
				return $current_key;
			}
			elseif ($iterator->current() == $needle)
			{
				return $current_key;
			}

			$iterator->next();
		}

		return false;
	}

}
