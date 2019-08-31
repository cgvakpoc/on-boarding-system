<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeRemuneration extends Model
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

    protected $table = 'remuneration';

    protected $fillable = ['joinee_id','take_home_sal','deductions','monthly_ctc','yearly_ctc','others'];
}
