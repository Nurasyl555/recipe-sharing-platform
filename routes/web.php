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

    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::delete('/ratings/{recipeId}', [RatingController::class, 'destroy'])->name('ratings.destroy');
    Route::post('/favorites/{recipeId}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{recipeId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
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
// Админ панель
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Главная панель
    Route::get('/dashboard', function () {
        // Берем статистику и рецепты, ожидающие проверки
        $pendingRecipes = \App\Models\Recipe::with('user')->where('status', 'draft')->latest()->get();
        $totalRecipes = \App\Models\Recipe::count();
        $totalUsers = \App\Models\User::count();

        return view('admin.dashboard', compact('pendingRecipes', 'totalRecipes', 'totalUsers'));
    })->name('dashboard');

    // Одобрить рецепт
    Route::patch('/recipes/{recipe}/approve', function (\App\Models\Recipe $recipe) {
        $recipe->update(['status' => 'published']);
        return back()->with('success', 'Recipe approved and published!');
    })->name('recipes.approve');

    // Отклонить рецепт
    Route::patch('/recipes/{recipe}/reject', function (\App\Models\Recipe $recipe) {
        $recipe->update(['status' => 'rejected']);
        return back()->with('success', 'Recipe rejected.');
    })->name('recipes.reject');
});
require __DIR__.'/auth.php';
