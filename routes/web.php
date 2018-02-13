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
Route::get('/tabard', '\App\Http\Controllers\HomeController@tabardData');

// Character pages
Route::get('/characters', '\App\Http\Controllers\CharactersController@get');
Route::get('/characters/data', '\App\Http\Controllers\CharactersController@data');

// Dungeons page
Route::get('/dungeons', '\App\Http\Controllers\DungeonsController@get');
Route::get('/dungeons/data', '\App\Http\Controllers\DungeonsController@data');
Route::get('/dungeons/data/{dungeon}', '\App\Http\Controllers\DungeonsController@dungeonData');

// Raids page
Route::get('/raids', '\App\Http\Controllers\RaidsController@get');
Route::get('/raids/data', '\App\Http\Controllers\RaidsController@data');
Route::get('/raids/data/{dungeon}', '\App\Http\Controllers\RaidsController@raidData');

// Professions page
Route::get('/professions', '\App\Http\Controllers\ProfessionsController@get');
Route::get('/professions/search', '\App\Http\Controllers\ProfessionsController@search');

// Reputation page
Route::get('/reputation', '\App\Http\Controllers\ReputationController@get');
Route::get('/reputation/data', '\App\Http\Controllers\ReputationController@data');
Route::get('/reputation/data/{faction}', '\App\Http\Controllers\ReputationController@factionData');

// Quests page
Route::get('/quests', '\App\Http\Controllers\QuestsController@get');
Route::get('/quests/search', '\App\Http\Controllers\QuestsController@search');

// Auction pages
Route::get('/auctions', '\App\Http\Controllers\AuctionsController@get');
Route::get('/auctions/data', '\App\Http\Controllers\AuctionsController@data');

// Stats page
Route::get('/stats', '\App\Http\Controllers\StatsController@get');
Route::get('/stats/data/candlestick', '\App\Http\Controllers\StatsController@dataCandlestick');
Route::get('/stats/data/pie', '\App\Http\Controllers\StatsController@dataPie');
Route::get('/stats/data/quests', '\App\Http\Controllers\StatsController@dataPieQuests');
Route::get('/stats/deaths', '\App\Http\Controllers\StatsController@deaths');
Route::get('/stats/kills', '\App\Http\Controllers\StatsController@kills');
Route::get('/stats/pvpkills', '\App\Http\Controllers\StatsController@pvpKills');
Route::get('/stats/dungeons', '\App\Http\Controllers\StatsController@dungeons');
Route::get('/stats/raids', '\App\Http\Controllers\StatsController@raids');
