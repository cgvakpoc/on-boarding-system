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
        $sort = $request->input('sort');
        $order = $request->input('order');
        $search = $request->input('search');
        $limit = $request->input('limit');
        
        if($sort && $order){
            $list = Department::orderBy($sort,$order)->paginate($limit); 
            return response()->json($list);   
        }

        if($search){
            $search_list = Department::where('department','LIKE','%'.$search.'%')->orWhere('description','LIKE','%'.$search.'%')->get();
            if(count($search_list) > 0){
                return response()->json($search_list);
            } else{
                return response()->json([
                    'success'   =>  false,
                    'message'   =>  'No Details found for the query.'
                ],500);
            }    
        }
            
        $departmentlist = Department::paginate($limit);
        
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

        $userid = Auth::user()->id;

        if($request->exists('department_name')) {
            if($request->has('department_name')) {
                $dept_name = $request->get('department_name');
            } else {
                return response()->json([
                    'success'   =>  false,
                    'message'   => 'Department Name cannot be empty'
                ],400);
            }
        }
        if($request->exists('description')){
            if($request->has('description')){
                $dept_desc = $request->get('description');   
            } else {
                return response()->json([
                    'success'   =>  false,
                    'message'   => 'Description cannot be empty'
                ],400);
            }
        }

        $updated =  $department->update([
                        'department'    =>  isset($dept_name) ? $dept_name : $department->department,
                        'description'   =>  isset($dept_desc) ? $dept_desc : $department->description,
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