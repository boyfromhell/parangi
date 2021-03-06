<?php

ini_set('error_reporting', 'E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING');

global $me;

/*
|--------------------------------------------------------------------------
| Authorization
|--------------------------------------------------------------------------
|
*/

$me = new User();

if( Auth::check() || Auth::viaRemember() ) {
	$me = Auth::user();
}
else {
	//$me->name = 'Guest';
}

View::share('is_mobile', Helpers::is_mobile());
View::share('me', $me);
View::share('cdn', Config::get('app.cdn'));
View::share('skin', '/images/skins/ivan/');

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

	// custom
	app_path().'/libraries',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

// @todo move the following lines to a Service Provider

View::addLocation(base_path().'/vendor/earlybirdmvp/foundry/views');
View::addNamespace('foundry', base_path().'/vendor/earlybirdmvp/foundry/views');

Validator::extend('checkHashedPass', function($attribute, $value, $parameters)
{
	if( ! Hash::check( $value , $parameters[0] ) )
	{
		return false;
	}
	return true;
});

App::error( function(Exception $exception, $code )
{
	global $me;

	if( Config::get('app.debug') ) {
		return;
	}
	if ( ! in_array($code, [401, 403, 404, 500]) ) {
		return;
	}

	if( ! $me->id && $code == 403 ) {
		Session::push('errors', 'You must login to view this page');
	}

	$data = array(
		'code' => $code,
	);

	return Response::view('errors.'.$code, $data, $code);
});

