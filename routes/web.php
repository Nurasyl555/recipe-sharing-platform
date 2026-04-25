<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;

// ==========================================
// 1. Открытые роуты (доступны всем гостям)
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

// Просмотр всех рецептов и одного конкретного
Route::resource('recipes', RecipeController::class)->only(['index', 'show']);


// ==========================================
// 2. Защищенные роуты (только для авторизованных)
// ==========================================
Route::middleware('auth')->group(function () {

    // Основной дашборд
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Управление профилем (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Управление рецептами (создание, редактирование, удаление)
    Route::resource('recipes', RecipeController::class)->except(['index', 'show']);

    // Страница "Мои рецепты"
    Route::get('/my-recipes', function () {
        $recipes = auth()->user()->recipes()->latest()->paginate(10);
        return view('recipes.my-recipes', compact('recipes'));
    })->name('recipes.my-recipes');

    // Рейтинги и Избранное (Код от Person 3)
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::delete('/ratings/{recipeId}', [RatingController::class, 'destroy']);
    Route::post('/favorites/{recipeId}', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{recipeId}', [FavoriteController::class, 'destroy']);

});


// ==========================================
// 3. Админ-панель (только для администраторов)
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Сюда другие участники добавят: users, recipes management
});


// Роуты авторизации (логин, регистрация, пароли)
require __DIR__.'/auth.php';
