<?php

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

// CANARIE monitoring - dynamic pages
Route::get('db/service/info', 'CanarieController@dbInfo');
Route::get('dbmigration/service/info', 'CanarieController@dbmigrationInfo');

Route::get('db/service/stats', 'CanarieController@dbStats');
Route::get('dbmigration/service/stats', 'CanarieController@dbmigrationStats');

// CANARIE monitoring - static pages
Route::get('canarie', 'CanarieController@links');

Route::get('db/service/{page}', 'CanarieController@linkPage');
Route::get('dbmigration/service/{page}', 'CanarieController@linkPage');

/*
|--------------------------------------------------------------------------
| Require authentication
|--------------------------------------------------------------------------
*/

Route::any('metadata', 'MetadataController@index');
Route::any('samples', 'SampleController@index');
Route::any('sequences', 'SequenceController@index');
Route::any('analysis', 'SequenceController@analysis');
Route::any('clones', 'SequenceController@clones');

Route::any('/v2/samples', 'SampleController@index');
Route::any('/v2/clones_summary', 'SequenceController@clones');
Route::any('/v2/sequences_summary', 'SequenceController@summary');

/*
|--------------------------------------------------------------------------
| Misc
|--------------------------------------------------------------------------
*/

// update request count for CANARIE
if (! app()->runningInConsole()) {
    App\Stats::incrementNbRequests();
}
