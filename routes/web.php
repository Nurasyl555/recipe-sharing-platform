<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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

require __DIR__.'/auth.php';
