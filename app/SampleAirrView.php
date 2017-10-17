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

        if (isset($f['ir_lab_id']) && $f['ir_lab_id'] != '') {
            $query = $query->where('ir_lab_id', '=', $f['ir_lab_id']);
        }

        if (isset($f['ir_project_id']) && ! empty($f['ir_project_id'])) {
            $query = $query->whereIn('ir_project_id', $f['ir_project_id']);
        }

        if (isset($f['sex']) && $f['sex'] != '') {
            $query = $query->where('sex', '=', $f['sex']);
        }

        if (isset($f['subject_id']) && $f['subject_id'] != '') {
            $query = $query->where('subject_id', 'like', '%' . $f['subject_id'] . '%');
        }

        if (isset($f['ethnicity']) && $f['ethnicity'] != '') {
            $query = $query->where('ethnicity', '=', $f['ethnicity']);
        }

        if (isset($f['subject_age_min']) && $f['subject_age_min'] != '') {
            $query = $query->where('subject_age', '>=', $f['subject_age_min']);
        }

        if (isset($f['subject_age_max']) && $f['subject_age_max'] != '') {
            $query = $query->where('subject_age', '<=', $f['subject_age_max']);
        }

        if (isset($f['ir_case_control_id']) && $f['ir_case_control_id'] != '') {
            $query = $query->where('ir_case_control_id', '=', $f['ir_case_control_id']);
        }

        if (isset($f['study_group_description']) && $f['study_group_description'] != '') {
            $query = $query->where('study_group_description', '=', $f['study_group_description']);
        }

        if (isset($f['sample_name']) && $f['sample_name'] != '') {
            $query = $query->where('sample_id', 'like', '%' . $f['sample_name'] . '%');
        }

        if (isset($f['ir_sample_source_id']) && ! empty($f['ir_sample_source_id'])) {
            $query = $query->whereIn('ir_sample_source_id', $f['ir_sample_source_id']);
        }

        if (isset($f['sample_type']) && ! empty($f['sample_type'])) {
            $query = $query->whereIn('sample_type', $f['sample_type']);
        }

        if (isset($f['ir_dna_id']) && ! empty($f['ir_dna_id'])) {
            $query = $query->whereIn('ir_dna_id', $f['ir_dna_id']);
        }

        if (isset($f['dna_type']) && ! empty($f['dna_type'])) {
            $query = $query->whereIn('library_source', $f['dna_type']);
        }

        if (isset($f['cell_subset']) && ! empty($f['cell_subset'])) {
            $query = $query->whereIn('cell_subset', $f['cell_subset']);
        }

        $list = $query->get();

        return $list;
    }
}
