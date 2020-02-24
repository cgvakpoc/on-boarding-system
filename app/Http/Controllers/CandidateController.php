<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Auth;
use App\Candidate\Candidate;
use App\Candidate\CandidateDocument;
use App\Candidate\CandidateDoc;
use App\Lead;

class CandidateController extends Controller
{
	protected $user;

	public function __construct(Request $request)
	{
		// if(!isset($request->token)){
		//   return response()->json(['success'	=>	false]);
		// }
		// $this->user = JWTAuth::parseToken()->authenticate();
	}

	protected $validationRules = [
		'name'					=>      'required|string|min:3|max:255',
		'department_id'				=>	'required',
		'designation_id'			=>	'required',
		'doj'						=>	'required',
		'dob'						=>	'required',
		'father_name'				=>	'required|string|max:255',
		'email_id'					=>	'required|string|email|unique:candidates,email',
		'cold_call_status'			=>	'required|array',
		'commitment_status'			=>	'required|string',
		'recruiter_name'			=>	'required|string|max:255',
		'requirement_detail'		=>	'required|string|max:255',
		'source_of_hire'			=>	'required|string|max:255',
		'location'					=>	'required|string|max:255',
		'accomodation'				=>	'required|string|max:255',
		'requirement_lead_id'		=>	'required|numeric',
		'consultant_lead_id'		=>	'required|numeric',
		'technical_lead_id'			=>	'required|numeric',
		'candidate_accomodation'	=>  'required|boolean',	
		'assigned_consultant_work'	=>  'required|boolean',
		'contact_number'			=>  'required|string',
		'is_tech_required'			=>  'required|boolean'
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

		 // $candidate_list = Candidate::get();
		 
		 // echo "<pre>";
		 // print_r($candidate_list);
		 // die;

		$candidate_list = DB::table('candidates')
						->join('designations', 'candidates.designation_id' , '=', 'designations.id')
						->join('departments', 'candidates.department_id','=','departments.id')
						->orderBy('candidates.id','ASC')
						->get(['candidates.*','designations.designation_name','departments.department']);


		//$candidate_list = Candidate::

		 // echo "<pre>";
		 // print_r($candidate_list);
		 // die;

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
			$list = $candidate_list;    
		}
		
