<?php

namespace App\Candidate;

use Illuminate\Database\Eloquent\Model;

class CandidateDocument extends Model
{
	protected $table = 'candidate_documents';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['candidate_id','document_title'];
}
