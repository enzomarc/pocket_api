<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return app()->version();
});

Route::group(['prefix' => 'api'], function () {
	
	Route::post('shop', 'ShopController@store');
	Route::post('login', 'UserController@login');
	Route::post('invitation/{invitation}', 'UserControler@store'); // Create user based on invitation (for role)
	
	Route::group(['middleware' => 'auth'], function () {
	
	});
	
});
