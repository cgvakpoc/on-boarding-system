<?php

namespace App\Candidate;

use Illuminate\Database\Eloquent\Model;

class CandidateDocument extends Model
{
	protected $table = 'candidate_documents';
    protected $primaryKey = 'id';
    protected $fillable = ['candidate_id','document_title','document_path','created_by','updated_by'];
}
