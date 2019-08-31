<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Auth;
use App\Candidate;


class CandidateController extends Controller
{
	protected $user;

	public function __construct(Request $request){
	if(!isset($request->token)){
	  return response()->json(['success'	=>	false]);
	}
	
	$this->user = JWTAuth::parseToken()->authenticate();
	}

	protected $validationRules = [
		'document_title'		=>	'required',
		'document_upload'		=>	'required',
		'document_upload.*'		=>	'mimes:pdf,docx,doc'
	];

	protected $customMessage = [
		'document_upload.*.required'	=>	'Please upload a document',
		'document_upload.*.mimes'		=>	'Only pdf,docx and doc files are allowed'
	];

	public function index(Request $request){
		$id = $request->id;

		$doc_list = Candidate::where('candidate_id','=',$id)
    	->select('new_joinees.name as candidate_name','candidate_documents.document_title','candidate_documents.document_path as document path')
    	->join('new_joinees','new_joinees.id','=','candidate_documents.candidate_id')
    	->get();

		if(count($doc_list) == 0){
			$error_msg = 'Sorry, Documents for id '.$id.' cannot be found';
			error_404(false,$error_msg);
		}

		success_200(true,'',$doc_list);
	}

	public function add(Request $request)
	{
		$id = $request->id;
		$user_id = Auth::user()->id;

		$validator = Validator::make($request->all(),$this->validationRules,$this->customMessage);
		if($validator->fails()){
			return response()->json($validator->errors());
		}

		$doc_upload = $request->file('document_upload');
		$doc_path = public_path('/uploads');
		  
		$doc_upload = store_files($doc_path,$doc_upload);
		$doc_save = json_encode($doc_upload);

		$docs = new Candidate();
		$docs->candidate_id = $id;
		$docs->document_title = $request->document_title;
		$docs->document_path = $doc_save;
		$docs->created_by = $user_id;
		$docs->updated_by = $user_id;	
		$save = $docs->save();

		$msg = 'Documents uploaded successfully';
		success_200(true,$msg,$docs);
	}

	public function update(Request $request){
		$id = $request->id;
		$user_id = Auth::user()->id;
		$candidate_docs = Candidate::where('candidate_id',$id)->first();

		if(count($candidate_docs) > 0 )
		{
			$validator = Validator::make($request->all(),$this->validationRules,$this->customMessage);
			if($validator->fails()){
				return response()->json($validator->errors());
			}

			$doc_upload = $request->file('document_upload');
			$doc_path = public_path('/uploads');
			  
			$doc_upload = store_files($doc_path,$doc_upload);
			$doc_update = json_encode($doc_upload);

			$update_docs = $candidate_docs->update([
				'document_title'	=>	$request->document_title,
				'document_path'		=>	$doc_update,
				'updated_by'		=>	$user_id
			]);

			$msg = 'Documents has been updated successfully';
			success_200(true,$msg,$candidate_docs);
		}
	}
	
	public function delete($id){
		$candidate_docs = Candidate::where('candidate_id',$id)->first();
        if(!$candidate_docs){
        	$msg = 'Sorry, Documents for id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }
        
        $deleted = $candidate_docs->delete();
        $msg = 'Document Information has been deleted';
        success_200(true,$msg,'');
	}
}
