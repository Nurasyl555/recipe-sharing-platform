<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;

Route::get('/health', function () {
    return ApiResource::success([
        'status' => 'ok',
    ], 'API is healthy');
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return ApiResource::success(
        new UserResource($request->user()),
        'Current user loaded'
    );
});