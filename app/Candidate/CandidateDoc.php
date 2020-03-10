<?php

namespace App\Candidate;

use Illuminate\Database\Eloquent\Model;

class CandidateDoc extends Model
{
	protected $table = 'candidate_document_details';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['candidate_document_id','file_name','path'];
}
