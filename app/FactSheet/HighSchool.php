<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class HighSchool extends Model
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

    protected $table = 'high_school';

    protected $fillable = ['joinee_id','maths_marks_10','maths_marks_12'];
}
