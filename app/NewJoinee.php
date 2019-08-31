<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewJoinee extends Model
{
    protected $table = 'new_joinees';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'department_id','designation_id','date_of_birth','date_of_join','father_name','email','cold_calling_status','commitment_status','joining_bonus','recruiter_name','requirement_details','source_of_hiring','location',' 	travel_accomodation','created_by','updated_by'
    ];
}
