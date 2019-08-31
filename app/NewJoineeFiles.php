<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewJoineeFiles extends Model
{
    protected $table = 'newjoinee_files';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','file_title','file_path','created_by','updated_by'];
}
