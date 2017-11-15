<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class VquestMetadata extends Model
{
    protected $table = 'vquest_metadata';

    public function sequenceData()
    {
        return $this->hasOne('SequenceData', 'sequence_id');
    }

    public static function getAggregate($filter)
    {
        $query = new self();
        $psa_list = [];
        $counts = [];
        Filter::parseFilter($query, $filter);
        $result = $query->groupBy('project_sample_id')->select('project_sample_id', DB::raw('count(*) as total'))->get();
        foreach ($result as $psa) {
            $psa_list[] = $psa['project_sample_id'];
            $counts[$psa['project_sample_id']] = $psa['total'];
        }

        $sample_query = new SampleAirrView();
        $sample_rows = $sample_query->whereIn('ir_project_sample_id', $psa_list)->get();
        $sample_metadata = [];
        foreach ($sample_rows as $sample) {
            $sample['ir_filtered_sequence_count'] = $counts[$sample['ir_project_sample_id']];
            $sample_metadata[] = $sample;
        }

        return $sample_metadata;
    }
}
