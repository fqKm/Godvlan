<?php

use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('register',[\App\Http\Controllers\UserController::class,'register']);
Route::post('login',[\App\Http\Controllers\UserController::class,'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function(){
    Route::get('profile',[\App\Http\Controllers\UserController::class,'profile']);
    Route::patch('profile/update',[\App\Http\Controllers\UserController::class,'update']);
    Route::delete('logout',[\App\Http\Controllers\UserController::class,'logout']);
    Route::post('transaction/add',[\App\Http\Controllers\TransactionController::class,'add']);
    Route::get('transaction/history',[\App\Http\Controllers\TransactionController::class,'index']);
    Route::get('transaction/history/{id}',[\App\Http\Controllers\TransactionController::class,'show'])->where('id','[0-9]+');
    Route::patch('transaction/edit/{id}',[\App\Http\Controllers\TransactionController::class,'update'])->where('id','[0-9]+');
});


