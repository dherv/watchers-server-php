<?php

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

Route::get('movies', 'MovieController@index');
Route::get('movies/{movie}', 'MovieController@show');

Route::get('series', 'SerieController@index');

Route::get('watchlist', 'WatchlistController@index');
Route::post('watchlist/{movie}', 'WatchlistController@store');

Route::post('registration', 'RegistrationController@store');
Route::post('login', 'SessionController@store');
Route::get('logout', 'SessionController@destroy');
