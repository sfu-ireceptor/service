<?php

namespace App\Http\Controllers;

use App\ExternalUser;
use App\SampleQueryView;
use App\SampleAirrView;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        if (isset($filter["output"]) && $filter["output"] == "airr")
        {    
          $sample_query_list = SampleAirrView::getSamples($filter);
        }
        else
        {

          $sample_query_list = SampleQueryView::getSamples($filter);
        }
        return json_encode($sample_query_list);
    }
    public function airr_samples(Request $request)
    {

    }
}
