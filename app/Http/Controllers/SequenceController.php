<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\FieldName;
use App\ExternalUser;
use App\CloneDataView;
use App\SequenceMdView;
use App\VquestMetadata;
use App\CloneDataFeature;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SequenceController extends Controller
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

        $t = [];

        if (! isset($filter['output'])) {
            $filter['output'] = 'json';
        }
        switch (strtolower($filter['output'])) {
            case 'csv':
                return response()->download(SequenceMdView::createCsvGW($filter))->deleteFileAfterSend(true);
                break;
            default:
                $sequence_query_list = SequenceMdView::getSequencesQuery($filter);
                $t['items'] = $sequence_query_list;

                $sequence_count = SequenceMdView::getSequencesCount($filter);
                $t['total'] = $sequence_count;

                return json_encode($t);
            break;
        }
    }

    public function clones(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        $t = [];
        if (empty($filter['output']) || ($filter['output'] != 'csv')) {
            $clone_count = CloneDataFeature::getClonesAggregate($filter);
            $t['summary'] = $clone_count;
            $clone_query_list = CloneDataFeature::getClonesQuery($filter);
            $t['items'] = $clone_query_list;

            return json_encode($t);
        } else {
            return response()->download(CloneDataView::createCsvGW($filter))->deleteFileAfterSend(true);
        }
    }

    public function analysis(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        $analysis_list = Analysis::getAnalysis($filter);

        return json_encode($analysis_list);
    }

    public function summary(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        $t = [];
        $sequence_summary_list = VquestMetadata::getAggregate($filter);
        $t['summary'] = $sequence_summary_list;
        $sequence_query_list = SequenceMdView::getSequencesQuery($filter);
        $return_sequence_list = FieldName::convertList($sequence_query_list->toArray(), 'ir_v1_sql', 'ir_v2');
        //$t['items'] = $sequence_query_list;
        $t['items'] = $return_sequence_list;

        return json_encode($t);
    }

    public function data(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        return response()->download(SequenceMdView::createCsvGW($filter))->deleteFileAfterSend(true);
    }
}
