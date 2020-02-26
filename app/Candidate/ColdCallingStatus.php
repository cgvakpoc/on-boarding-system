<?php

namespace App\Candidate;

use Illuminate\Database\Eloquent\Model;

class ColdCallingStatus extends Model
{
    protected $table = 'cold_calling_status';
    protected $primaryKey = 'id';
    protected $fillable = ['candidate_id', 'date', 'name', 'created_at', 'updated_at'];
}
