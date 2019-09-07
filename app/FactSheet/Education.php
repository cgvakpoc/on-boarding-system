<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
	protected $primaryKey = null;
	public $incrementing = false;
    public $timestamps = false;
    protected $table = 'joinee_education';
    protected $fillable = ['joinee_id','from','to','qualification','course_name','institution_name','medium','percentage','arrears','class_obtained'];
}
