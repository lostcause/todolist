<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api)
{
	$api->group(['middleware' => ['cors']], function($api)
	{

		// Authentication Routes...
		$api->post('auth/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@login']);
		$api->post('auth/register', 'Auth\AuthController@register');

	});

	//protected API routes with JWT (must be logged in)
	$api->group(['middleware' => ['cors', 'api.auth']], function($api)
	{

		$api->get('task', 'TaskController@index');

		$api->post('task/save', 'TaskController@save');

		$api->patch('task/{id}/status', 'TaskController@status');
		$api->patch('task/{id}/update', 'TaskController@update');

		$api->delete('task/{id}/delete', 'TaskController@delete');
	});
});