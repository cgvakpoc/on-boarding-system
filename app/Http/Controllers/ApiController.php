<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterAuthRequest;
use App\Role;
use App\User;
use App\Permission;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
	public $loginAfterSignUp = false;

	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|string|email|max:255|unique:users',
			'name' => 'required|string|unique:users',
			'password' => 'required',
			'role' => 'required',
		]);

		if ($validator->fails()) {
			$response = http_200(false, 'Validation Error', $validator->errors());
		}

		if (!$validator->fails()) {
			$response = $this->createUser($request);
		}

		return $response;
	}

	/**
	 * @param $request
	 * @return string
	 */
	private function createUser($request)
	{
		DB::beginTransaction();

		try {
			$user = new User();
			$user->name = $request->input('name');
			$user->email = $request->input('email');
			$user->password = bcrypt($request->get('password'));
			$user->created_at = date('Y-m-d H:i:s');
			$user->updated_at = date('Y-m-d H:i:s');
			$user->save();

			$admin = Role::where('name', '=', $request->get('role'))->first();
			$user->roles()->attach($admin->id);
			$response = http_201('User entry has been created successfully', $user);

			DB::commit();

		} catch (\Exception $e) {
			DB::rollback();
			$response = response()->json(['error' => $e->getMessage()], 500);
		}
		return $response;
	}

	public function getUserRoles($id)
	{
		$user = User::find($id);
		$response = error_404();

		if ($user) {
			$response = http_200(false, 'Success', $user->roles);
		}

		return $response;
	}

	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required',
			'password' => 'required',
		]);

		if($validator->fails()){
			$response = error_401('Validation Error', $validator->errors());
			return $response;
		}

		$input = $request->only('email', 'password');
		$jwt_token = null;

		if (!$jwt_token = JWTAuth::attempt($input)) {
			$response = error_401('Invalid Email or Password');
			return $response;
		}
		Auth::user()->roles;
		$response = response()->json(['success' => true, 'token' => $jwt_token, 'user' => Auth::user()]);
		return $response;
	}

	public function logout(Request $request)
	{
		$this->validate($request, [
			'token' => 'required'
		]);

		try {
			JWTAuth::invalidate($request->token);
			$response = http_200(true, 'User logged out successfully', '');

		} catch (JWTException $exception) {
			$response = http_200(false, 'Sorry, The user cannot be logged out', '');

		}
		return $response;
	}

	public function getAuthUser(Request $request)
	{
		//Commented on 26/12/2019. Reason: validation done in middleware
		// $this->validate($request, ['token' => 'required']);

		$user = JWTAuth::authenticate($request->token);

		return response()->json(['user' => $user]);
	}
	
	public function getAuthenticatedUser()
            {
                    try {

                            if (! $user = JWTAuth::parseToken()->authenticate()) {
                                    return response()->json(['user_not_found'], 404);
                            }

                    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                            return response()->json(['token_expired'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                            return response()->json(['token_invalid'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                            return response()->json(['token_absent'], $e->getStatusCode());

                    }

                    return response()->json(compact('user'));
            }

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getPermissions()
	{
		$permissions = Permission::all();
		return http_200(true, '', $permissions);
	}
}
