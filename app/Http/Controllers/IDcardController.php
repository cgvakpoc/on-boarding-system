<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWT;
use Validator;
use Auth;
use App\IDCard;

class IDcardController extends Controller
{
    public function __construct(Request $Request)
    {
    	if(!isset($request->token)){
    		return response()->json(['success'	=>	false]);
    	}

    	$this->user = JWTAuth::parseToken()->authenticate();
    }
    protected $validationRules = [
        'candidate_name'    =>  'required|string|min:3|max:255',
        'emp_code'          =>  'required|unique:id_card,emp_code',
        'candidate_addr'    =>  'required',
        'blood_group'       =>  'required|string',
        'file_upload'       =>  'required',
        'file_upload.*'     =>  'mimes:jpeg,jpg,png'
    ];

    protected $customMessage = [
        'emp_code.unique'           =>  'Employee code has already been taken',
        'file_upload.*.required'    =>  'Please upload candidate photo and signature',
        'file_upload.*.mimes'       =>  'Only jpg, png files are allowed'
    ];

    public function index($id)
    {
    	$candid_id = IDCard::where('candidate_id',$id)->first();
    	if(count($candid_id) === 0){
            $error_msg = 'Sorry, Information for id '.$id.' cannot be found';
            error_404(false,$error_msg);
            die;    
    	}
        success_200(true,'',$candid_id);
    }

    public function add(Request $request)
    {
    	$userid = Auth::user()->id;
        $emp_id = $request->id;

    	$validator = Validator::make($request->all(),$this->validationRules,$this->customMessage);
     	if($validator->fails()){
     		return response()->json($validator->errors());
     	}

     	$doc_upload = $request->file('file_upload');
		$doc_path = public_path('/uploads');
		$doc_upload = store_files($doc_path,$doc_upload);

     	$idcard = new IDCard();
     	$idcard->emp_code = $request->emp_code;
     	$idcard->candidate_id = $emp_id;
     	$idcard->name = $request->candidate_name;
     	$idcard->address = $request->candidate_addr;
     	$idcard->blood_group = $request->blood_group;
     	$idcard->document_path = $doc_upload;
     	$idcard->created_by = $userid;
     	$idcard->updated_by = $userid;
     	$idcard->save();

        $success_msg = 'The ID card details are saved successfully';
        success_200(true,$success_msg,$idcard);
    }
    
    public function update(Request $request)
    {
    	$id = $request->id;
    	$card_data = IDCard::where('candidate_id',$id)->first();

    	if(count($card_data) == 0){
            $error_msg = 'Sorry, Information for id '.$id.' cannot be found';
    		error_404(false,$error_msg);
            die;
    	}
        
        $this->validationRules['emp_code'] = 'required|numeric|unique:id_card,emp_code,'.$request->id.',user_id';
    	$validator = Validator::make($request->all(),$this->validationRules,$this->customMessage);

     	if($validator->fails()){
     		return response()->json($validator->errors());
     	}

     	$doc_upload = $request->file('file_upload');
		$doc_path = public_path('/uploads');
		$doc_upload = store_files($doc_path,$doc_upload);

		$update_card = $card_data->update([
			'emp_code'		=>	$request->emp_code,
			'candidate_id'	=>	$id,
			'name'			=>	$request->candidate_name,
			'address'		=>	$request->candidate_addr,
			'blood_group'	=>	$request->blood_group,
			'document_path'	=>	$doc_upload,
			'updated_by'	=>	Auth::user()->id
		]);

        if(!$update_card){
            $msg = "Failed to update the records";
            bad_request(false,$msg);
            die;
        }
        $update_msg = 'ID Card details has been updated successfully';
		success_200(true,$update_msg,$card_data);
    }
}
