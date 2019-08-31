<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeSibling extends Model
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

    protected $table = 'joinee_siblings';

    protected $fillable = ['joinee_id','sibling_name','course','institution'];

    public function joinee_details(){
    	return $this->belongsTo('App\FactSheet\FactSheet');
    }
}
