<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Auth;
use App\Department;

class DepartmentController extends Controller
{
    protected $user;
 
    public function __construct(Request $request)
    {
        if(!isset($request->token)){
            return response()->json(['status' => false]);
        }
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index(Request $request){
        $sort_column = $request->input('sort_column');
        $sort_order = $request->input('sort_order');
        $searchtxt = $request->input('search_txt');
        
        if($sort_column && $sort_order){
            $sort_list = Department::orderBy($sort_column,$sort_order)->paginate(4); 
            return response()->json($sort_list);   
        }

        if($searchtxt){
            $search_list = Department::where('department','LIKE','%'.$searchtxt.'%')->orWhere('description','LIKE','%'.$searchtxt.'%')->get();
            if(count($search_list) > 0){
                return response()->json($search_list);
            } else{
                return response()->json([
                    'success'   =>  false,
                    'message'   =>  'No Details found for the query.'
                ],500);
            }    
        }
            
        $departmentlist = Department::paginate(4);
        
        if(!empty($departmentlist) ){
            return response()->json($departmentlist);    
        } else {
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Nothing Found'
            ],400);
        }
    }

    public function show($id){
        $department_list = Department::find($id);
        
        if(!$department_list){
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Department with id '.$id.' cannot be found'
            ]);
        }

        return response()->json($department_list);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'department' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $departments = new Department();
        $departments->department = $request->input('department');
        $departments->description = $request->input('description');
        $departments->created_by = Auth::user()->id;
        $departments->updated_by = Auth::user()->id;
        $departments->save();
        return response()->json([
            'success'   =>  true,
            'message'   => 'Department is created successfully'
        ],200);
    }

    public function update(Request $request,$id){

        $department = Department::find($id);
        
        if(!$department){
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Sorry, Department with id '.$id.' cannot be found'
            ], 400);
        }
        
        $dept_name = $request->input('department_name');
        $dept_desc = $request->input('description');
        $userid = Auth::user()->id;
        
        if($dept_name == '' || $dept_desc == ''){
            return response()->json([
                'success'   =>  false,
                'message'   => 'Department or Description is not provided'
            ],400);
        }

        $updated =  $department->update([
                        'department'    =>  $dept_name,
                        'description'   =>  $dept_desc,
                        'updated_by'    =>  $userid
                    ]);

        if($updated){
            return response()->json([
                'success'   =>  true,
                'message'   => 'Department is updated successfully'
            ],200);    
        } else {
            return response()->json([
                'success'   =>  false,
                'message'   => 'Department could not be updated'
            ],400);
        } 
    }

    public function destroy($id){
        $department = Department::find($id);
        
        if(!$department){
            return response()->json([
                'success'   =>  false,
                'message'   => 'Sorry, Department with id '.$id.' cannot be found'
            ],400);
        }
        
        $deleted = $department->delete();

        if($deleted){
            return response()->json([
                'success'   =>  true,
                'message'   => 'Department is deleted'
            ],200);
        } else{
            return response()->json([
                'success'   =>  false,
                'message'   => 'Department could not be deleted'
            ],400);
        }
    }
}