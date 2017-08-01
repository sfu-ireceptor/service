<?php

use App\SampleQueryView;
use Illuminate\Http\Request;
use App\Lab;
//use App\Project;
use App\CaseControlType;
use App\CellSubsetType;
use App\DnaInfo;
use App\Source;
use App\Subject;

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

Route::any('metadata', function (Request $request) {
		$filter = $request->all();
		// ExternalUser::checkPermissions($filter);
		$lab = new Lab();
		$labprojectlist = $lab->getProjectsByLab();
		$ethnicity= Subject::distinct()->get(array('ethnicity'));
		$gender=Subject::distinct()->get(array('sex'));
		$source = Source::distinct()->get(array('sample_source_name'));
		$casecontrol = CaseControlType::distinct()->get(array('name'));
		$dnainfo = DnaInfo::distinct()->get(array('dna_type'));
		$cellsubsettype = CellSubsetType::get(array('subset_name'));
		
		
		// instead of sending info as an object, we want to flatten it out. e.g.
		//		gender: ["M", "F"] instead of gender: [ {"sex: M"}, {"sex: F"}]
		// to do so, change the Eloquent query object into an indexed array - can't use
		//		array() function, as that will change it to associative array
		$source_array = Array();
		$casecontrol_array = Array();
		$dnainfo_array = Array();
		$gender_array = Array();
		$ethnicity_array = Array();
		$subset_array = Array();
		foreach ($ethnicity as $ethnicity_element)
		{
			$ethnicity_array[]= $ethnicity_element['ethnicity'];
		}
		foreach ($casecontrol as $casecontrol_element)
		{
			$casecontrol_array[]= $casecontrol_element['name'];
		}
		foreach ($dnainfo as $dnainfo_element)
		{
			$dnainfo_array[]= $dnainfo_element['dna_type'];
		}
		foreach ($source as $source_element)
		{
			$source_array[]= $source_element['sample_source_name'];
		}
		foreach ($gender as $gender_element)
		{
		
			$gender_array[]= $gender_element['sex'];
		}
		foreach ($cellsubsettype as $subset_name)
		{
			$subset_array[] = $subset_name['subset_name'];
		}
		
		return (json_encode(array('labs_projects'=>$labprojectlist,
				'ethnicity'=>$ethnicity_array,
				'casecontrol'=>$casecontrol_array,
				'dnainfo'=>$dnainfo_array,
				'source'=>$source_array,
				'gender'=>$gender_array,
				'cellsubsettypes'=>$subset_array)));
		
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
