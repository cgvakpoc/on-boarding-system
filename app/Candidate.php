<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{	
	protected $table = 'candidate_documents';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['candidate_id','document_title','document_path','created_by','updated_by'];
}
