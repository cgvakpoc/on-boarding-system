<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Auth;
use App\Role;

class PermissionController extends Controller
{
	protected $user;

	/**
	 * PermissionController constructor.
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		if (!isset($request->token)) {
			return response()->json(['status' => false]);
		}
		$this->user = JWTAuth::parseToken()->authenticate();
	}

	/**
	 * @param $roleId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index($roleId)
	{
		$role = Role::where('id', '=', $roleId)->first();

		$permissionList = $role->permissions;

		$response = error_404();

		if (!empty($permissionList)) {
			$response = http_200(true, 'Success', $permissionList);
		}
		return $response;
	}


	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store($roleId, Request $request)
	{
		$data = $request->all();
		$permissionId = [];

		$validator = Validator::make($request->all(), ["permissionId" => "required"]);

		if ($validator->fails()) {
			return http_200(false, 'Validation Error', $validator->errors());
		}

		foreach ($data['permissionId'] as $d) {
			array_push($permissionId, $d);
		}
		return $this->createRolePermission($permissionId, $roleId);
	}

	/**
	 * @param $permissionId
	 * @param $roleId
	 * @return \Illuminate\Http\JsonResponse
	 */
	private function createRolePermission($permissionId, $roleId)
	{
		$role = Role::where('id', '=', $roleId)->first();
		$role->perms()->sync([]);
		$role->perms()->sync($permissionId);
		return http_201('Permission has been assigned successfully', $role->permissions);
	}

	/**
	 * @param $role
	 * @return \Illuminate\Http\JsonResponse
	 */
	public
	function getRoleUser($role)
	{
		$permissions = User::withRole($role)->get();

		$response = error_404();

		if (!empty($permissions)) {
			$response = http_200(true, 'Success', $permissions);
		}
		return $response;
	}

}