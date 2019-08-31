<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeSoftwareRating extends Model
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

    protected $table = 'joinee_software_rating';

    protected $fillable = ['joinee_id','software_subject','software_rating'];
}
