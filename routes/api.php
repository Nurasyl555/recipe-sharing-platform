<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;

/**
 * @OA\Get(
 *     path="/api/health",
 *     tags={"System"},
 *     summary="Health check",
 *     @OA\Response(response=200, description="API is healthy")
 * )
 */
Route::get('/health', function () {
    return ApiResource::success(['status' => 'ok'], 'API is healthy');
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    /**
     * @OA\Get(
     *     path="/api/user",
     *     tags={"User"},
     *     summary="Get current user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Route::get('/user', function (Request $request) {
        return ApiResource::success(
            new UserResource($request->user()),
            'Current user loaded'
        );
    });

    Route::post('/ratings', [RatingController::class, 'store'])->name('api.ratings.store');
    Route::delete('/ratings/{recipeId}', [RatingController::class, 'destroy'])->name('api.ratings.destroy');

    Route::post('/favorites/{recipeId}', [FavoriteController::class, 'store'])->name('api.favorites.store');
    Route::delete('/favorites/{recipeId}', [FavoriteController::class, 'destroy'])->name('api.favorites.destroy');
});