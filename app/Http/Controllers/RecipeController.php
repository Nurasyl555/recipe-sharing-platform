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
    public function index()
    {
        $query = Recipe::with(['user', 'category', 'cuisine'])
            ->where('status', 'published');

        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        if (request('cuisine_id')) {
            $query->where('cuisine_id', request('cuisine_id'));
        }

        if (request('prep_time')) {
            $query->where('prep_time', '<=', request('prep_time'));
        }

        if (request('ingredient')) {
            $query->whereHas('ingredients', function ($q) {
                $q->where('name', 'like', '%' . request('ingredient') . '%');
            });
        }

        if (request('sort') === 'trending') {
            $query->withAvg('ratings', 'score')
                ->withCount('ratings')
                ->orderByDesc('ratings_avg_rating')
                ->orderByDesc('ratings_count');
        } else {
            // default sorting
            $query->latest();
        }

        $recipes = $query->paginate(12)->withQueryString();

        return view('recipes.index', compact('recipes'));
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
        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'Recipe created successfully. Waiting for approval.');
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

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'Recipe updated successfully!');
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

        return redirect()->route('recipes.index')
            ->with('success', 'Recipe deleted.');
    }

}
