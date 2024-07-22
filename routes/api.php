<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostControler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);





Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('posts', PostControler::class);
});


// Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');


// Route::apiResource('posts',[PostControler::class])->middleware('auth:sanctum');
