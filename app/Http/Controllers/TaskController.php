<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use App\Candidate\Candidate;
use App\Task;
use Auth;
use DB;

class TaskController extends Controller
{
    public function __construct(Request $request)
    {
    	// if(!isset($request->token)){
    	// 	return response()->json(['status'	=>	false]);
    	// }

    	// $this->user = JWTAuth::parseToken()->authenticate();
    }

    protected $validationRules = [
    	'task_details'	=>	'required',
    	'lead_id'		=>	'required',
    	'task_status'	=>	'required',
    	'task_upload'	=>	'required',
    	'task_upload.*'	=>	'mimes:pdf,docx,doc'
    ];

    protected $customMessage = [
    	'task_upload.*.required'	=>	'Please upload a document',
    	'task_upload.*.mimes'		=>	'Only pdf,docx and doc files are allowed'
    ];

    public function index($id)
    {
    	$candidate = Candidate::find($id);

    	if(count($candidate) === 0){
    		$msg = 'Sorry, Assessment details for the id '.$id.' cannot be found';
    		error_404(false,$msg);
    		die;
    	}
    	$tasks = DB::table('candidate_tasks as t1')
    			 ->select('t1.candidate_id','t1.task_details','t3.name as lead','t1.document_path','t4.status_name as status')
    			 ->join('candidates as t2','t1.candidate_id','t2.id')
    			 ->join('leads as t3','t1.lead_id','t3.id')
    			 ->join('task_status as t4','t1.task_status','t4.id')
    			 ->where('t2.id',$id)
    			 ->get();
    	//$tasks[0]->document_path = unserialize($tasks[0]->document_path);
    	success_200(true,$tasks);
    }

    public function add(Request $request)
    {
    	$id = $request->id;
    	$task_list = Task::where('candidate_id',$id)->first();
    	if( count($task_list) > 0 ){
    		$msg = 'Data already exists';
    		bad_request(false,$msg);
    		die;
    	}

		$validator = Validator::make($request->all(),$this->validationRules,$this->customMessage);
		
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$task_upload = $request->file('task_upload');
		$task_path = public_path('/uploads');
		  
		$task_upload = store_files($task_path,$task_upload);
		$task_doc = $task_upload;

		//$task = new Task();
		foreach($task_doc as $doc){
			$task_add[] = array(
				'candidate_id'	=>	$id,
				'task_details'	=>	$request->task_details,
				'lead_id'		=>	$request->lead_id,
				'document_path'	=>	$doc,
				'task_status'	=>	$request->task_status
			);
		}
		$save = Task::insert($task_add);
		if($save === 0){
			$msg = 'Task details cannot be added';
			bad_request(false,$msg);
			die;
		}
		$task = Task::where('candidate_id',$id)->get();
		$msg = 'Task details added successfully';
		success_200(true,$task,$msg);
    }

    public function update(Request $request)
    {
    	$id = $request->id;
    	$candidate_task = Task::where('candidate_id',$id);
		if(count($candidate_task) === 0 ){
			$msg = 'Sorry, candidate with id '.$id.' not found';
			error_404(false,$msg);
			die;
		}
		$validator = Validator::make($request->all(),$this->validationRules,$this->customMessage);
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$task_upload = $request->file('task_upload');
		$doc_path = public_path('/uploads');
		  
		$task_upload = store_files($doc_path,$task_upload);
		$task_update = serialize($task_upload);

		$update_tasks = $candidate_task->update([
			'task_details'		=>	$request->task_details,
			'lead_id'			=>	$request->lead_id,
			'document_path'		=>	$task_update,
			'task_status'		=>	$request->task_status,
		]);

		if(!$update_tasks){
			$msg = 'Task details cannot be updated';
			bad_request(false,$msg);
			die;
		}
		$msg = 'Task details has been updated';
		success_200(true,$candidate_task,$msg);
    }
}
