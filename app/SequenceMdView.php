<?php

namespace App;

use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SequenceMdView extends Model
{
    protected $table = 'sequence_md_view';

    private static $max_results = 50;

    public static function getSequencesQuery($f)
    {
        Log::debug($f);

        $query = new self();

        $num_results = self::$max_results;
        $start_at = 0;

        Filter::parseFilter($query, $f);

        if (! empty($f['page_number']) && ($f['page_number'] > 0)) {
            $start_at = $f['page_number'] - 1;
        }
        if (! empty($f['num_results']) && ($f['num_results'] > 0)) {
            $num_results = $f['num_results'];
        }

        return $query->skip($start_at * $num_results)->take($num_results)->get();
    }

    public static function getSequencesCount($f)
    {
        $query = new self();

        Filter::parseFilter($query, $f);

        return $query->count();
    }

    public static function createCsvGW($filter)
    {
        set_time_limit(0);
        //function to CSV-ise a JSON result list for sequence annotation and metadata
        $query = new self();
        Filter::parseFilter($query, $filter);
        $sample_query = new SampleAirrView();
        $sample_rows = $sample_query->whereIn('ir_project_sample_id', $filter['ir_project_sample_id_list'])->get()->toArray();
        $sample_metadata = [];
        foreach ($sample_rows as $sample) {
            $sample_metadata[$sample['ir_project_sample_id']] = $sample;
        }
        $sample_headers = array_keys($sample_rows[0]);

        //unset project_sample_id as it already exists in sequence header
        if (($key = array_search('ir_project_sample_id', $sample_headers)) !== false) {
            unset($sample_headers[$key]);
        }
        $gateway_db_name = '';

        if (! empty($filter['gateway_db_name'])) {
            $gateway_db_name = $filter['gateway_db_name'];
        }
        if (! isset($filter['username'])) {
            $csvname = uniqid();
        } else {
            $csvname = $filter['username'];
        }

        unset($sample_query);
        set_time_limit(300);
        ini_set('memory_limit', '1G');
        $csv_output = '';
        $filename = sys_get_temp_dir() . '/' . $csvname . '-' . date('Y-m-d_G-i-s', time()) . '.csv';
        $file = fopen($filename, 'w');

        $total = $query->count();
        $perPage = 1500;
        $nbPages = ceil($total / $perPage);
        //$lastChunk = $total-($perPage * floor($total/$perPage));
        Log::info('nbPages=' . $nbPages);
        $time_start = microtime(true);
        Log::info($time_start);
        $last_id = -1;
        /*$id_list = $query->select('id')->get();
    	 Log::info('Processed ids');
    	$id_array_list = Array();
    	foreach ($id_list->toArray() as $id_element)
    	{
    	$id_array_list[]= $id_element['id'];
    	}*/

        for ($i = 1; $i <= $nbPages; $i++) {
            Log::info('Processing page ' . $i);
            $time_start_find = microtime(true);
            //if ($i == $nbPages) {$perPage = $lastChunk;};
            $query_chunk = clone $query;
            //$query_chunk = $query_chunk->where('id','>', $last_id);
            //$sequence_list = $query_chunk->where('id', '>', $last_id)->take($perPage)->orderBy('id')->get();
            $id_list = $query_chunk->select('id')->where('id', '>', $last_id)->take($perPage)->orderBy('id')->get();
            $id_array_list = [];
            $time_end_find = microtime(true) - $time_start_find;
            Log::info('find ids time = ' . $time_end_find);
            $sql_log = DB::getQueryLog();
            $chunk_sql = json_encode(end($sql_log));
            Log::info('Query: ' . $chunk_sql);
            foreach ($id_list->toArray() as $id_element) {
                $id_array_list[] = $id_element['id'];
            }

            $time_start_find = microtime(true);
            $sequence_query = new self();
            /*	$id_array_chunk = Array();
    			for ($j = $i-1; $j< ($i*$perPage);$i++)
    			{
    		$id_array_chunk[] = $id_array_list[$j];
    		}
    		//$sequence_query = new VquestMetadata();
    		$sequence_query = $sequence_query->whereIn('id', $id_array_chunk);
    		$sequence_list = $sequence_query->get();*/

            $currentRow = ($i - 1) * $perPage;
            $query_chunk = clone $query;
            $sequence_query = $sequence_query->whereIn('id', $id_array_list);
            $sequence_list = $sequence_query->get()->take($perPage);

            $time_end_find = microtime(true) - $time_start_find;
            Log::info('find sequences time = ' . $time_end_find);
            $sql_log = DB::getQueryLog();
            $chunk_sql = json_encode(end($sql_log));
            Log::info('Query: ' . $chunk_sql);

            $time_end_find = microtime(true) - $time_start_find;
            Log::info('find sequences time = ' . $time_end_find);
            if ($i == 1) {
                $sequence_headers =  array_keys(FieldName::convert($sequence_list[0]->toArray(),'ir_v1_sql', 'ir_v2'));
                $headers_array = array_merge($sequence_headers, $sample_headers);
                $headers_array[] = 'db_name';
                fputcsv($file, $headers_array, ',');
                unset($sample_query);
            }

            $time_start_find = microtime(true);
            foreach ($sequence_list as $s) {
                $sequence_array = $s->toArray();
                $project_sample_id = $sequence_array['project_sample_id'];
                //$sample_row = $sample_query->where('project_sample_id', '=', $project_sample_id)->get()->take(1);
                //$sample_array = $sample_row->toArray();

                $result_array = array_merge($sequence_array, $sample_metadata[$project_sample_id]);
                $result_array[] = $gateway_db_name;
                fputcsv($file, $result_array, ',');
                //fputcsv($file, $sequence_array);
                $last_id = $sequence_array['id'];
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

    public static function CreateVdjml($filter)
    {
        if (! isset($filter['username'])) {
            $csvname = uniqid();
        } else {
            $csvname = $filter['username'];
        }
        $filename = sys_get_temp_dir() . '/' . $csvname . '-' . date('Y-m-d_G-i-s', time()) . '.vdjml';
        $file = fopen($filename, 'w');
        $time = gmdate('YYYY-MM-DDTHH:MM:SS');
        //write out the VDJML Header first

        fwrite($file, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
        fwrite($file, "<vdjml xmlns=\"http://vdjserver.org/vdjml/xsd/1/\" version=\"1.0\" />\n");

        //information about generator
        fwrite($file, "<meta>\n");
        fwrite($file, "<generator name=\"iReceptor\" version=\"0.1\" time_gmt=\"$time\" />\n");

        //find the annotation tools and their versions, and fill out the <aligner> elements
        $query = new self();
        //self::parseFilter($query, $filter);
        Filter::parseFilter($query, $filter);

        $tool_query = clone $query;
        $tool_versions = $tool_query->distinct()->select('annotation_tool', 'tool_version')->groupBy('annotation_tool', 'tool_version')->get();
        $reference_tool_array = [];
        $tool_count = 0;
        foreach ($tool_versions->toArray() as $tool) {
            $annotation_tool = $tool['annotation_tool'];
            $annotation_version = $tool['tool_version'];
            $reference_tool_array[$annotation_tool][$annotation_version] = $tool_count;
            fwrite($file, "<aligner aligner_id=\"$tool_count\" name=\"$annotation_tool\" version=\"$annotation_version\" \n");
            $tool_count++;
        }
        unset($tool_query);
        fwrite($file, "<germline_db gl_db_id=\"1\" />\n");
        fwrite($file, "</meta>\n");

        //each annotation is a <read> element in <read_results> tag
        fwrite($file, "<read_results>\n");

        set_time_limit(300);

        $total = $query->count();
        $perPage = 1500;
        $nbPages = ceil($total / $perPage);
        $time_start = microtime(true);
        $last_id = -1;
        for ($i = 1; $i <= $nbPages; $i++) {
            $query_chunk = clone $query;
            $id_list = $query_chunk->select('id')->where('id', '>', $last_id)->take($perPage)->orderBy('id')->get();
            $id_array_list = [];
            foreach ($id_list->toArray() as $id_element) {
                $id_array_list[] = $id_element['id'];
            }

            $sequence_query = new self();
            $sequence_query = $sequence_query->whereIn('id', $id_array_list);
            $sequence_list = $sequence_query->get()->take($perPage);

            foreach ($sequence_list as $s) {
                // each <read> element is identified by its name, which is sequence_tag in sequence_data table
                $xml_output = '';
                $sequence_array = $s->toArray();

                $tag = $sequence_array['seq_name'];
                $tag = preg_replace('/_/', ' ', $tag);
                $xml_output = '<read read_id="' . $tag . "\">\n";

                // get all the VDJ info first
                $vregion_start = $sequence_array['vregion_start'];
                $vregion_end = $sequence_array['vregion_end'];
                $vregion_len = $vregion_end - $vregion_start;
                $vgene_probability = $sequence_array['vgene_probability'];
                $vgene_score = $sequence_array['vgene_score'];
                $vgene = $sequence_array['vgene_string'];

                $jregion_start = $sequence_array['jregion_start'];
                $jregion_end = $sequence_array['jregion_end'];
                $jregion_len = $jregion_end - $jregion_start;
                //$jgene_probablity = $sequence_array["jgene_probability"];
                //$jgene_score = $sequence_array["jgene_score"];
                $jgene = $sequence_array['jgene_string'];

                $dregion_start = $sequence_array['dregion_start'];
                $dregion_end = $sequence_array['dregion_end'];
                $dregion_len = $dregion_end - $dregion_start;
                //$dgene_probablity = $sequence_array["dgene_probability"];
                //$dgene_score = $sequence_array["dgene_score"];
                $dgene = $sequence_array['dgene_string'];

                // VDJ alignment has a segment for each of V, D and J alignment
                $xml_output .= "<vdj_alignment>\n";

                if (isset($vgene) && ($vgene != '')) {
                    $xml_output .= '<segment_match segment_match_id="1" read_pos0="' . $vregion_start . '" read_len="' . $vregion_len . '" identity="' . $vgene_probability;
                    $xml_output .= '" score="' . $vgene_score . "\" \n>";
                }
                if (isset($jgene) && ($jgene != '')) {
                    $xml_output .= '<segment_match segment_match_id="2" read_pos0="' . $jregion_start . '" read_len="' . $jregion_len . "\" identity=\"\" />\n";

                    //$xml_output .= "\" score=\"".$jgene_score."\" score=\"". $jgene_score. "\" \n>";
                }
                if (isset($dgene) && ($dgene != '')) {
                    $xml_output .= '<segment_match segment_match_id="3" read_pos0="' . $dregion_start . '" read_len="' . $dregion_len . "\" />\n";
                    //$xml_output .= "\" score=\"".$dgene_score."\" score=\"". $dgene_score. "\" \n>";
                }
                fwrite($file, $xml_output);
                $last_id = $sequence_array['id'];
            }

            unset($sequence_list);
            unset($last_row);
            unset($query_chunk);
            unset($sequence_query);
        }

        fclose($file);

        return $filename;
    }
}
