<?php

/**
 * Sitemap Task
 *
 * Generates a sitemap containing URLs to all live (approved) entries
 *
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Sitemap_Task
{

	/**
	 * Generate the sitemap
	 *
	 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function run($options)
	{
		$base_url = array_shift($options) ?: 'http://magicrainbowadventure.com';
		echo "Generating sitemap with base URL {$base_url}... ";

		Config::set('application.url', $base_url);

		$em = IoC::resolve('doctrine::manager');
		$entries = $em->getRepository('Entity\Entry')->findAll();
		$sitemap_path = path('public') . 'sitemap.txt';

		File::delete($sitemap_path);

		foreach ($entries as $entry)
		{
			File::append($sitemap_path, URL::to("{$entry->getId()}/{$entry->getUrlTitle()}") . "\n");
		}

		echo "Done.\n";
	}

}
