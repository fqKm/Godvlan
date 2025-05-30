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
});


