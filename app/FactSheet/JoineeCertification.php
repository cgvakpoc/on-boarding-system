<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeCertification extends Model
{
	protected $primaryKey = null;
	public $incrementing = false;
    public $timestamps = false;
    protected $table = 'joinee_certifications';
    protected $fillable = ['joinee_id','certification_name','completion_year'];
}