		success_200(true,$list);
	}

	public function showCandidate($id)
	{
		
		$candidate = DB::table('candidates')
					->where('candidates.id', $id)
					->join('designations', 'candidates.designation_id' , '=', 'designations.id')
					->join('departments', 'candidates.department_id','=','departments.id')
					->orderBy('candidates.id','ASC')
					->get(['candidates.*','designations.designation_name','departments.department']);

						// echo "<pre>";
						// print_r($candidate_list);
						// die;
		
		// $candidate = Candidate::find($id);
		$data = DB::table('cold_calling_status')
					->select('id','date as status_date', 'name as status')
					->where(['candidate_id' => $id])->get();
		
		if(isset($candidate[0])){
			$candidate[0]->cold_calling_status = $data; // Add Cold Call status
			$lead_name = Lead::select('name')->find($candidate[0]->requirement_lead_id)['name'];
			$lead_name = ($lead_name != null) ? $lead_name : '';
			$candidate[0]->lead_name = $lead_name;
			$candidate[0]->is_tech_required = ($candidate[0]->is_tech_required == "1") ? true : false;
			$candidate[0]->candidate_accomodation = ($candidate[0]->candidate_accomodation == "1") ? true: false;
			$candidate[0]->assigned_consultant_work = ($candidate[0]->assigned_consultant_work == "1") ? true: false;
			$lead_name = Lead::select('name')->find($candidate[0]->consultant_lead_id)['name'];
			$lead_name = ($lead_name != null) ? $lead_name : '';
			$candidate[0]->consultant_lead_name = $lead_name;
			$lead_name = Lead::select('name')->find($candidate[0]->technical_lead_id)['name'];
			$lead_name = ($lead_name != null) ? $lead_name : '';
			$candidate[0]->technical_lead_name = $lead_name;
		}

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
		$new_candidate->cold_calling_status = '';
		$new_candidate->commitment_status = $request->commitment_status;
		$new_candidate->joining_bonus = $request->joining_bonus;
		$new_candidate->recruiter_name = $request->recruiter_name;
		$new_candidate->requirement_details = $request->requirement_detail;
		$new_candidate->source_of_hiring = $request->source_of_hire;
		$new_candidate->location = $request->location;
		$new_candidate->travel_accomodation = $request->accomodation;
		$new_candidate->created_by = $userid;
		$new_candidate->updated_by = $userid;
		$new_candidate->requirement_lead_id = $request->requirement_lead_id;
		$new_candidate->consultant_lead_id = $request->consultant_lead_id;
		$new_candidate->technical_lead_id = $request->technical_lead_id;
		$new_candidate->candidate_accomodation = $request->candidate_accomodation;
		$new_candidate->assigned_consultant_work = $request->assigned_consultant_work;
		$new_candidate->contact_number = $request->contact_number;
		$new_candidate->is_tech_required = $request->is_tech_required;
		$candidate_saved = $new_candidate->save();

		// Get the list of cold_call_status and insert
		foreach($request->cold_call_status as $value){
			$data = array(
				"candidate_id" => $new_candidate->id, // get the last canditate id created to map
				"date" => convert_date($value['status_date']),
				"name" => $value['status'],
				"created_at" => date('Y-m-d H:i:s'),
				"updated_at" => date('Y-m-d H:i:s')
			); // create an array of data and insert into the table
			DB::table('cold_calling_status')->insert($data); // insert the newly created array in table
		}

		$msg = 'Candidate details has been added successfully';
		success_200(true,$new_candidate,$msg);
	}

	public function updateCandidate(Request $request)
	{
		$userid = Auth::user()->id;
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
			'name'						=>	$request->name,
			'department_id'				=>	$request->department_id,
			'designation_id'			=>	$request->designation_id,
			'date_of_birth'				=>	$dob,
			'date_of_join'				=>	$doj,
			'father_name'				=>	$request->father_name,
			'commitment_status'			=>	$request->commitment_status,
			'joining_bonus'				=>	$request->joining_bonus,
			'recruiter_name'			=>	$request->recruiter_name,
			'requirement_details'		=>	$request->requirement_detail,
			'source_of_hiring'			=>	$request->source_of_hire,
			'location'					=>	$request->location,
			'requirement_lead_id' 		=>  $request->requirement_lead_id,
			'consultant_lead_id' 		=>  $request->consultant_lead_id,
			'technical_lead_id' 		=>  $request->technical_lead_id,
			'candidate_accomodation'	=>  $request->candidate_accomodation,
			'assigned_consultant_work' 	=>  $request->assigned_consultant_work,
			'travel_accomodation'		=>	$request->accomodation,
			'updated_by'				=>	$userid
		]);

		// Get the list of cold_call_status and update
		foreach($request->cold_call_status as $value){
			$data = array(
				"date" => convert_date($value['status_date']),
				"name" => $value['status'],
				"updated_at" => date('Y-m-d H:i:s')
			); // create an array of data and update into the table
			DB::table('cold_calling_status')->where(['id' => $value])->update($data); // update the newly created array in table
		}

		$candidate = DB::table('candidates')
					->where('candidates.id', $id)
					->join('designations', 'candidates.designation_id' , '=', 'designations.id')
					->join('departments', 'candidates.department_id','=','departments.id')
					->orderBy('candidates.id','ASC')
					->get(['candidates.*','designations.designation_name','departments.department']);
		$data = DB::table('cold_calling_status')
					->select('id','date as status_date', 'name as status')
					->where(['candidate_id' => $id])->get();
		if(isset($candidate[0])){
			$candidate[0]->cold_calling_status = $data; // Add Cold Call status
			$lead_name = Lead::select('name')->find($candidate[0]->requirement_lead_id)['name'];
			$lead_name = ($lead_name != null) ? $lead_name : ''; 
			$candidate[0]->lead_name = $lead_name;
			$candidate[0]->is_tech_required = ($candidate[0]->is_tech_required == "1") ? true : false;
			$candidate[0]->candidate_accomodation = ($candidate[0]->candidate_accomodation == "1") ? true: false;
			$candidate[0]->assigned_consultant_work = ($candidate[0]->assigned_consultant_work == "1") ? true: false;
			$lead_name = Lead::select('name')->find($candidate[0]->consultant_lead_id)['name'];
			$lead_name = ($lead_name != null) ? $lead_name : '';
			$candidate[0]->consultant_lead_name = $lead_name;
			$lead_name = Lead::select('name')->find($candidate[0]->technical_lead_id)['name'];
			$lead_name = ($lead_name != null) ? $lead_name : '';
			$candidate[0]->technical_lead_name = $lead_name;
			$candidate = $candidate[0];
		}

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


	//Upload Candidate Docs

	public function add_document($request){
		$id = $request->id;
		// echo $id;
		// die;
		$doc_upload = $request->file('document_upload');
		$doc_path = public_path('/uploads');
		error_reporting(1);
		$doc_upload = store_files($doc_path,$doc_upload);
		// echo "$doc_upload";
		// die;
		$doc_save = $doc_upload;

		foreach($doc_save as $key){
			CandidateDoc::Create([
				'candidate_id'	=> $id,
				'document_path'	=>	$key
			]);
		}
	}

	public function add_title($request){
		$id = $request->id;
		CandidateDocument::Create([
			'candidate_id'		=> $id,
			'document_title'	=>	$request->document_title
		]);
	}

	public function update_title($request){
		$id = $request->id;
		CandidateDocument::where('candidate_id',$id)->delete();
		CandidateDocument::Create([
			'candidate_id'		=>	$id,
			'document_title'	=>	$request->document_title
		]);
	}

	public function update_document($request){
		$id = $request->id;
		CandidateDoc::where('candidate_id',$id)->delete();
		$doc_upload = $request->file('document_upload');
		$doc_path = public_path('/uploads');
		$doc_upload = store_files($doc_path,$doc_upload);
		$doc_save = $doc_upload;
		foreach($doc_save as $key){
			CandidateDoc::Create([
				'candidate_id'	=> $id,
				'document_path'	=>	$key
			]);
		}
	}

	public function index(Request $request,$msg='')
	{	
		$id = $request->id;
		$doc_list = Candidate::find($id);
		if(count((array)$doc_list) === 0){
			$error_msg = 'Sorry, Documents for id '.$id.' cannot be found';
			error_404(false,$error_msg);
			die;
		}
		$documents = DB::table('candidate_documents as c1')
		               ->select('c1.document_title')
		               ->join('candidates as c2','c1.candidate_id','c2.id')
		               ->where('c1.candidate_id',$id)
		               ->get();
		$document_list = DB::table('candidate_document_details as c1')
		              ->select('c1.document_path')
		              ->join('candidates as c2','c2.id','c1.candidate_id')
		              ->where('c1.candidate_id',$id)
		              ->get();
		$list = array();
		$list['title'] = $documents;
		$list['document_paths'] = $document_list;
		success_200(true,$list,$msg);
	}

	public function add(Request $request)// Add Candidate Documents
	{
		// $candidate_docs = CandidateDocument::where('candidate_id',$request->id)->first();
		// if(count((array)$candidate_docs) > 0){
		// 	$msg = "Data already exists";
		// 	bad_request(false,$msg);
		// 	die;
		// }
		$id = $request->id;
		$validator = Validator::make($request->all(),$this->documentRules,$this->customMessage);
		if($validator->fails()){
			return response()->json($validator->errors());
		}
		
		DB::beginTransaction();
		try{
			$this->add_title($request);
			$this->add_document($request);
		}
		catch(\Exception $e){
			DB::rollback();
            error_404(false,$e);
            die;
		}
		DB::commit();
		$msg = 'Documents uploaded successfully';
		$docs = $this->index($request,$msg);
	}

	public function update(Request $request)//Update Candidate Documents
	{
		$id = $request->id;
		$candidate_docs = CandidateDocument::where('candidate_id',$id)->first();
		if(count((array)$candidate_docs) === 0){
			$msg = 'Sorry, Documents for id '.$id.' cannot be found';
			error_404(false,$msg);
			die;
		}
		$validator = Validator::make($request->all(),$this->documentRules,$this->customMessage);
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		DB::beginTransaction();
		try{
			$this->update_title($request);
			$this->update_document($request);
		}
		catch(\Exception $e){
			DB::rollback();
			error_404(false,$e);
		}
		DB::commit();
		$msg = 'Documents has been updated successfully';
		$this->index($request,$msg);
	}
}
