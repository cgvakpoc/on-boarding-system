<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'activities';
    protected $fillable = ['joinee_id','activity'];
}
