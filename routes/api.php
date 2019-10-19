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
	Route::post('logout', 'ApiController@logout');
	Route::post('user', 'ApiController@getAuthUser');

	Route::get('get-user-roles/{id}', 'ApiController@getUserRoles');
	Route::get('roles-permissions/{id}', 'ApiController@getRolePermission');
	Route::get('get-permissions', 'ApiController@getPermissions');

	Route::get('candidates','CandidateController@listCandidates');
	Route::get('candidates/{id}','CandidateController@showCandidate');
	Route::post('candidates/add','CandidateController@addCandidate');
	Route::put('candidates/{id}/update','CandidateController@updateCandidate');
	Route::delete('candidates/{id}','CandidateController@deleteCandidate');
	Route::post('candidates/{id}/professional-documents/add','CandidateController@add');
	Route::post('candidates/{id}/professional-documents/update','CandidateController@update');
	Route::get('candidates/{id}/professional-documents','CandidateController@index');
	Route::post('candidates/{id}/assessment/add','TaskController@add');
	Route::post('candidates/{id}/assessment/update','TaskController@update');
	Route::get('candidates/{id}/assessment/','TaskController@index');
	Route::get('candidates/{id}/id-card/','IDCardController@index');
	Route::post('candidates/{id}/id-card/add','IDCardController@add');
	Route::post('candidates/{id}/id-card/update','IDCardController@update');
	Route::delete('candidates/{id}/id-card/delete','IDCardController@delete');

	Route::post('factsheet/add','FactSheetController@add');
	Route::get('factsheet/show/{id}','FactSheetController@show');
	Route::put('factsheet/update/{id}','FactSheetController@update');

	Route::apiResource('departments', 'DepartmentController');
	Route::apiResource('roles', 'Role\RoleController');
	Route::apiResource('permission', 'Role\PermissionController');
	Route::apiResource('leads','LeadController');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
