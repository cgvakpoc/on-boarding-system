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
    	
    	// if(!isset($request->token)){
    	// 	return response()->json(['success'	=>	false]);
    	// }
    	// $this->user = JWTAuth::parseToken()->authenticate();
    }

    protected $validationRules = [
        'name'          =>  'required|max:255',
        'designation'   =>  'required|max:255',
        'email_id'      =>  'required|max:255|unique:leads,email_id'
    ];

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

    public function show($id)
    {	
        $lead_info = Lead::find($id);
        if(!$lead_info){
           $msg = 'Lead with id '.$id.' cannot be found';
    	   bad_request(false,$msg);
           die;
    	}
        success_200(true,'',$lead_info);
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
    	$validator = Validator::make($request->all(),$this->validationRules);
    	if($validator->fails()){
    		return response()->json($validator->errors());
    	}

    	$leads = new Lead();
    	$leads->name = $request->name;
    	$leads->designation = $request->designation;
    	$leads->email_id = $request->email_id;
    	$leads->created_by = $user_id;
    	$leads->updated_by = $user_id;

    	$saved = $leads->save();
    	if(!$saved){
            $msg = "Details cannot be saved";
            bad_request(false,$msg);
            die;
    	} 
        $msg = 'Lead Information is saved successfully';
        success_200(true,$leads,$msg);
    }

    public function update(Request $request,$id)
    {
        $lead = Lead::find($id);   
        if(!$lead){
            $msg = 'Sorry, Lead with id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }
        $validator = Validator::make($request->all(),$this->validationRules);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $lead_name = $request->name;
        $lead_designation = $request->designation;
        $userid = Auth::user()->id;
        
        $updated =  $lead->update([
                        'name'          =>  $lead_name,
                        'designation'   =>  $lead_designation,
                        'updated_by'    =>  $userid
                    ]);

        if(!$updated){
            $msg = 'Lead Information could not be updated';
            bad_request(false,$msg);
            die;    
        }
        $msg = 'Lead Information is updated successfully';
        success_200(true,$msg,''); 
    }

    public function destroy($id){
        $lead = Lead::find($id);
        
        if(!$lead){
            $msg = 'Sorry, Lead with id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }
        
        $deleted = $lead->delete();

        if(!$deleted){
            $msg = 'Lead Information could not be deleted';
            bad_request(false,$msg);
            die;
        }
        $msg = 'Lead Information is deleted';
        success_200(true,$msg,'');
    }
}
