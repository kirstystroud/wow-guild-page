<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Default
Route::get('/', '\App\Http\Controllers\HomeController@get');

// Character pages
Route::get('/characters', '\App\Http\Controllers\CharactersController@get');
Route::get('/characters/data', '\App\Http\Controllers\CharactersController@data');

// Dungeons page
Route::get('/dungeons', '\App\Http\Controllers\DungeonsController@get');

// Professions page
Route::get('/professions', '\App\Http\Controllers\ProfessionsController@get');

// Stats page
Route::get('/stats', '\App\Http\Controllers\StatsController@get');
Route::get('/stats/data', '\App\Http\Controllers\StatsController@data');
