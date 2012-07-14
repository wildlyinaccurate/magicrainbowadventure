<?php

use \MagicRainbowAdventure\Tools\EntryThumbnailTool;

/**
 * Generate Thumbnails Task
 *
 * Usage:
 * 	generate-thumbnails
 * 		(Re-)generate the thumbnails for ALL entries
 *
 * 	generate-thumbnails ID [ID...]
 * 		(Re-)generate the thumbnails for specific entries
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Generate_Thumbnails_Task
{

	/**
	 * Run the generate-thumbnails task
	 *
	 * @param	array	$args
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function run($args)
	{
		Bundle::start('resizer');

		$thumbnail_sizes = Config::get('magicrainbowadventure.entry_thumbnails');
		$em = IoC::resolve('doctrine::manager');

		// Treat the arguments as entry IDs
		if (empty($args))
		{
			// Generate thumbnails for all entries
			$entries = $em->getRepository('\Entity\Entry')->findAll();
		}
		else
		{
			$entries = $em->getRepository('\Entity\Entry')->getWhereIdIn($args);
		}

		$total_entries = count($entries);
		$notify = round($total_entries / 10);

		echo "Generating thumbnails for {$total_entries} entries...\n";

		foreach ($entries as $index => $entry)
		{
			$thumbnail_tool = new EntryThumbnailTool($entry);
			$thumbnail_tool->generateFromArray($thumbnail_sizes);

			if ($index > 0 && $notify > 0 && $index % $notify == 0)
			{
				echo "\t{$index} / {$total_entries}\n";
			}
		}

		echo "Done!";
	}

}
