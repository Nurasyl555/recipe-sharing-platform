<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// 1. ЗАЩИЩЕННЫЕ РОУТЫ (Должны быть выше!)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Управление рецептами (create, store, edit, update, destroy)
    Route::resource('recipes', RecipeController::class)->except(['index', 'show']);

    Route::get('/my-recipes', function () {
        $recipes = auth()->user()->recipes()->latest()->paginate(10);
        return view('recipes.my-recipes', compact('recipes'));
    })->name('recipes.my-recipes');

    Route::post('/ratings', [RatingController::class, 'store']);
    Route::delete('/ratings/{recipeId}', [RatingController::class, 'destroy']);
    Route::post('/favorites/{recipeId}', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{recipeId}', [FavoriteController::class, 'destroy']);
});

// ==========================================
// 2. ОТКРЫТЫЕ РОУТЫ (Опускаем вниз)
// ==========================================
Route::resource('recipes', RecipeController::class)->only(['index', 'show']);


// Админ панель
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
