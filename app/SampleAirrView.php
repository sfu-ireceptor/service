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
        if (isset($f['study_title']) && $f['study_title'] != '') {
            $query = $query->where('study_title', 'like', '%' . $f['study_title'] . '%');
        }

        if (isset($f['study_description']) && $f['study_description'] != '') {
            $query = $query->where('study_description', 'like', '%' . $f['study_description'] . '%');
        }

        if (isset($f['lab_name']) && $f['lab_name'] != '') {
            $query = $query->where('lab_name', 'like', '%' . $f['lab_name'] . '%');
        }

        if (isset($f['organism']) && $f['organism'] != '') {
            $query = $query->where('organism', 'like', '%' . $f['organism'] . '%');
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

        if (isset($f['disease_state_sample']) && $f['disease_state_sample'] != '') {
            $query = $query->where('disease_state_sample', 'like', '%' . $f['disease_state_sample'] . '%');
        }

        if (isset($f['cell_phenotype']) && $f['cell_phenotype'] != '') {
            $query = $query->where('cell_phenotype', 'like', '%' . $f['cell_phenotype'] . '%');
        }

        if (isset($f['platform']) && $f['platform'] != '') {
            $query = $query->where('platform', 'like', '%' . $f['platform'] . '%');
        }

        if (isset($f['ir_sample_source_id']) && ! empty($f['ir_sample_source_id'])) {
            $query = $query->whereIn('ir_sample_source_id', $f['ir_sample_source_id']);
        }

        if (isset($f['tissue']) && ! empty($f['tissue'])) {
            $query = $query->whereIn('tissue', $f['tissue']);
        }

        if (isset($f['sample_type']) && ! empty($f['sample_type'])) {
            $query = $query->whereIn('sample_type', $f['sample_type']);
        }

        if (isset($f['ir_dna_id']) && ! empty($f['ir_dna_id'])) {
            $query = $query->whereIn('ir_dna_id', $f['ir_dna_id']);
        }

        if (isset($f['library_source']) && ! empty($f['library_source'])) {
            $query = $query->whereIn('library_source', $f['library_source']);
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
