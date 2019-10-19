<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IDCard extends Model
{
    protected $table = 'id_card';
    protected $primaryKey = 'id';
    protected $fillable = ['emp_code','name','address','blood_group','document_path','created_by','updated_by'];
}
