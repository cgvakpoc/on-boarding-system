<?php

namespace App\Http\Controllers;

use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Role;
use App\User;
use App\UserRole;
use App\Synergysystem;
use App\SynergysystemDesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function checkAndCreateUser(Request $request){
		try {
			$user_name = $request->user_name;
			$password = $request->password;
			$synergyUser = Synergysystem::where(['LoginUserName' => $user_name, 'LoginPassword' => $password])->get();
			if(isset($synergyUser[0])){
				$user = User::where(['employee_id' => trim($synergyUser[0]->EmployeeNumber)]);
				if($user->count() > 0){
					$response = http_201('User already exists', $user);
				} else {
					$new_user = new User;
					$new_user->name = trim($synergyUser[0]->EmployeeFirstName);
					$new_user->email = trim($synergyUser[0]->EmployeeCorporateEmailId);
					$new_user->password = md5(trim($synergyUser[0]->LoginPassword));
					$new_user->employee_id = trim($synergyUser[0]->EmployeeNumber);
					$new_user->save();
					
					$userRole = DB::insert('insert into role_user(user_id, role_id) values(?,?)', [
						$new_user->id,
						$synergyUser[0]->DesignationICode
					]);
					$response = http_201('User entry has been created successfully', $new_user);
				}
			}
		} catch (\Exception $e) {
			$response = response()->json(['error' => $e->getMessage()], 500);
		}
		return $response;
	}
	
	public function createUserRoles(){ // Create new Roles which exists in Synergy DB
		try {
			$res = SynergysystemDesignation::all();
			if(!empty($res)){
				foreach ($res as $key => $value) {
					if(Role::where(['id' => $value['DesignationICode']])->count() == 1){
						Role::where(['id' => $value['DesignationICode']])
								->update([
									'name' => $value['Designation'],
									'display_name' => $value['Designation'],
									'description' => $value['Designation'],
								]);
					} else {
						$role = new Role;
						$role->name = $value['Designation'];
						$role->display_name = $value['Designation'];
						$role->description = $value['Designation'];
						$role->save();
					}
				}
				$response = http_201('Roles Updated successully.', array());
			}
		} catch (\Exception $e) {
			$response = response()->json(['error' => $e->getMessage()], 500);
		}
		return $response;
	}

}
