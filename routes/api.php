<?php

use Illuminate\Http\Request;
use App\SampleQueryView;

Route::any('samples', function (Request $request) {
	$filter = $request->all();
	// ExternalUser::checkPermissions($filter);

	$sample_query_list = SampleQueryView::getSamples($filter);
	
	return json_encode($sample_query_list);
});