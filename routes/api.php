<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TuyenController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'Customer'],function(){

	Route::post('register',[CustomerController::class,'register']);

	Route::post('checkEmail',[CustomerController::class,'checkEmail']);

});

Route::group(['prefix'=>'Tuyen'],function(){

	Route::get('getAllTuyen',[TuyenController::class,'getAllTuyen']);

	Route::post('timChuyenTau',[TuyenController::class,'timChuyenTau']);

	Route::post('getToaTauTheoChuyen',[TuyenController::class,'getToaTauTheoChuyen']);

	Route::post('getVetau',[TuyenController::class,'getVetau']);

	Route::post('datve',[TuyenController::class,'datve']);
});


