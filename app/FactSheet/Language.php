<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'languages';
    protected $fillable = ['joinee_id','language'];
}
