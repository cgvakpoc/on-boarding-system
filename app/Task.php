<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	protected $table = 'candidate_tasks';

	protected $primaryKey = 'id';

	protected $fillable = ['candidate_id','task_details','lead_id','document_path','task_status','created_by','updated_by'];
}
