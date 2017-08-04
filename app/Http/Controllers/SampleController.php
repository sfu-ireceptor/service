<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\SampleQueryView;
use App\ExternalUser;

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
