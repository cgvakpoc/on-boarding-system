<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class JoineeSibling extends Model
{
	protected $primaryKey = null;
	public $incrementing = false;
    public $timestamps = false;
    protected $table = 'joinee_siblings';
    protected $fillable = ['joinee_id','sibling_name','course','institution'];
}
