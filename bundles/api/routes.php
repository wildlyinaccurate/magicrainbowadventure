<?php

use MagicRainbowAdventure\API\RequestHandler,
	Bgy\Doctrine\EntitySerializer,
	Monolog\Logger,
	Monolog\Handler\RotatingFileHandler;

/**
 * Magic Rainbow Adventure API Bundle
 *
 * Routing
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

$logger = IoC::resolve('magicrainbowadventure.logger');
$em = IoC::resolve('doctrine::manager');
$serializer = new EntitySerializer($em);
$serializer->setMaxRecursionDepth(1);

/**
 * Add a log handler for every api call
 */
Route::filter('push_api_logger', function() use ($logger)
{
	$logger->pushHandler(new RotatingFileHandler(path('storage') . 'logs/' . 'magicrainbowadventure-api.log'), Logger::INFO);
});

Route::filter('pattern: api/*', 'push_api_logger');

/**
 * Retrieve one or many entries
 */
Route::get('(:bundle)/entries/(:num?)', function($id = null) use ($em, $serializer, $logger)
{
	if ($id !== null)
	{
		// Retrieve only one entry
		$entry = $em->find('Entity\Entry', $id);

		if ($entry === null)
		{
			return Response::json(array(
				'error' => Lang::line('api::response.entry_not_found')->get(),
			), 404);
		}

		return Response::json($serializer->toArray($entry));
	}

	// Retrieve an array of entries
	$page = max(Input::get('page'), 1);
	$per_page = min(Config::get('api::api.max_items_per_page'), Input::get('per_page', Config::get('api::api.default_items_per_page')));
	$offset = $per_page * ($page - 1);

	$entries = $em->getRepository('Entity\Entry')->getAllEntries($offset, $per_page);
	$return_json = array();

	foreach ($entries as $entry)
	{
		$entry_array = $serializer->toArray($entry);

		// Manually include the moderator data because EntitySerializer doesn't include it
		if ($entry->getModeratedBy() !== null)
		{
			$entry_array['moderated_by'] = $serializer->toArray($entry->getModeratedBy());
		}

		// Retrieve all of the thumbnail URLs
		$entry_array['thumbnail_url'] = array();

		foreach (\Config::get('magicrainbowadventure.entry_thumbnails') as $name => $thumbnail)
		{
			$entry_array['thumbnail_url'][$name] = $entry->getThumbnailUrl($name);
		}

		$return_json[] = $entry_array;
	}

	$logger->addDebug('Returning ' . count($return_json) . ' results.');

	return Response::json($return_json);
});

/**
 * Create or update an entry
 */
Route::post('(:bundle)/entries/(:num?)', function($id = null) use ($em, $serializer, $logger)
{
	if ($id === null)
	{
		// Create a new Entry
		// @TODO
		return Response::make(null, 501);
	}

	// Update an existing entry
	$entry = $em->find('Entity\Entry', $id);

	if ($entry === null)
	{
		return Response::json(array(
			'error' => Lang::line('api::response.entry_not_found')->get(),
		), 404);
	}

	$entry_data = json_decode(Input::get('entry'));

	$moderated_by = $em->find('Entity\User', $entry_data->moderated_by->id);

	$entry->setTitle($entry_data->title)
		->setDescription($entry_data->description)
		->setApproved($entry_data->approved)
		->setModeratedBy($moderated_by);

	$em->persist($entry);
	$em->flush();

	return Response::json($serializer->toArray($entry));
});

/**
 * Retrieve one or many users
 */
Route::get('(:bundle)/users/(:num?)', function($id = null) use ($em, $serializer, $logger)
{
	$serializer->setMaxRecursionDepth(0);

	if ($id !== null)
	{
		$user = $em->find('Entity\User', $id);

		if ($user === null)
		{
			return Response::json(array(
				'error' => Lang::line('api::response.user_not_found')->get(),
			), 404);
		}

		return Response::json($serializer->toArray($user));
	}

	$page = max(Input::get('page'), 1);
	$per_page = min(Config::get('api::api.max_items_per_page'), Input::get('per_page', Config::get('api::api.default_items_per_page')));
	$offset = $per_page * ($page - 1);

	$users = $em->getRepository('Entity\User')->getAllUsers($offset, $per_page);
	$return_json = array();

	foreach ($users as $user)
	{
		$return_json[] = $serializer->toArray($user);
	}

	$logger->addDebug('Returning ' . count($return_json) . ' results.');

	return Response::json($return_json);
});
