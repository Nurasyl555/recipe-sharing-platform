<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::where('status', 'published')
            ->with(['user', 'category', 'cuisine', 'ratings'])
            ->paginate(15);
        
        return view('recipes.index', compact('recipes'));
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['user', 'category', 'cuisine', 'ingredients', 'ratings']);
        
        return view('recipes.show', compact('recipe'));
    }
}