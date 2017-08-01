<?php

namespace App\Http\Controllers;

use App\Stats;
use Carbon\Carbon;
use App\SequenceMdView;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CanarieController extends Controller
{
    public function links()
    {
        return view('canarieLinks');
    }

    public function linkPage($page)
    {
        $url = 'http://ireceptor.irmacs.sfu.ca/platform/' . $page;

        if ($page == 'factsheet') {
            $url = 'http://www.canarie.ca/software/platforms/ireceptor/';
        } elseif ($page == 'provenance' || $page == 'licence') {
            $url = 'http://ireceptor.irmacs.sfu.ca/platform/doc';
        }

        $data = [];
        $data['title'] = '/' . $page;
        $data['page'] = $page;
        $data['url'] = $url;

        return view('canarieLink', $data);
    }

    public function dbInfo(Request $request, Response $response)
    {
        $t = [];

        $t['name'] = 'iReceptor Database Service';
        $t['category'] = 'Data Storage and Retrieval';
        $t['synopsis'] = 'iReceptor Database Service';
        $t['version'] = '0.1';
        $t['institution'] = 'IRMACS/Simon Fraser University';
        $d = new Carbon('first day of July 2015', 'UTC');
        $t['releaseTime'] = $d->toDateString() . 'T' . $d->toTimeString() . 'Z';
        $t['researchSubject'] = 'Immunology';
        $t['supportEmail'] = 'help@irmacs.sfu.ca';
        $t['tags'] = ['immunology', 'iReceptor'];

        if ($request->wantsJson()) {
            return $response->json($t);
        } else {
            return view('canarieInfo', $t);
        }
    }

    public function dbmigrationInfo(Request $request, Response $response)
    {
        $t = [];

        $t['name'] = 'iReceptor Database Migration Service';
        $t['category'] = 'Sensor Management/Data Acquisition';
        $t['synopsis'] = 'iReceptor Database Migration Service';
        $t['version'] = '0.1';
        $t['institution'] = 'IRMACS/Simon Fraser University';
        $d = new Carbon('first day of July 2015', 'UTC');
        $t['releaseTime'] = $d->toDateString() . 'T' . $d->toTimeString() . 'Z';
        $t['researchSubject'] = 'Immunology';
        $t['supportEmail'] = 'help@irmacs.sfu.ca';
        $t['tags'] = ['immunology', 'iReceptor'];

        if ($request->wantsJson()) {
            return $response->json($t);
        } else {
            return view('canarieInfo', $t);
        }
    }

    public function dbStats(Request $request, Response $response)
    {
        $t = [];

        $s = Stats::currentStats();

        $t['nbRequests'] = $s->nb_requests;
        $t['lastReset'] = $s->startDateIso8601();

        if ($request->wantsJson()) {
            return $response->json($t);
        } else {
            $t['name'] = 'iReceptor Database Service Stats';
            $t['key'] = 'Number of requests';
            $t['val'] = $t['nbRequests'];

            return view('canarieStats', $t);
        }
    }

    public function dbmigrationStats()
    {
        $t = [];

        $t['nbSequences'] = SequenceMdView::count();
        $d = new Carbon('last day of June 2015', 'UTC');
        $t['lastReset'] = $d->toDateString() . 'T' . $d->toTimeString() . 'Z';

        if ($request->wantsJson()) {
            return $response->json($t);
        } else {
            $t['name'] = 'iReceptor Database Migration Service Stats';
            $t['key'] = 'Number of sequences';
            $t['val'] = $t['nbSequences'];

            return view('canarieStats', $t);
        }
    }
}
