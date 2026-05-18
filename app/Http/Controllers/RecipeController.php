<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Ingredient;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Notifications\RecipeCreatedNotification;

class RecipeController extends Controller
{
    // Private helpers---
    private function syncIngredients(Recipe $recipe, array $names, array $amounts): void
    {
        $sync = [];

        foreach ($names as $index => $name) {
            $ingredient = Ingredient::firstOrCreate(
                ['name' => strtolower(trim($name))]
            );
            $sync[$ingredient->id] = ['amount' => $amounts[$index]];
        }

        $recipe->ingredients()->sync($sync);
    }

    private function  authorizeRecipe(Recipe $recipe): void
    {
        if (!auth()->user()->isAdmin() && !$recipe->isOwnedBy(auth()->user())) {
            abort(403, 'You do not have permission to access this recipe.');
        }

    }
    //-------------------------------

    // GET /recipes — a list of all published recipes
    public function index(\Illuminate\Http\Request $request)
    {
        $recipes = Recipe::with(['user', 'category', 'cuisine'])
            ->where('status', 'published')
            ->when($request->search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->cuisine, function ($query, $cuisineId) {
                $query->where('cuisine_id', $cuisineId);
            })
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->difficulty, function ($query, $difficulty) {
                $query->where('difficulty', $difficulty);
            })
            ->when($request->max_time, function ($query, $maxTime) {
                $query->whereRaw('(prep_time + cook_time) <= ?', [$maxTime]);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $cuisines = Cuisine::all();
        $categories = Category::all();

        return view('recipes.index', compact('recipes', 'cuisines', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     * GET /recipes/create/ form create
     */
    public function create()
    {
        $categories = Category::all();
        $cuisines = Cuisine::all();

        return view('recipes.create', compact('categories', 'cuisines'));
    }

    /**
     * Store a newly created resource in storage.
     * POST /recipes Save the new recipe
     */
    public function store(StoreRecipeRequest $request)
    {
        $data = $request->validated();

        // image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        $data['user_id'] = auth()->id();
        $data['status'] = 'draft';

        $recipe = Recipe::create($data);

        // Save ingredients via pivot
        $this->syncIngredients($recipe, $request->ingredients, $request->amounts);
        $request->user()->notify(
    new RecipeCreatedNotification($recipe->title)
);
        return redirect()
            ->route('recipes.my-recipes')
            ->with('success', __('messages.recipe_created_success'));
    }
     
    /**
     * Display the specified resource.
     * GET /recipes/{recipe}
     */
    public function show(Recipe $recipe)
    {
        $recipe->load(['user', 'category', 'cuisine', 'ingredients', 'ratings.user']);

        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     * GET /recipes/{recipe}/edit
     */
    public function edit(Recipe $recipe)
    {
        $this->authorizeRecipe($recipe);
        $categories = Category::all();
        $cuisines   = Cuisine::all();

        return view('recipes.edit', compact('recipe', 'categories', 'cuisines'));

    }

    /**
     * Update the specified resource in storage.
     * PUT /recipes/{recipe}
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $data = $request->validated();

        // Заменить фото если загрузили новое
        if ($request->hasFile('image')) {
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        $recipe->update($data);

        // Обновить ингредиенты
        $this->syncIngredients($recipe, $request->ingredients, $request->amounts);

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', __('messages.recipe_updated_success'));
        }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        $this->authorizeRecipe($recipe);

        if ($recipe->image) {
            Storage::disk('public')->delete($recipe->image);
        }

        $recipe->delete();
return redirect()
    ->route('recipes.my-recipes')
    ->with('success', __('messages.recipe_deleted_success'));
}


}
