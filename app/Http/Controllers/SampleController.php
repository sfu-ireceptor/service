<?php

namespace App\Http\Controllers;

use App\ExternalUser;
use App\SampleQueryView;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        $sample_query_list = SampleQueryView::getSamples($filter);

        return json_encode($sample_query_list);
    }
}
