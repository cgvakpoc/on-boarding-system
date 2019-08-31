<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeCertification extends Model
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

    protected $table = 'joinee_certifications';

    protected $fillable = ['joinee_id','certification_name','completion_year'];

    /*public function certification(){
    	return $this->belongsTo('App\FactSheet\FactSheet');
    }*/
}
