<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your applications using Laravel's RESTful routing, and it
| is perfectly suited for building both large applications and simple APIs.
| Enjoy the fresh air and simplicity of the framework.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post('hello, world', function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

// Named 'shortcut' routes
Route::get('/', 'entries@index');
Route::get('login', 'account@login');
Route::get('(:num)/(:any)', 'entries@view');
Route::post('(:num)/(:any)/favourite', 'entries@favourite');
Route::get('account/my-entries', 'account@my_entries');
Route::post('entries/(:num)/comment', 'entries@comment');

// Register all controller routes
Route::controller(Controller::detect());

// Configure assets with Basset
Bundle::start('basset');

Basset::styles('magicrainbowadventure', function($basset)
{
	$basset->directory('public/assets/css', function($basset)
	{
		$basset->add('reset', 'reset.css')
			->add('1140gs', '1140.css')
			->add('bootstrap', 'bootstrap.css')
			->add('default', 'default.less');
	});
});

Basset::scripts('default', function($basset)
{
	$basset->directory('public/assets/js', function($basset)
	{
		$basset->add('jquery', 'jquery-1.7.2.min.js');
		$basset->add('css3-mediaqueries', 'css3-mediaqueries.js');
		$basset->add('jquery-lazyload', 'jquery-lazyload/jquery.lazyload.js');
		$basset->add('activity-indicator', 'jquery.activity-indicator-1.0.0.min.js');
		$basset->add('bootstrap-tooltip', 'bootstrap/bootstrap-tooltip.js');
		$basset->add('bootstrap-popover', 'bootstrap/bootstrap-popover.js');
	});
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in "before" and "after" filters are called before and
| after every request to your application, and you may even create other
| filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		Session::flash('auth_referrer', URL::current());

		return Redirect::to('login');
	}
});
