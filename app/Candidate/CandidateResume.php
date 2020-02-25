<?php

namespace App\Candidate;

use Illuminate\Database\Eloquent\Model;

class CandidateResume extends Model
{
    protected $table= 'candidate_resume';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['candidate_id', 'resume_path'];

}
