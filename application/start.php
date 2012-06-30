<?php

/*
|--------------------------------------------------------------------------
| PHP Display Errors Configuration
|--------------------------------------------------------------------------
|
| Since Laravel intercepts and displays all errors with a detailed stack
| trace, we can turn off the display_errors ini directive. However, you
| may want to enable this option if you ever run into a dreaded white
| screen of death, as it can provide some clues.
|
*/

ini_set('display_errors', 'On');

/*
|--------------------------------------------------------------------------
| Laravel Configuration Loader
|--------------------------------------------------------------------------
|
| The Laravel configuration loader is responsible for returning an array
| of configuration options for a given bundle and file. By default, we
| use the files provided with Laravel; however, you are free to use
| your own storage mechanism for configuration arrays.
|
*/

Laravel\Event::listen(Laravel\Config::loader, function($bundle, $file)
{
	return Laravel\Config::file($bundle, $file);
});

/*
|--------------------------------------------------------------------------
| Register Class Aliases
|--------------------------------------------------------------------------
|
| Aliases allow you to use classes without always specifying their fully
| namespaced path. This is convenient for working with any library that
| makes a heavy use of namespace for class organization. Here we will
| simply register the configured class aliases.
|
*/

$aliases = Laravel\Config::get('application.aliases');

Laravel\Autoloader::$aliases = $aliases;

/*
|--------------------------------------------------------------------------
| Auto-Loader Mappings
|--------------------------------------------------------------------------
|
| Registering a mapping couldn't be easier. Just pass an array of class
| to path maps into the "map" function of Autoloader. Then, when you
| want to use that class, just use it. It's simple!
|
*/

Autoloader::map(array(
	'Base_Controller' => path('app') . 'controllers/base.php',
));

/*
|--------------------------------------------------------------------------
| Auto-Loader Directories
|--------------------------------------------------------------------------
|
| The Laravel auto-loader can search directories for files using the PSR-0
| naming convention. This convention basically organizes classes by using
| the class namespace to indicate the directory structure.
|
*/

Autoloader::directories(array(
	path('app').'models',
	path('app').'libraries',
));

/*
|--------------------------------------------------------------------------
| Laravel View Loader
|--------------------------------------------------------------------------
|
| The Laravel view loader is responsible for returning the full file path
| for the given bundle and view. Of course, a default implementation is
| provided to load views according to typical Laravel conventions but
| you may change this to customize how your views are organized.
|
*/

Event::listen(View::loader, function($bundle, $view)
{
	return View::file($bundle, $view, Bundle::path($bundle).'views');
});

/*
|--------------------------------------------------------------------------
| Laravel Language Loader
|--------------------------------------------------------------------------
|
| The Laravel language loader is responsible for returning the array of
| language lines for a given bundle, language, and "file". A default
| implementation has been provided which uses the default language
| directories included with Laravel.
|
*/

Event::listen(Lang::loader, function($bundle, $language, $file)
{
	return Lang::file($bundle, $language, $file);
});

/*
|--------------------------------------------------------------------------
| Attach The Laravel Profiler
|--------------------------------------------------------------------------
|
| If the profiler is enabled, we will attach it to the Laravel events
| for both queries and logs. This allows the profiler to intercept
| any of the queries or logs performed by the application.
|
*/

if (Config::get('application.profiler'))
{
	Profiler::attach();
}

/*
|--------------------------------------------------------------------------
| Enable The Blade View Engine
|--------------------------------------------------------------------------
|
| The Blade view engine provides a clean, beautiful templating language
| for your application, including syntax for echoing data and all of
| the typical PHP control structures. We'll simply enable it here.
|
*/

Blade::sharpen();

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| We need to set the default timezone for the application. This controls
| the timezone that will be used by any of the date methods and classes
| utilized by Laravel or your application. The timezone may be set in
| your application configuration file.
|
*/

date_default_timezone_set(Config::get('application.timezone'));

/*
|--------------------------------------------------------------------------
| Start / Load The User Session
|--------------------------------------------------------------------------
|
| Sessions allow the web, which is stateless, to simulate state. In other
| words, sessions allow you to store information about the current user
| and state of your application. Here we'll just fire up the session
| if a session driver has been configured.
|
*/

if ( ! Request::cli() and Config::get('session.driver') !== '')
{
	Session::load();
}

// Register autoloaders
Autoloader::namespaces(array(
	'MagicRainbowAdventure' => path('app') . 'MagicRainbowAdventure',
	'Monolog' => path('base') . 'vendor/monolog/src/Monolog',
));

// Doctrine memcache configuration
//IoC::register('doctrine::cache.provider', function()
//{
//	$memcached_driver = new Doctrine\Common\Cache\MemcachedCache();
//	$memcached_driver->setMemcached(Cache::driver()->memcache);
//
//	return $memcached_driver;
//});

Bundle::start('doctrine');

// Count all queries executed
$em = IoC::resolve('doctrine::manager');
$query_counter = new \MagicRainbowAdventure\Logging\QueryCounter;
$em->getConfiguration()->setSQLLogger($query_counter);

// Dropbox configuration
Event::listen('laravel.started: dropbox', function()
{
	Config::set('dropbox::config.app_key', 'kp99xoktm9q0u0r');
	Config::set('dropbox::config.app_secret', 'i9kkeiojpp15jv0');
	Config::set('dropbox::config.encryption_key', '2f13cf3ed6872967f657df242e571893');
	Config::set('dropbox::config.root', 'dropbox');

	Config::set('dropbox::config.access_token', array(
		'oauth_token_secret' => 'c1y0rnzuzhje17t',
		'oauth_token' => 'lxr3zyvj2p8kxu3',
		'uid' => '28552199',
	));
});

// Register the MagicRainbowAdventure Auth driver
Auth::extend('magicrainbowadventure', function() {
	return new \Auth\Drivers\MagicRainbowAuthDriver;
});

// Monolog setup
$rotating_file_handler = new \Monolog\Handler\RotatingFileHandler(path('storage') . 'logs/' . 'magicrainbowadventure-global.log');

$log = new \Monolog\Logger('global');
$log->pushHandler(new \Monolog\Handler\FingersCrossedHandler($rotating_file_handler));

if ( ! Request::cli())
{
	$log->pushProcessor(new \Monolog\Processor\WebProcessor);
	$log->pushProcessor(new \MagicRainbowAdventure\Logging\Processor\SessionProcessor);
}

IoC::instance('log.global', $log);
