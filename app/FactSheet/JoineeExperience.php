<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeExperience extends Model
{
	protected $primaryKey = null;
	public $incrementing = false;
    public $timestamps = false;
    protected $table = 'joinee_experience';
    protected $fillable = ['joinee_id','from','to','total_exp','designation','organisation','location','reason_to_leave'];
}
