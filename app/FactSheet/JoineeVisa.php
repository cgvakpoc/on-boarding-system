<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeVisa extends Model
{
	protected $primaryKey = null;
	public $incrementing = false;
    public $timestamps = false;
    protected $table = 'visa_details';
    protected $fillable = ['joinee_id','visa_applied','reject_reason'];
}
