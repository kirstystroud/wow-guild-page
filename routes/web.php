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
Route::get('/dungeons/data', '\App\Http\Controllers\DungeonsController@data');
Route::get('/dungeons/data/{dungeon}', '\App\Http\Controllers\DungeonsController@dungeonData');

// Professions page
Route::get('/professions', '\App\Http\Controllers\ProfessionsController@get');
Route::get('/professions/search', '\App\Http\Controllers\ProfessionsController@search');

// Reputation page
Route::get('/reputation', '\App\Http\Controllers\ReputationController@get');
Route::get('/reputation/data', '\App\Http\Controllers\ReputationController@data');
Route::get('/reputation/data/{faction}', '\App\Http\Controllers\ReputationController@factionData');

// Stats page
Route::get('/stats', '\App\Http\Controllers\StatsController@get');
Route::get('/stats/data/candlestick', '\App\Http\Controllers\StatsController@dataCandlestick');
Route::get('/stats/data/pie', '\App\Http\Controllers\StatsController@dataPie');
Route::get('/stats/deaths', '\App\Http\Controllers\StatsController@deaths');
Route::get('/stats/kills', '\App\Http\Controllers\StatsController@kills');
