<?php

use Bgy\Doctrine\EntitySerializer,
	Monolog\Logger,
	Monolog\Handler\RotatingFileHandler;

/**
 * Magic Rainbow Adventure API Bundle
 *
 * Routing
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

$log = IoC::resolve('log.global');
$entity_manager = IoC::resolve('doctrine::manager');

/**
 * Add a log handler for every api call
 */
Route::filter('push_api_logger', function()
{
	$log = IoC::resolve('log.global');
	$log->pushHandler(new RotatingFileHandler(path('storage') . 'logs/' . 'magicrainbowadventure-api.log'), Logger::INFO);
});

Route::filter('pattern: api/*', 'push_api_logger');

/**
 * Retrieve one or many entries
 */
Route::get('(:bundle)/entries/(:num?)', function($id = null) use ($entity_manager, $log)
{
	$entity_serializer = new EntitySerializer($entity_manager, 1);

	if ($id !== null)
	{
		// Retrieve only one entry
		$entry = $entity_manager->find('Entity\Entry', $id);

		if ($entry === null)
		{
			return Response::json(array(
				'error' => Lang::line('api::response.entry_not_found')->get(),
			), 404);
		}

		return Response::json($entity_serializer->toArray($entry));
	}

	// Retrieve an array of entries
	$page = max(Input::get('page'), 1);
	$per_page = min(Config::get('api::api.max_items_per_page'), Input::get('per_page', Config::get('api::api.default_items_per_page')));
	$offset = $per_page * ($page - 1);

	$entries = $entity_manager->getRepository('Entity\Entry')->getAllEntries($offset, $per_page);
	$return_json = array();

	foreach ($entries as $entry)
	{
		$entry_array = $entity_serializer->toArray($entry);

		// Manually include the moderator data because EntitySerializer doesn't include it
		if ($entry->getModeratedBy() !== null)
		{
			$entry_array['moderated_by'] = $entity_serializer->toArray($entry->getModeratedBy());
		}

		// Retrieve all of the thumbnail URLs
		$entry_array['thumbnail_url'] = array();

		foreach (\Entity\Entry::$thumbnail_sizes as $thumbnail)
		{
			$entry_array['thumbnail_url'][$thumbnail['size']] = $entry->getThumbnailUrl($thumbnail['size']);
		}

		$return_json[] = $entry_array;
	}

	$log->addDebug('Returning ' . count($return_json) . ' results.');

	return Response::json($return_json);
});

/**
 * Create or update an entry
 */
Route::post('(:bundle)/entries/(:num?)', function($id = null) use ($entity_manager, $log)
{
	$entity_serializer = new EntitySerializer($entity_manager, 1);

	if ($id === null)
	{
		// Create a new Entry
		// @TODO
		return Response::make(null, 501);
	}

	// Update an existing entry
	$entry = $entity_manager->find('Entity\Entry', $id);

	if ($entry === null)
	{
		return Response::json(array(
			'error' => Lang::line('api::response.entry_not_found')->get(),
		), 404);
	}

	$entry_data = json_decode(Input::get('entry'));

	$moderated_by = $entity_manager->find('Entity\User', $entry_data->moderated_by->id);

	$entry->setTitle($entry_data->title)
		->setDescription($entry_data->description)
		->setApproved($entry_data->approved)
		->setModeratedBy($moderated_by);

	$entity_manager->persist($entry);
	$entity_manager->flush();

	return Response::json($entity_serializer->toArray($entry));
});

/**
 * Retrieve one or many users
 */
Route::get('(:bundle)/users/(:num?)', function($id = null) use ($entity_manager, $log)
{
	$entity_serializer = new EntitySerializer($entity_manager, 0);

	if ($id !== null)
	{
		$user = $entity_manager->find('Entity\User', $id);

		if ($user === null)
		{
			return Response::json(array(
				'error' => Lang::line('api::response.user_not_found')->get(),
			), 404);
		}

		return Response::json($entity_serializer->toArray($user));
	}

	$page = max(Input::get('page'), 1);
	$per_page = min(Config::get('api::api.max_items_per_page'), Input::get('per_page', Config::get('api::api.default_items_per_page')));
	$offset = $per_page * ($page - 1);

	$users = $entity_manager->getRepository('Entity\User')->getAllUsers($offset, $per_page);
	$return_json = array();

	foreach ($users as $user)
	{
		$return_json[] = $entity_serializer->toArray($user);
	}

	$log->addDebug('Returning ' . count($return_json) . ' results.');

	return Response::json($return_json);
});
