<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\RecipeController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ru', 'kk'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// ============ PUBLIC PAGES ============
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');

// ============ AUTH PAGES ============
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-recipes', function () {
        $recipes = auth()->user()->recipes()
            ->with(['category', 'cuisine'])
            ->latest()
            ->paginate(10);
        return view('recipes.my-recipes', compact('recipes'));
    })->name('recipes.my-recipes');
});

// ============ ADMIN ============
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $pendingRecipes = \App\Models\Recipe::with('user')
            ->where('status', 'draft')
            ->latest()
            ->paginate(10);
        $totalRecipes = \App\Models\Recipe::count();
        $totalUsers = \App\Models\User::count();

        return view('admin.dashboard', compact('pendingRecipes', 'totalRecipes', 'totalUsers'));
    })->name('dashboard');

    Route::patch('/recipes/{recipe}/approve', function (\App\Models\Recipe $recipe) {
        $recipe->update(['status' => 'published']);
        return back()->with('success', __('messages.recipe_approved'));
    })->name('recipes.approve');

    Route::patch('/recipes/{recipe}/reject', function (\App\Models\Recipe $recipe) {
        $recipe->update(['status' => 'rejected']);
        return back()->with('success', __('messages.recipe_rejected'));
    })->name('recipes.reject');
});

require __DIR__.'/auth.php';
