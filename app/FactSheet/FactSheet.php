<?php

namespace App\FactSheet;

use Illuminate\Database\Eloquent\Model;

class FactSheet extends Model
{
    protected $table = 'fact_sheet';

    protected $primaryKey = 'id';

    protected $fillable = ['name','pos_applied','email','phonenumber','mobile','age','dob','address','town','state','father_name','father_occupation','marital_status','spouse_name','spouse_occupation','religion','languages'];

    public function siblings(){
    	return $this->hasMany('App\FactSheet\JoineeSibling','joinee_id');
    }

    public function education(){
    	return $this->hasMany('App\FactSheet\Education','joinee_id');
    }

    public function certification(){
    	return $this->hasMany('App\FactSheet\JoineeCertification','joinee_id');
    }

    public function experience(){
        return $this->hasMany('App\FactSheet\JoineeExperience','joinee_id')->orderBy('to','DESC');
    }

    public function renumeration(){
        return $this->hasMany('App\FactSheet\JoineeRemuneration','joinee_id');
    }
}
