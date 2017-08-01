<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;


class Lab extends Model
{
	protected $table = "lab";
	
	public function project()
	{
		return $this->hasMany('Project', 'lab_id' );
	}
	
	public function getProjectsByLab()
	{
		$labs = Lab::all();
		$lablist =  Array();
		$labindex = 0;
		
		foreach ($labs as $lab)
		{
			$labname = $lab['name'];
			$labid = $lab['id'];
		
			$projectlist = new Project();
			$parentidlist = $projectlist->distinct()->select('parent_project_id')->where('lab_id', '=', $labid)->whereNotNull('parent_project_id')->get();
			$childprojects = $projectlist::select('id', 'name', 'sra_accession')->where('lab_id', '=', $labid)->whereNotIn('id', $parentidlist->toArray())->get();
			$lablist[$labindex]['name'] = $labname;
			$lablist[$labindex]['id']=$labid;
			$lablist[$labindex]['projects'] = $childprojects;
			$labindex++;			
		}
		return($lablist);
	}
}
