<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::post('/ratings', [RatingController::class, 'store']);
    Route::delete('/ratings/{recipeId}', [RatingController::class, 'destroy']);
    Route::post('/favorites/{recipeId}', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{recipeId}', [FavoriteController::class, 'destroy']);
});
