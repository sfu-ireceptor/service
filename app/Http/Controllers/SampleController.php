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
        if (config('app.auth')) {
            $this->middleware('auth.basic');
        }
    }

    public function index(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        $sample_query_list = SampleQueryView::getSamples($filter);

        return json_encode($sample_query_list);
    }

    public function airr(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        $sample_query_list = SampleAirrView::getSamples($filter);

        return json_encode($sample_query_list);
    }
}
