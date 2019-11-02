<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Validator;
use Auth;
use App\Department;
//use DB;

class DepartmentController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        if(!isset($request->token)){
            return response()->json(['success' => false]);
        }
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    protected $validationRules = [
        'department' => 'required|max:255',
        'description' => 'required|max:255',
    ];

    public function index(Request $request){
        $sort = $request->sort;
        $order = $request->order;
        $search = $request->search;
        $limit = $request->limit;

        $departmentlist = Department::query();

        $department_results = $departmentlist->get();

        if(count($department_results) === 0){
            $msg = 'No Records found';
            error_404(false,$msg);
            die;
        }
        if($search){
            $departmentlist = $departmentlist->where('department','LIKE','%'.$search.'%')
                              ->orWhere('description','LIKE','%'.$search.'%');
            $search_results = $departmentlist->get();

            if(count($search_results) === 0){
                $msg = 'No search results found for the query '.$search;
                error_404(false,$msg);
                die;
            }
        }

        if($sort && $order){
            $list = $departmentlist->orderBy($sort,$order)->paginate($limit);
        } else {
            $list = $departmentlist->orderBy('id','ASC')->paginate($limit);
        }
		return http_201('success', $list);
    }

    public function show($id){
        $department_list = Department::find($id);

        if(!$department_list){
            $msg = 'Department with id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }
		return http_201('success', $department_list);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),$this->validationRules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

		DB::beginTransaction();
		try {
			$departments = new Department();
			$departments->department = $request->department;
			$departments->description = $request->description;
			$departments->created_by = Auth::user()->id;
			$departments->updated_by = Auth::user()->id;
			$departments->save();
			DB::commit();
			$msg = 'Department is created successfully';
			$response =  http_201($msg, $departments);
		} catch (\Exception $e){
			DB::rollback();
			$response = response()->json(['error' => $e->getMessage()], 500);
		}
		return $response;
    }

    public function update(Request $request,$id)
    {
        $userid = Auth::user()->id;
        $department = Department::find($id);

        if(!$department){
            $msg = 'Sorry, Department with id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }

        $validator = Validator::make($request->all(),$this->validationRules);
        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $dept_name = $request->department;
        $dept_desc = $request->description;
        $updated =  $department->update([
                        'department'    =>  $dept_name,
                        'description'   =>  $dept_desc,
                        'updated_by'    =>  $userid
                    ]);

        $msg = 'Department is updated successfully';
		return  http_201($msg, $department);
    }

    public function destroy($id){
        $department = Department::find($id);

        if(!$department){
            $msg = 'Sorry, Department with id '.$id.' cannot be found';
            error_404(false,$msg);
            die;
        }

        $deleted = $department->delete();
        $msg = 'Department is deleted';
        success_200(true,$msg,'');
    }
}
