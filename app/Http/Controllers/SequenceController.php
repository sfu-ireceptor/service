<?php

namespace App\Http\Controllers;

use App\ExternalUser;
use App\SequenceMdView;
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
}
