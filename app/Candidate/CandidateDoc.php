<?php

namespace App\Candidate;

use Illuminate\Database\Eloquent\Model;

class CandidateDoc extends Model
{
	protected $table = 'candidate_document_details';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['candidate_id','document_path'];
}
