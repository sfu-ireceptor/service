<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\ExternalUser;
use App\CloneDataView;
use App\SequenceMdView;
use App\VquestMetadata;
use App\CloneDataFeature;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SequenceController extends Controller
{
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
                return Response::download(SequenceMdView::createCsvGW($filter))->deleteFileAfterSend(true);
                break;
            case 'vdjml':
                return Response::download(SequenceMdView::createVdjml($filter))->deleteFileAfterSend(true);
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
            $t['aggregates'] = $clone_count;
            $clone_query_list = CloneDataFeature::getClonesQuery($filter);
            $t['clones'] = $clone_query_list;

            return json_encode($t);
        } else {
            return Response::download(CloneDataView::createCsvGW($filter));
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
        $t['aggregation_summary'] = $sequence_summary_list;
        $sequence_query_list = SequenceMdView::getSequencesQuery($filter);
        $t['sequences'] = $sequence_query_list;

        return json_encode($t);
    }
}
