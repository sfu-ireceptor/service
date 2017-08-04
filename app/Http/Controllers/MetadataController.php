<?php

namespace App\Http\Controllers;

use App\Lab;
use App\Source;
use App\DnaInfo;
use App\Subject;
use App\ExternalUser;
use App\CellSubsetType;
use App\CaseControlType;
use Illuminate\Http\Request;

class MetadataController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->all();
        ExternalUser::checkPermissions($filter);

        $lab = new Lab();
        $labprojectlist = $lab->getProjectsByLab();
        $ethnicity = Subject::distinct()->get(['ethnicity']);
        $gender = Subject::distinct()->get(['sex']);
        $source = Source::distinct()->get(['sample_source_name']);
        $casecontrol = CaseControlType::distinct()->get(['name']);
        $dnainfo = DnaInfo::distinct()->get(['dna_type']);
        $cellsubsettype = CellSubsetType::get(['subset_name']);

        // instead of sending info as an object, we want to flatten it out. e.g.
        //		gender: ["M", "F"] instead of gender: [ {"sex: M"}, {"sex: F"}]
        // to do so, change the Eloquent query object into an indexed array - can't use
        //		array() function, as that will change it to associative array
        $source_array = [];
        $casecontrol_array = [];
        $dnainfo_array = [];
        $gender_array = [];
        $ethnicity_array = [];
        $subset_array = [];

        foreach ($ethnicity as $ethnicity_element) {
            $ethnicity_array[] = $ethnicity_element['ethnicity'];
        }
        foreach ($casecontrol as $casecontrol_element) {
            $casecontrol_array[] = $casecontrol_element['name'];
        }
        foreach ($dnainfo as $dnainfo_element) {
            $dnainfo_array[] = $dnainfo_element['dna_type'];
        }
        foreach ($source as $source_element) {
            $source_array[] = $source_element['sample_source_name'];
        }
        foreach ($gender as $gender_element) {
            $gender_array[] = $gender_element['sex'];
        }
        foreach ($cellsubsettype as $subset_name) {
            $subset_array[] = $subset_name['subset_name'];
        }

        return json_encode(['labs_projects'=>$labprojectlist,
                    'ethnicity'=>$ethnicity_array,
                    'casecontrol'=>$casecontrol_array,
                    'dnainfo'=>$dnainfo_array,
                    'source'=>$source_array,
                    'gender'=>$gender_array,
                    'cellsubsettypes'=>$subset_array, ]);
    }
}
