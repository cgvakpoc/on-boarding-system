<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use App\Candidate\Candidate;
use App\Joinee;
use Auth;
use DB;

class JoineeController extends Controller
{
    public function __construct(Request $request)
    {
    	// if(!isset($request->token)){
    	// 	return response()->json(['status'	=>	false]);
    	// }

    	// $this->user = JWTAuth::parseToken()->authenticate();
    }

    protected $validationRules = [
    	'name'				=>	'required',
    	'phonenumber'		=>	'required',
    	'father_name'		=>	'required',
    	'mother_name'		=>	'required',
    	'address'			=>	'required',
    	'permant_address'	=>	'required',
    	'dob'				=>	'required',
    	'date_joining'		=>	'required',
    	'email'				=>	'required|email|unique:fact_sheet,email',
	];
	
	public function index(Request $request,$msg='')
	{
	}

    public function addJoinee(Request $request)
    {
		$userid = Auth::user()->id;
		$validator = Validator::make($request->all(),$this->validationRules);
		
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$doj = convert_date($request->doj);
		$dob = convert_date($request->dob);

		$addJoinee = new Joinee();
		$addJoinee->address=$request->address;
		$addJoinee->blood_group=$request->blood_group;
		$addJoinee->dob=$dob;
		$addJoinee->date_joining=$doj;
		$addJoinee->email=$request->email;
		$addJoinee->father_name=$request->father_name;
		$addJoinee->father_contact=$request->father_contact;
		$addJoinee->mobile=$request->mobile;
		$addJoinee->mother_name=$request->mother_name;
		$addJoinee->mother_contact=$request->mother_contact;
		$addJoinee->name=$request->name;
		$addJoinee->phonenumber=$request->phonenumber;
		$addJoinee->permanent_address=$request->permant_address;
		$addJoinee->spouse_name=$request->spouse_name;
		$addJoinee->spouse_number=$request->spouse_number;
		$addJoinee->updated_by=$userid;
		$save = $addJoinee->save();
		if($save === 0){
			$msg = 'Joinee details cannot be added';
			bad_request(false,$msg);
			die;
		}
		$joinee = Joinee::where('id',$addJoinee->id)->get();
		$msg = 'Joinee details added successfully';
		success_200(true,$joinee,$msg);
    }
}
