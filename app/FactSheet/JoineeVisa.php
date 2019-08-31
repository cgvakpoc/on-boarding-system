<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeVisa extends Model
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

    protected $table = 'visa_details';

    protected $fillable = ['joinee_id','visa_applied','reject_reason'];
}
