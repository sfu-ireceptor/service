<?php

use App\SampleQueryView;
use Illuminate\Http\Request;

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

Route::any('samples', function (Request $request) {
    $filter = $request->all();
    // ExternalUser::checkPermissions($filter);

    $sample_query_list = SampleQueryView::getSamples($filter);

    return json_encode($sample_query_list);
});

/*
|--------------------------------------------------------------------------
| Misc
|--------------------------------------------------------------------------
*/

// update request count for CANARIE
if (! App::runningInConsole()) {
    App\Stats::incrementNbRequests();
}