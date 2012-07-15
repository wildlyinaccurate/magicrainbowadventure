<?php

namespace MagicRainbowAdventure\Task;

/**
 * Base Entry Task
 *
 * Provides access to common functionality when processing
 * entries in a CLI task.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class EntryTask
{

	/**
	 * Perform a task ($callback) on some entries.
	 *
	 * @param	array	$entry_ids
	 * @return	array
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	protected function _process_entries($entry_ids, \Closure $callback)
	{
		$em = \Laravel\IoC::resolve('doctrine::manager');

		if (empty($entry_ids))
		{
			$entries = $em->getRepository('\Entity\Entry')->findAll();
		}
		else
		{
			$entries = $em->getRepository('\Entity\Entry')->getWhereIdIn($entry_ids);
		}

		$total_entries = count($entries);
		$notify = pow(10, floor(log10($total_entries / 1.5)));

		foreach ($entries as $index => $entry)
		{
			$current = $index + 1;

			call_user_func($callback, $entry, $index);

			if ($current % $notify == 0 || $current == $total_entries)
			{
				echo "\t{$current} / {$total_entries}\n";
			}
		}
	}

}
