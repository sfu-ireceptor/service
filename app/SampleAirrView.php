<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SampleAirrView extends Model
{
    protected $table = 'sample_airr_view';

    public static function getSamples($f)
    {
        //Log::debug($f);

        $query = new self();

        if (isset($f['lab_id']) && $f['lab_id'] != '') {
            $query = $query->where('ir_lab_id', '=', $f['lab_id']);
        }

        if (isset($f['project_id']) && ! empty($f['project_id'])) {
            $query = $query->whereIn('ir_project_id', $f['project_id']);
        }

        if (isset($f['subject_gender']) && $f['subject_gender'] != '') {
            $query = $query->where('sex', '=', $f['subject_gender']);
        }

        if (isset($f['subject_code']) && $f['subject_code'] != '') {
            $query = $query->where('subject_id', 'like', '%' . $f['subject_code'] . '%');
        }

        if (isset($f['subject_ethnicity']) && $f['subject_ethnicity'] != '') {
            $query = $query->where('ethnicity', '=', $f['subject_ethnicity']);
        }

        if (isset($f['subject_age_min']) && $f['subject_age_min'] != '') {
            $query = $query->where('subject_age', '>=', $f['subject_age_min']);
        }

        if (isset($f['subject_age_max']) && $f['subject_age_max'] != '') {
            $query = $query->where('subject_age', '<=', $f['subject_age_max']);
        }

        if (isset($f['case_control_id']) && $f['case_control_id'] != '') {
            $query = $query->where('ir_case_control_id', '=', $f['case_control_id']);
        }

        if (isset($f['case_control_name']) && $f['case_control_name'] != '') {
            $query = $query->where('study_group_description', '=', $f['case_control_name']);
        }

        if (isset($f['sample_name']) && $f['sample_name'] != '') {
            $query = $query->where('sample_id', 'like', '%' . $f['sample_name'] . '%');
        }

        if (isset($f['sample_source_id']) && ! empty($f['sample_source_id'])) {
            $query = $query->whereIn('ir_sample_source_id', $f['sample_source_id']);
        }

        if (isset($f['sample_source_name']) && ! empty($f['sample_source_name'])) {
            $query = $query->whereIn('sample_type', $f['sample_source_name']);
        }

        if (isset($f['dna_id']) && ! empty($f['dna_id'])) {
            $query = $query->whereIn('ir_dna_id', $f['dna_id']);
        }

        if (isset($f['dna_type']) && ! empty($f['dna_type'])) {
            $query = $query->whereIn('library_source', $f['dna_type']);
        }

        if (isset($f['ireceptor_cell_subset_name']) && ! empty($f['ireceptor_cell_subset_name'])) {
            $query = $query->whereIn('cell_subset', $f['ireceptor_cell_subset_name']);
        }

        $list = $query->get();

        return $list;
    }
}
