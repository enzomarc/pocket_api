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
	
	Route::post('register', 'ShopController@store');
	Route::post('login', 'UserController@login');
	Route::post('invitation/{invitation}', 'UserControler@store'); // Create user based on invitation (for role)
	Route::get('check/{token}', 'UserController@check');  // Check user with the given token.
	
	/* User non-secured routes */
	Route::put('teams/{user}', 'UserController@update');
	
	Route::group(['middleware' => 'auth'], function () {
		Route::post('shop/{shop}/invitations', 'InvitationController@store');
		Route::put('shop/{shop}', 'ShopController@update');
		Route::put('shop/{shop}/shop', 'ShopController@update_logo');
		Route::post('shop/{shop}/verifications', 'VerificationController@store');  // Store verification
		Route::put('shop/{shop}/verifications', 'VerificationController@update');
		Route::get('shop/{shop}/verifications', 'VerificationController@show');
	});
	
});
