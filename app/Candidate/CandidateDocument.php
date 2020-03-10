<?php

namespace App\Candidate;

use Illuminate\Database\Eloquent\Model;

class CandidateDocument extends Model
{
	protected $table = 'candidate_documents';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['candidate_id','document_type','document_id'];
	
	public function document_details() {
		return $this->hasMany('App\Candidate\CandidateDoc','candidate_document_id');
	}
	
	public function document_title() {
		return $this->belongsTo('App\Document','document_id');
	}

	
}
