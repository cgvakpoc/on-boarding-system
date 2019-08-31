<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use App\Task;
use Auth;

class TaskController extends Controller
{
    public function __construct(Request $request)
    {
    	if(!isset($request->token)){
    		return response()->json(['status'	=>	false]);
    	}

    	$this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index($id){

    	$task_list = Task::where('candidate_id','=',$id)
    	->select('new_joinees.name as candidate_name','candidate_tasks.task_details','leads.name as lead_name','candidate_tasks.document_path','task_status.status_name')
    	->join('task_status','task_status.id','=','candidate_tasks.task_status')
    	->join('leads','leads.id','=','candidate_tasks.lead_id')
    	->join('new_joinees','new_joinees.id','=','candidate_tasks.candidate_id')
    	->get();

    	if(count($task_list) == 0){
			return response()->json([
				'success'	=>	false,
				'message'	=>	'Sorry, Documents for id '.$id.' cannot be found'
			],404);
		}

    	return response()->json([
    		'success'	=>	true,
    		'details'	=>	$task_list
    	],200);
    }

    public function add(Request $request)
    {
    	$id = $request->id;
		$user_id = Auth::user()->id;

		$validator = Validator::make($request->all(),[
		'task_details'			=>	'required',
		'lead_id'				=>	'required',
		'task_status'			=>	'required',
		'task_upload'			=>	'required',
		'task_upload.*'			=>	'mimes:pdf,docx,doc'
		],[
			'task_upload.*.required'	=>	'Please upload a document',
			'task_upload.*.mimes'		=>	'Only pdf,docx and doc files are allowed'
		]);
		
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$task_upload = $request->file('task_upload');
		$task_path = public_path('/uploads');
		  
		$task_upload = store_files($task_path,$task_upload);
		$task_save = json_encode($task_upload);

		$task = new Task();

		$task->candidate_id = $id;
		$task->task_details = $request->input('task_details');
		$task->lead_id = $request->input('lead_id');
		$task->document_path = $task_save;
		$task->task_status = $request->input('task_status');
		$task->created_by = $user_id;
		$task->updated_by = $user_id;	
		
		$save = $task->save();

		if(!$save){
			return response()->json([
				'success'	=>	false,
				'message'	=>	'Documents could not be uploaded'
			],409);
		}

		return response()->json([
			'success'	=>	true,
			'message'	=>	'Documents uploaded successfully',
			'details'	=>	$task
		],200);
    }

    public function update(Request $request){
    	$id = $request->id;
		$user_id = Auth::user()->id;
    	$candidate_task = Task::where('candidate_id',$id)->first();

		if(count($candidate_task) > 0 ){
			$validator = Validator::make($request->all(),[
				'task_details'	=>	'required',
				'lead_id'		=>	'required',
				'task_status'	=>	'required',
				'task_upload'	=>	'required',
				'task_upload.*'	=>	'mimes:pdf,docx,doc'
			],[
				'task_upload.*.required'	=>	'Please upload a document',
				'task_upload.*.mimes'		=>	'Only pdf,docx and doc files are allowed'
			]);

			if($validator->fails()){
				return response()->json($validator->errors());
			}

			$task_upload = $request->file('task_upload');
			$doc_path = public_path('/uploads');
			  
			$task_upload = store_files($doc_path,$task_upload);
			$task_update = json_encode($task_upload);

			$update_tasks = $candidate_task->update([
				'task_details'		=>	$request->input('task_details'),
				'lead_id'			=>	$request->input('lead_id'),
				'task_status'		=>	$request->input('task_status'),
				'document_path'		=>	$task_update,
				'updated_by'		=>	$user_id
			]);

			if(!$update_tasks){
				return response()->json([
					'success'	=>	false,
					'message'	=>	'Documents cannot be updated'
				],409);
			}

			return response()->json([
				'success'	=>	true,
				'message'	=>	'Documents has been updated successfully',
				'details'	=>	$candidate_task
			],200);
		}
    }

    public function delete($id)
    {
		$candidate_tasks = Task::where('candidate_id',$id)->first();
        
        if(!$candidate_tasks){
            return response()->json([
                'success'   =>  false,
                'message'   => 'Sorry, Task details for id '.$id.' cannot be found'
            ],404);
        }
        
        $deleted = $candidate_tasks->delete();

        return response()->json([
            'success'   =>  true,
            'message'   => 'Task Information has been deleted'
        ],200);
	}
}
