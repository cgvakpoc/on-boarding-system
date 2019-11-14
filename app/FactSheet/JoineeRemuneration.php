<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeRemuneration extends Model
{	
	protected $primaryKey = null;
	public $incrementing = false;
    public $timestamps = false;
    protected $table = 'remuneration';
    protected $fillable = ['joinee_id','take_home_sal','deductions','monthly_ctc','yearly_ctc','others'];
}
