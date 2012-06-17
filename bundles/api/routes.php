<?php

use Bgy\Doctrine\EntitySerializer;

/**
 * Magic Rainbow Adventure API Bundle
 *
 * Routing
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

/** @var $entity_manager \Doctrine\ORM\EntityManager */
$entity_manager = IoC::resolve('doctrine::manager');

/**
 * Retrieve one or many entries
 */
Route::get('(:bundle)/entries/(:num?)', function($id = null) use ($entity_manager)
{
	$entity_serializer = new EntitySerializer($entity_manager, 1);

	if ($id !== null)
	{
		$entries = array($entity_manager->find('Entity\Entry', $id));
	}
	else
	{
		$page = max(Input::get('page'), 1);
		$per_page = min(Config::get('api::api.max_items_per_page'), Input::get('per_page', Config::get('api::api.default_items_per_page')));
		$offset = $per_page * ($page - 1);
		$entries = $entity_manager->getRepository('Entity\Entry')->getAllEntries($offset, $per_page);
	}

	$return_json = array();

	foreach ($entries as $entry)
	{
		$entry_array = $entity_serializer->toArray($entry);
		$entry_array['thumbnail_url'] = $entry->getThumbnailUrl('medium');

		$return_json[] = $entry_array;
	}

	return Response::json($return_json);
});