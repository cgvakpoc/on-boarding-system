<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');

Route::group(['middleware' => 'auth.jwt'], function () {
	Route::get('logout', 'ApiController@logout');
	Route::get('user', 'ApiController@getAuthUser');
	Route::get('get-user-roles/{id}', 'ApiController@getUserRoles');
	Route::get('roles-permissions/{id}', 'ApiController@getRolePermission');
	Route::get('get-permissions', 'ApiController@getPermissions');

	Route::apiResource('departments', 'DepartmentController');
	Route::apiResource('roles', 'Role\RoleController');
	Route::apiResource('permission/{roleId}', 'Role\PermissionController');


});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
