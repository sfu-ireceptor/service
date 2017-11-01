<?php

namespace App\Http\Controllers;

use App\FieldName;

class AdminController extends Controller
{
    public function getFieldNames()
    {
        $data = [];
        $data['field_name_list'] = FieldName::all()->toArray();

        return view('fieldNames', $data);
    }

    public function getTest()
    {
        $data = [];
        $data[] = ['project_id' => 1, 'subject_id' => 2];
        $data[] = ['project_id' => 2, 'subject_id' => 3, 'test' => 4];

        var_dump($data);

        echo "\n-------------------------------------\n\n";

        $t = FieldName::convertList($data, 'ir_v1', 'ir_v2');
        var_dump($t);
    }
}
