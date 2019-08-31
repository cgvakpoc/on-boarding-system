<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeJobDetails extends Model
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

    protected $table = 'job_details';

    protected $fillable = ['joinee_id','responsibilities','achievements','ambition','activities','passport'];
}
