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

/**
 * @OA\Tag(name="Recipes", description="Recipe management operations")
 */
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

    /**
     * @OA\Get(
     *     path="/api/recipes",
     *     summary="Get published recipes list",
     *     tags={"Recipes"},
     *     @OA\Parameter(name="search", in="query", description="Search by title or description", @OA\Schema(type="string")),
     *     @OA\Parameter(name="cuisine", in="query", description="Filter by cuisine id", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="category", in="query", description="Filter by category id", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="difficulty", in="query", description="Filter by difficulty", @OA\Schema(type="string")),
     *     @OA\Parameter(name="max_time", in="query", description="Filter by maximum total time", @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="List of recipes",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Recipe")),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
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
     * @OA\Post(
     *     path="/api/recipes",
     *     summary="Create new recipe (authenticated)",
     *     tags={"Recipes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","instructions"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="instructions", type="string"),
     *             @OA\Property(property="prep_time", type="integer"),
     *             @OA\Property(property="cook_time", type="integer"),
     *             @OA\Property(property="servings", type="integer"),
     *             @OA\Property(property="difficulty", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="cuisine_id", type="integer"),
     *             @OA\Property(property="ingredients", type="array", @OA\Items(type="object",
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="amount", type="string"),
     *                 @OA\Property(property="unit", type="string")
     *             ))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Recipe created", @OA\JsonContent(ref="#/components/schemas/Recipe")),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation failed")
     * )
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
        return redirect()
            ->route('recipes.my-recipes')
            ->with('success', __('messages.recipe_created_success'));
    }

    /**
     * @OA\Get(
     *     path="/api/recipes/{id}",
     *     summary="Get recipe by id",
     *     tags={"Recipes"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Recipe details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", ref="#/components/schemas/Recipe"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
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
