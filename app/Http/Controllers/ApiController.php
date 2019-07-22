<?php
 
namespace App\Http\Controllers;
 
// use App\Http\Requests\RegisterAuthRequest;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\UserRole;

class ApiController extends Controller
{
    public $loginAfterSignUp = false;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|unique:users',
            'password'=> 'required',
            'role'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        if($user){
            UserRole::create([
                'user_id' => $user->id,
                'role' => $request->get('role'),
            ]);
        }

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
        
        return response()->json([
            'success' => true
        ], 200);
    }

    // public function register(RegisterAuthRequest $request)
    // {
    //     echo '123';
    //     // print_r($request->all());
    //     die;
    //     $user = new User();
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->password = bcrypt($request->password);
    //     $user->save();
 
    //     if ($this->loginAfterSignUp) {
    //         return $this->login($request);
    //     }
 
    //     return response()->json([
    //         'success' => true,
    //         'data' => $user
    //     ], 200);
    // }
 
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;
 
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
 
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }
 
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
 
    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json(['user' => $user]);
    }
}