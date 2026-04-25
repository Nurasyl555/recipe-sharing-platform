<?php

use App\Models\Recipe;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;

// Главная страница
Route::get('/', function () {
    return view('welcome');
});


// Роуты для авторизованных пользователей
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Роуты только для админа
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Сюда другие участники добавят: users, recipes management
});
// Защищенные роуты (создание, редактирование, удаление — только для авторизованных)
Route::middleware('auth')->group(function () {
    Route::resource('recipes', \App\Http\Controllers\RecipeController::class)->except(['index', 'show']);

    // Твой роут "Мои рецепты"
    Route::get('/my-recipes', function () {
        $recipes = auth()->user()->recipes()->latest()->paginate(10);
        return view('recipes.my-recipes', compact('recipes'));
    })->name('recipes.my-recipes');
});
// Recipe routes
// Открытые роуты для всех (список всех рецептов и просмотр одного рецепта)
Route::resource('recipes', \App\Http\Controllers\RecipeController::class)->only(['index', 'show']);



require __DIR__.'/auth.php';
