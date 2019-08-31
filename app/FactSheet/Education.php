<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
	/**
	 * primaryKey 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $primaryKey = null;

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	
	public $incrementing = false;
    
    public $timestamps = false;

    protected $table = 'joinee_education';

    protected $fillable = ['joinee_id','from','to','qualification','course_name','institution_name','medium','percentage','arrears','class_obtained'];

    /*public function education(){
    	return $this->belongsTo('App\FactSheet\FactSheet','joinee_id');
    }*/
}
