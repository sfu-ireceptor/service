<?php

namespace App;

use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CloneDataFeature extends Model
{
    protected $table = 'clone_data_feature';
    private static $max_results = 50;
    public static $coltype = [
         'id'             => 'int',
         'cd_analysis_id' => 'int',
         'vgene_family'   => 'string',
         'vgene_gene'     => 'string',
         'vgene_allele'   => 'string',
         'jgene_family'   => 'string',
         'jgene_gene'   => 'string',
         'jgene_allele'   => 'string',
         'dgene_family'   => 'string',
         'dgene_gene'     => 'string',
         'dgene_allele'   => 'string',
         'feature_name'   => 'string',

         'nt_sequence'  => 'string',
         'aa_sequence'    => 'string',
         'length'         => 'int',
         'clone_count'    => 'int',

    ];

    public static function parseFilter(&$query, $f)
    {
        if (isset($f['cd_analysis_id_list'])) {
            $query = $query->whereIn('cd_analysis_id', $f['cd_analysis_id_list']);
        }

        foreach ($f as $filtername => $filtervalue) {
            if (empty(self::$coltype[$filtername]) || $filtervalue == '') {
                continue;
            }
            if ($filtername == 'cd_analysis_id_list') {
                continue;
            }
            if (self::$coltype[$filtername] == 'string') {
                $query = $query->where($filtername, 'like', '%' . $filtervalue . '%');
            }
            if (self::$coltype[$filtername] == 'int') {
                $query = $query->where($filtername, '=', $filtervalue);
            }
        }
    }

    public static function getClonesQuery($f)
    {
        Log::debug($f);

        $query = new self();

        $num_results = self::$max_results;
        $start_at = 0;

        self::parseFilter($query, $f);

        if (! empty($f['page_number']) && ($f['page_number'] > 0)) {
            $start_at = $f['page_number'] - 1;
        }
        if (! empty($f['num_results']) && ($f['num_results'] > 0)) {
            $num_results = $f['num_results'];
        }

        return $query->skip($start_at * $num_results)->take($num_results)->get();
    }

    public static function getClonesCount($f)
    {
        $query = new self();

        self::parseFilter($query, $f);

        return $query->count();
    }

    public static function getClonesAggregate($f)
    {
        $query = new self();
        $psa_list = [];
        $counts = [];
        $sample_metadata = [];
        self::parseFilter($query, $f);
        $result = $query->groupBy('psa_id')->select('psa_id', DB::raw('count(*) as total'))->get();
        foreach ($result as $psa) {
            $psa_list[] = $psa['psa_id'];
            $counts[$psa['psa_id']] = $psa['total'];
        }
        if (count($psa_list) < 1) {
            return $sample_metadata;
        }
        $sample_query = new SampleQueryView();
        $sample_rows = $sample_query->whereIn('project_sample_id', $psa_list)->get();
        foreach ($sample_rows as $sample) {
            $sample['clones'] = $counts[$sample['project_sample_id']];
            $sample_metadata[] = $sample;
        }

        return $sample_metadata;
    }

    public static function createCsvGW($filter)
    {
        //function to CSV-ise a JSON result list for sequence annotation and metadata
        $query = new self();
        self::parseFilter($query, $filter);

        set_time_limit(300);
        ini_set('memory_limit', '1G');
        $csv_output = '';
        $filename = sys_get_temp_dir() . '/' . $filter['username'] . '-' . date('Y-m-d_G-i-s', time()) . '.csv';
        $file = fopen($filename, 'w');

        $total = $query->count();
        $perPage = 1500;
        $nbPages = ceil($total / $perPage);
        //$lastChunk = $total-($perPage * floor($total/$perPage));
        Log::info('nbPages=' . $nbPages);
        $time_start = microtime(true);
        Log::info($time_start);
        $last_id = -1;

        for ($i = 1; $i <= $nbPages; $i++) {
            Log::info('Processing page ' . $i);
            $time_start_find = microtime(true);
            //if ($i == $nbPages) {$perPage = $lastChunk;};
            $query_chunk = clone $query;
            //$query_chunk = $query_chunk->where('gene_feature_id','>', $last_id);
            //$sequence_list = $query_chunk->where('gene_feature_id', '>', $last_id)->take($perPage)->orderBy('gene_feature_id')->get();
            $id_list = $query_chunk->select('gene_feature_id')->where('gene_feature_id', '>', $last_id)->take($perPage)->orderBy('gene_feature_id')->get();
            $id_array_list = [];
            $time_end_find = microtime(true) - $time_start_find;
            Log::info('find ids time = ' . $time_end_find);
            $sql_log = DB::getQueryLog();
            $chunk_sql = json_encode(end($sql_log));
            Log::info('Query: ' . $chunk_sql);
            foreach ($id_list->toArray() as $id_element) {
                $id_array_list[] = $id_element['gene_feature_id'];
            }

            $time_start_find = microtime(true);
            $sequence_query = new self();

            $currentRow = ($i - 1) * $perPage;
            $query_chunk = clone $query;
            $sequence_query = $sequence_query->whereIn('gene_feature_id', $id_array_list);
            $sequence_list = $sequence_query->get()->take($perPage);

            $time_end_find = microtime(true) - $time_start_find;
            Log::info('find sequences time = ' . $time_end_find);
            $sql_log = DB::getQueryLog();
            $chunk_sql = json_encode(end($sql_log));
            Log::info('Query: ' . $chunk_sql);

            $time_end_find = microtime(true) - $time_start_find;
            Log::info('find sequences time = ' . $time_end_find);
            if ($i == 1) {
                $headers_array = array_keys($sequence_list[0]->toArray());
                $headers_array[] = 'db_name';
                fputcsv($file, $headers_array, ',');
                unset($sample_query);
            }

            $time_start_find = microtime(true);
            foreach ($sequence_list as $s) {
                $sequence_array = $s->toArray();
                //fputcsv($file, $s, ",");
                fputcsv($file, $sequence_array);
                $last_id = $sequence_array['gene_feature_id'];
                //unset($sample_query);
            }

            $time_end_find = microtime(true) - $time_start_find;
            Log::info('write sequences time = ' . $time_end_find);

            unset($id_array_chunk);
            unset($sequence_list);
            unset($last_row);
            unset($query_chunk);
            unset($sequence_query);
        }
        $time_end = microtime(true);
        Log::info('Total End Time: ' . $time_end);
        $diff = $time_end - $time_start;
        Log::info('Time Spent Generating CSV: ' . $diff);
        fclose($file);

        return $filename;
    }
}
