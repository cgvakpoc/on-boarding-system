<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeJobDetails extends Model
{
	protected $primaryKey = null;
	public $incrementing = false;
    public $timestamps = false;
    protected $table = 'job_details';
    protected $fillable = ['joinee_id','responsibilities','achievements','ambition','activities','passport'];
}
