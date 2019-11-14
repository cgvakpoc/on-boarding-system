<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeSoftwareRating extends Model
{
	protected $primaryKey = null;
	public $incrementing = false;
    public $timestamps = false;
    protected $table = 'joinee_software_rating';
    protected $fillable = ['joinee_id','software_subject','software_rating'];
}
