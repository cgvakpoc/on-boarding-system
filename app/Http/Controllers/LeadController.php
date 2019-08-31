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
    		return response()->json(['success'	=>	false]);
    	}
    	$this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index(Request $request){
    	$sort = $request->get('sort');
    	$order = $request->get('order');
        $limit = $request->get('limit');
    	$search = $request->get('search');

    	$leads_list = Lead::query();

        $lead_records = $leads_list->get();

        if($search){
            $leads_list = $leads_list->where('name','LIKE','%'.$search.'%')
                          ->where('designation','LIKE','%'.$search.'%')
                          ->orWhere('email_id','LIKE','%'.$search.'%');

            $search_list = $leads_list->get();

            if(count($search_list) == 0){
                return response()->json([
                    'success'   =>  false,
                    'message'   =>  'No search results found for the query '.$search
                ],400);    
            }
        }
        if($sort && $order){
            $list = $leads_list->orderBy($sort,$order)->paginate($limit); 
        } else if(count($lead_records) == 0){
            return response()->json([
                'success'   =>  false,
                'message'   =>  'No Records Found'
            ], 400);
        } else{
            $list = $leads_list->paginate($limit);
        }
        
        return response()->json($list);
    }

    public function show($id){
    	
        $lead_info = Lead::find($id);
    	
        if(!$lead_info){
    	   return response()->json([
                'success'   =>  false,
                'message'   =>  'Lead with id '.$id.' cannot be found'
            ],400);	
    	}
        return response()->json($lead_info);
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

    	if(!$saved){
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Failed to save the details'
            ],400);	
    	} 

        return response()->json([
                'success'   =>  true,
                'message'   =>  'Lead Information is added successfully',
                'data'      =>  $leads
            ]);
    }

    public function update(Request $request,$id){

        $lead = Lead::find($id);
        
        if(!$lead){
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Sorry, Lead with id '.$id.' cannot be found'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'name'          =>  'required|max:255',
            'designation'   =>  'required|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $lead_name = $request->get('name');
        $lead_designation = $request->get('designation');
        $userid = Auth::user()->id;
        
        $updated =  $lead->update([
                        'name'          =>  $lead_name,
                        'designation'   =>  $lead_designation,
                        'updated_by'    =>  $userid
                    ]);

        if(!$updated){
            return response()->json([
                'success'   =>  false,
                'message'   => 'Lead Information could not be updated'
            ],400);    
        }
        return response()->json([
            'success'   =>  true,
            'message'   => 'Lead Information is updated successfully'
        ],200); 
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

        if(!$deleted){
            return response()->json([
                'success'   =>  false,
                'message'   => 'Lead Information could not be deleted'
            ],400);
        }
        return response()->json([
            'success'   =>  true,
            'message'   => 'Lead Information is deleted'
        ],200);
    }
}
