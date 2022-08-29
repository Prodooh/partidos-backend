<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* AUTH */
Route::namespace('Auth')->group(function() {
	Route::prefix('auth')->name('auth.')->group(function() {
		// Route::get('email/verify/{token}', 'VerificationController@verify')->name('verification.verify');
		// Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');

		// Route::post('email/password/reset', 'PasswordResetController@sendEmailToken')->name('password.reset');
		// Route::post('email/password/reset/{token}', 'PasswordResetController@resetPassword')->name('reset.password');
	});
	// Route::apiResource('register', 'RegisterController')->only('store');
});

/* OAUTH */
Route::prefix('oauth')->name('oauth.')->group(function() {

	Route::post('token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

	Route::get('token/validate', function () {
	    return ['message' => 'ok', 'code' => 200];
	})->middleware('auth:api');

});

/* USERS */
Route::namespace('User')->group(function() {
	Route::prefix('users')->name('users.')->group(function() {
		Route::get('logout', 'UserController@logout')->name('logout');
		Route::get('me', 'UserController@me')->name('me');
	});
	Route::apiResource('users', 'UserController');
});

/* PHRASES */
Route::namespace('Phrase')->group(function() {
	Route::prefix('phrases')->name('phrases.')->group(function() {
		Route::get('random', 'RandomPhraseController@random')->name('random');
		Route::post('save/image', 'PhraseController@saveImage')->name('save.image');
	});
	Route::apiResource('phrases', 'PhraseController');
});

/* Partidos */
Route::namespace('Partido')->group(function() {
    Route::resource('partidos', 'PartidoController')->only(['index']);
    Route::put('partidos', 'PartidoController@update');
    Route::get('markers/{partido}', 'PartidoController@show');
    Route::post('partidos/save/image', 'PartidoController@saveImage');
});

/* Public Routes */
Route::namespace('PublicController')->group(function() {
    Route::prefix('public')->name('public.')->group(function() {
        Route::get('partido', 'PartidoPublicController@show')->name('partido.show');
    });
});
