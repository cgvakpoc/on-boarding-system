<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Auth;
use App\Candidate\Candidate;
use App\Candidate\CandidateDocument;

class CandidateController extends Controller
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
		'email_id'				=>	'required|string|email|unique:candidates,email',
		'cold_call_status'		=>	'required|string',
		'commitment_status'		=>	'required|string',
		'recruiter_name'		=>	'required|string|max:255',
		'requirement_detail'	=>	'required|string|max:255',
		'source_of_hire'		=>	'required|string|max:255',
		'location'				=>	'required|string|max:255',
		'accomodation'			=>	'required|string|max:255'
	];

	protected $documentRules = [
		'document_title'		=>	'required',
		'document_upload'		=>	'required',
		'document_upload.*'		=>	'mimes:pdf,docx,doc'
	];

	protected $customMessage = [
		'document_upload.*.required'	=>	'Please upload a document',
		'document_upload.*.mimes'		=>	'Only pdf,docx and doc files are allowed'
	];

	public function listCandidates(Request $request)
	{
		$sort = $request->sort;
		$order = $request->order;
		$search = $request->search;
		$limit = $request->limit;

		$candidate_list = Candidate::query();

		if($search){
			$candidate_list = $candidate_list->where('name','LIKE','%'.$search.'%')
							  ->orWhere('email','LIKE','%'.$search.'%')
                              ->orWhere('recruiter_name','LIKE','%'.$search.'%')
                              ->orWhere('source_of_hiring','LIKE','%'.$search.'%');
			$search_list = $candidate_list->get();
			if(count($search_list) === 0){
				$msg = 'No search results found for the query '.$search;
				error_404(false,$msg);
				die;
			}
		}
		if($sort && $order){
			$list = $candidate_list->orderBy($sort,$order)->paginate($limit); 
		} else {
			$list = $candidate_list->orderBy('id','ASC')->paginate($limit);    
		}
		success_200(true,$list);
	}

	public function showCandidate($id)
	{
		$candidate = Candidate::find($id);
        if(!$candidate){
            $msg = 'Candidate with id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }
        success_200(true,$candidate);
	}

	public function addCandidate(Request $request)
	{
		$userid = Auth::user()->id;
		$validator = Validator::make($request->all(),$this->validationRules);

		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$doj = convert_date($request->doj);
		$dob = convert_date($request->dob);

		$new_candidate = new Candidate();

		$new_candidate->name 			= $request->name;
		$new_candidate->department_id 	= $request->department_id;
		$new_candidate->designation_id = $request->designation_id;
		$new_candidate->date_of_birth  = $dob;
		$new_candidate->date_of_join 	= $doj;
		$new_candidate->father_name 	= $request->father_name;
		$new_candidate->email 			= $request->email_id;
		$new_candidate->cold_calling_status = $request->cold_call_status;
		$new_candidate->commitment_status = $request->commitment_status;
		$new_candidate->joining_bonus = $request->joining_bonus;
		$new_candidate->recruiter_name = $request->recruiter_name;
		$new_candidate->requirement_details = $request->requirement_detail;
		$new_candidate->source_of_hiring = $request->source_of_hire;
		$new_candidate->location = $request->location;
		$new_candidate->travel_accomodation = $request->accomodation;
		$new_candidate->created_by = $userid;
		$new_candidate->updated_by = $userid;

		$candidate_saved = $new_candidate->save();

		$msg = 'Candidate details has been added successfully';
		success_200(true,$new_candidate,$msg);
	}

	public function updateCandidate(Request $request)
	{
		$id = $request->id;
		$candidate = Candidate::find($id);

		if(!$candidate){
			$msg = 'Candidate with id '.$id.' cannot be found';
			error_404(false,$msg);
			die;
        }

        $this->validationRules['email_id'] = 'required|string|email|unique:candidates,email,'.$id.',id';
		$validator = Validator::make($request->all(),$this->validationRules);
		
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$doj = convert_date($request->doj);
      	$dob = convert_date($request->dob);

		$update_candidate = $candidate->update([
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

		if(!$update_candidate){
			$msg = "Candidate cannot be updated";
			bad_request(false,$msg);
			die;
		}

		$msg = 'Candidate details updated successfully';
		success_200(true,$candidate,$msg);
	}

	public function deleteCandidate($id)
	{
		$candidate = Candidate::find($id);
        if(!$candidate){
            $msg = 'Sorry, Candidate with id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }
        $deleted = $candidate->delete();
        if(!$deleted){
        	$msg = 'Cannot process the request';
        	bad_request(false,$msg);
        	die;
        }
        $msg = 'Candidate has been deleted';
        success_200(true,'',$msg);
	}

	public function index(Request $request)
	{
		$id = $request->id;
		$doc_list = Candidate::find($id);
		if(count($doc_list) === 0){
			$error_msg = 'Sorry, Documents for id '.$id.' cannot be found';
			error_404(false,$error_msg);
			die;
		}
		$list = $doc_list->can_docs()->get();
		$list[0]->document_path = unserialize($list[0]->document_path);
		success_200(true,$list);
	}

	public function add(Request $request)
	{
		$candidate_docs = CandidateDocument::where('candidate_id',$request->id)->first();
		if(count($candidate_docs) > 0){
			$msg = "Data already exists";
			bad_request(false,$msg);
			die;
		}
		$id = $request->id;
		$user_id = Auth::user()->id;
		$validator = Validator::make($request->all(),$this->documentRules,$this->customMessage);
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$doc_upload = $request->file('document_upload');
		$doc_path = public_path('/uploads');
		$doc_upload = store_files($doc_path,$doc_upload);
		$doc_save = serialize($doc_upload);

		$docs = new CandidateDocument();
		$docs->candidate_id = $id;
		$docs->document_title = $request->document_title;
		$docs->document_path = $doc_save;
		$docs->created_by = $user_id;
		$docs->updated_by = $user_id;	
		$save = $docs->save();

		$docs['document_path'] = unserialize($docs['document_path']);
		$msg = 'Documents uploaded successfully';
		success_200(true,$docs,$msg);
	}

	public function update(Request $request)
	{
		$id = $request->id;
		$user_id = Auth::user()->id;
		$candidate_docs = CandidateDocument::where('candidate_id',$id)->first();
		if(count($candidate_docs) === 0){
			$msg = 'Sorry, Documents for id '.$id.' cannot be found';
			error_404(false,$msg);
			die;
		}
		$validator = Validator::make($request->all(),$this->documentRules,$this->customMessage);
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$doc_upload = $request->file('document_upload');
		$doc_path = public_path('/uploads'); 
		$doc_upload = store_files($doc_path,$doc_upload);
		$doc_update = serialize($doc_upload);

		$update_docs = $candidate_docs->update([
			'document_title'	=>	$request->document_title,
			'document_path'		=>	$doc_update,
			'updated_by'		=>	$user_id
		]);
		if(!$update_docs){
			$msg = "Failed to update the documents";
			bad_request(false,$msg);
			die;
		}
		$candidate_docs['document_path'] = unserialize($candidate_docs['document_path']);
		$msg = 'Documents has been updated successfully';
		success_200(true,$candidate_docs,$msg);
	}
}
