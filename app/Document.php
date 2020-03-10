<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['title'];
}
