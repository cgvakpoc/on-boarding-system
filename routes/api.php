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
    Route::apiResource('departments','DepartmentController'); 
    Route::apiResource('leads','LeadController');
    Route::apiResource('newjoinees','NewjoineeController');
    Route::post('candidates/{id}/professional-documents/add','CandidateController@add');
    Route::post('candidates/{id}/professional-documents/update','CandidateController@update');
    Route::get('candidates/{id}/professional-documents','CandidateController@index');
    Route::delete('candidates/{id}/professional-documents/delete','CandidateController@delete');
    Route::post('candidates/{id}/assessment/add','TaskController@add');
    Route::post('candidates/{id}/assessment/update','TaskController@update');
    Route::get('candidates/{id}/assessment/','TaskController@index');
    Route::delete('candidates/{id}/assessment/delete','TaskController@delete');
    Route::get('candidates/{id}/id-card/','IDCardController@index');
    Route::post('candidates/{id}/id-card/add','IDCardController@add');
    Route::post('candidates/{id}/id-card/update','IDCardController@update');
    Route::delete('candidates/{id}/id-card/delete','IDCardController@delete');
    Route::post('factsheet/add','FactSheetController@add');
    Route::get('factsheet/show/{id}','FactSheetController@show');
    Route::put('factsheet/update/{id}','FactSheetController@update');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
