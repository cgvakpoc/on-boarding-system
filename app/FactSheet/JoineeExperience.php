<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeExperience extends Model
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

    protected $table = 'joinee_experience';

    protected $fillable = ['joinee_id','from','to','total_exp','designation','organisation','location','reason_to_leave'];
}
