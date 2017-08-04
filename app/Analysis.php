<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    protected $table = 'analysis';
    private static $max_results = 50;

    public static function getAnalysis($f)
    {
        //Log::debug($f);

        $query = new self();
        $num_results = self::$max_results;
        $start_at = 0;

        if (isset($f['analysis_psa_id_list']) && ! empty($f['analysis_psa_id_list'])) {
            $query = $query->whereIn('analysis_psa_id', $f['analysis_psa_id_list']);
        }

        if (! empty($f['page_number']) && ($f['page_number'] > 0)) {
            $start_at = $f['page_number'] - 1;
        }
        if (! empty($f['num_results']) && ($f['num_results'] > 0)) {
            $num_results = $f['num_results'];
        }

        $list = $query->skip($start_at * $num_results)->take($num_results)->get();

        return $list;
    }
}
