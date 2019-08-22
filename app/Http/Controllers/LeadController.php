<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Auth;
use App\Lead;

class LeadController extends Controller
{
	protected $user;

    public function __construct(Request $request){
    	
    	if(!isset($request->token)){
    		return response()->json(['status'	=>	false]);
    	}
    	$this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index(Request $request){
    	$sort = $request->get('sort');
    	$order = $request->get('order');
        $limit = $request->get('limit');
    	$search = $request->get('search');

    	if($sort && $order){
    		$list = Lead::orderBy($sort,$order)->paginate(4);
    		return response()->json($list);
    	}

    	if($search){
    		$query_list = Lead::where('name','LIKE','%'.$search.'%')->orWhere('designation','LIKE','%'.$search.'%')->orWhere('email_id','LIKE','%'.$search.'%')->get();
    		if(count($query_list) > 0){
    			return response()->json($query_list);
    		} else{
    			return response()->json([
    				'success'	=>	false,
    				'message'	=>	'No Details found for the query.'
    			],400);
    		}
    	}
    	$lead_list = Lead::paginate($limit);

    	if(!empty($lead_list) ){
            return response()->json($lead_list);    
        } else {
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Nothing Found'
            ],400);
        }
    }

    public function show($id){
    	$lead_info = Lead::find($id);
    	if($lead_info){
    		return response()->json($lead_info);
    	} else{
    		return response()->json([
    			'success'	=>	false,
    			'message'	=>	'Lead with id '.$id.' cannot be found'
    		],400);
    	}
    }

    public function store(Request $request){
    	$validator = Validator::make($request->all(),[
    		'name'			=>	'required|max:255',
    		'designation'	=>	'required|max:255',
    		'email_id'		=>	'required|max:255|unique:leads'
    	]);

    	if($validator->fails()){
    		return response()->json($validator->errors());
    	}

    	$leads = new Lead();
    	$leads->name = $request->input('name');
    	$leads->designation = $request->input('designation');
    	$leads->email_id = $request->input('email_id');
    	$leads->created_by = Auth::user()->id;
    	$leads->updated_by = Auth::user()->id;

    	$saved = $leads->save();

    	if($saved){
    		return response()->json([
    			'success'	=>	true,
    			'message'	=>	'Lead Information is added successfully'
    		]);
    	} else{
    		return response()->json([
    			'success'	=>	false,
    			'message'	=>	'Failed to save the details'
    		]);
    	}
    }

    public function update(Request $request,$id){

        $lead = Lead::find($id);
        $userid = Auth::user()->id;
        
        if(!$lead){
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Sorry, Lead with id '.$id.' cannot be found'
            ], 400);
        }

        if($request->exists('name')) {
            if($request->has('name')) {
                $lead_name = $request->get('name');
            } else {
                return response()->json([
                    'success'   =>  false,
                    'message'   => 'Name cannot be empty'
                ],400);
            }
        }
        if($request->exists('designation')){
            if($request->has('designation')){
                $lead_designation = $request->get('designation');   
            } else {
                return response()->json([
                    'success'   =>  false,
                    'message'   => 'Designation cannot be empty'
                ],400);
            }
        }
        
        $updated =  $lead->update([
                        'name'          =>  isset($lead_name) ? $lead_name : $lead->name,
                        'designation'   =>  isset($lead_designation) ? $lead_designation : $lead->designation,
                        'updated_by'    =>  $userid
                    ]);

        if($updated){
            return response()->json([
                'success'   =>  true,
                'message'   => 'Lead Information is updated successfully'
            ],200);    
        } else {
            return response()->json([
                'success'   =>  false,
                'message'   => 'Lead Information could not be updated'
            ],400);
        } 
    }

    public function destroy($id){
        $lead = Lead::find($id);
        
        if(!$lead){
            return response()->json([
                'success'   =>  false,
                'message'   => 'Sorry, Lead with id '.$id.' cannot be found'
            ],400);
        }
        
        $deleted = $lead->delete();

        if($deleted){
            return response()->json([
                'success'   =>  true,
                'message'   => 'Lead Information is deleted'
            ],200);
        } else{
            return response()->json([
                'success'   =>  false,
                'message'   => 'Lead Information could not be deleted'
            ],400);
        }
    }
}
