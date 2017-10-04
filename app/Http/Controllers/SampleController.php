<?php

namespace App\Http\Controllers;

use App\ExternalUser;
use App\SampleAirrView;
use App\SampleQueryView;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function __construct()
    {
        if(config('app.auth')) {
            $this->middleware('auth.basic');
        }
    }

    public function index(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        if (isset($filter['output']) && $filter['output'] == 'airr') {
            $sample_query_list = SampleAirrView::getSamples($filter);
        } else {
            $sample_query_list = SampleQueryView::getSamples($filter);
        }

        return json_encode($sample_query_list);
    }
}
