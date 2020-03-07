<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Joinee extends Model
{
	protected $table = 'fact_sheet';
    protected $primaryKey = 'id';
    // protected $fillable = ['name','pos_applied','email','phonenumber','mobile','age','dob','address','permant_address','town','state','father_name','father_contact','father_occupation','mother_name','mother_contact','marital_status','date_joining','blood_group','spouse_name','spouse_number','spouse_occupation','religion','edit_state'];
}
