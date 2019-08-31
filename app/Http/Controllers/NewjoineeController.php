<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Validator;
use Auth;
use App\NewJoinee;

class NewjoineeController extends Controller
{
   protected $user;

	public function __construct(Request $request)
	{
	   	if(!isset($request->token)){
	   	  return response()->json(['success'	=>	false]);
	   	}
	   	
	   	$this->user = JWTAuth::parseToken()->authenticate();
	}

	protected $validationRules = [
		'name'					=>	'required|string|min:3|max:255',
		'department_id'			=>	'required',
		'designation_id'		=>	'required',
		'doj'					=>	'required',
		'dob'					=>	'required',
		'father_name'			=>	'required|string|max:255',
		'email_id'				=>	'required|string|email|unique:new_joinees,email',
		'cold_call_status'		=>	'required|string',
		'commitment_status'		=>	'required|string',
		'recruiter_name'		=>	'required|string|max:255',
		'requirement_detail'	=>	'required|string|max:255',
		'source_of_hire'		=>	'required|string|max:255',
		'location'				=>	'required|string|max:255',
		'accomodation'			=>	'required|string|max:255'
	];

	public function index(Request $request)
	{
      $sort = $request->sort;
      $order = $request->order;
      $search = $request->search;
      $limit = $request->limit;

      $newjoinee_list = NewJoinee::query();

      if($search){
         $newjoinee_list = $newjoinee_list->where('name','LIKE','%'.$search.'%')
                           ->where('email','LIKE','%'.$search.'%')
                           ->where('recruiter_name','LIKE','%'.$search.'%')
                           ->orWhere('source_of_hiring','LIKE','%'.$search.'%');
      }
      if($sort && $order){
         $list = $newjoinee_list->orderBy($sort,$order)->paginate($limit); 
      } else{
         $list = $newjoinee_list->orderBy('id','ASC')->paginate($limit);    
      }

      if(count($list) == ''){
      	$msg = 'No search results found for the query '.$search;
        error_404(false,$msg);
        die;
      }
      success_200(true,'',$list);
   }

	public function store(Request $request)
	{ 
        $userid = Auth::user()->id;
		$validator = Validator::make($request->all(),$this->validationRules);

		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$doj = convert_date($request->doj);
		$dob = convert_date($request->dob);

		$new_joinee = new NewJoinee();

		$new_joinee->name 			= $request->name;
		$new_joinee->department_id 	= $request->department_id;
		$new_joinee->designation_id = $request->designation_id;
		$new_joinee->date_of_birth  = $dob;
		$new_joinee->date_of_join 	= $doj;
		$new_joinee->father_name 	= $request->father_name;
		$new_joinee->email = $request->email_id;
		$new_joinee->cold_calling_status = $request->cold_call_status;
		$new_joinee->commitment_status = $request->commitment_status;
		$new_joinee->joining_bonus = $request->joining_bonus;
		$new_joinee->recruiter_name = $request->recruiter_name;
		$new_joinee->requirement_details = $request->requirement_detail;
		$new_joinee->source_of_hiring = $request->source_of_hire;
		$new_joinee->location = $request->location;
		$new_joinee->travel_accomodation = $request->accomodation;
		$new_joinee->created_by = $userid;
		$new_joinee->updated_by = $userid;

		$newjoinee_saved = $new_joinee->save();

		$msg = 'New Joinee details has been added successfully';
		success_200(true,$msg,$new_joinee);
	}

	public function update(Request $request)
	{
		$id = $request->id;
		$joinee = NewJoinee::find($id);

		if(!$joinee){
			$msg = 'Joinee with id '.$id.' cannot be found';
			error_404(false,$msg);
			die;
        }

        $this->validationRules['email_id'] = 'required|string|email|unique:new_joinees,email,'.$id.',id';
		$validator = Validator::make($request->all(),$this->validationRules);
		
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$doj = convert_date($request->doj);
      	$dob = convert_date($request->dob);

		$update_joinee = $joinee->update([
			'name'					=>	$request->name,
			'department_id'			=>	$request->department_id,
			'designation_id'		=>	$request->designation_id,
			'date_of_birth'			=>	$dob,
			'date_of_join'			=>	$doj,
			'father_name'			=>	$request->father_name,
			'cold_calling_status'	=>	$request->cold_call_status,
			'commitment_status'		=>	$request->commitment_status,
			'joining_bonus'			=>	$request->joining_bonus,
			'recruiter_name'		=>	$request->recruiter_name,
			'requirement_details'	=>	$request->requirement_detail,
			'source_of_hiring'		=>	$request->source_of_hire,
			'location'				=>	$request->location,
			'travel_accomodation'	=>	$request->accomodation,
			'updated_by'			=>	$userid
		]);

		$msg = 'Details updated successfully';
		success_200(true,$msg,$joinee);
	}

   public function delete($id)
   {
   		$newjoinees = NewJoinee::find($id);
        
        if(!$newjoinees){
            $msg = 'Sorry, Details for id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }
        
        $deleted = $newjoinees->delete();
        $msg = 'Candidate has been deleted';
        success_200(true,$msg,'');
   }
}
