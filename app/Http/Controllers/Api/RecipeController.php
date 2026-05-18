<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\RecipeResource;
use OpenApi\Attributes as OA;

class RecipeController extends Controller
{
    #[OA\Get(
        path: "/api/recipes",
        operationId: "listRecipes",
        tags: ["Recipes"],
        summary: "List all recipes",
        parameters: [
            new OA\Parameter(name: "page", in: "query", schema: new OA\Schema(type: "integer", default: 1)),
            new OA\Parameter(name: "limit", in: "query", schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(response: 200, description: "Recipes list retrieved successfully")
        ]
    )]
    public function index()
    {
        $recipes = Recipe::where('status', 'published')
            ->with(['user', 'category', 'cuisine', 'ingredients', 'ratings'])
            ->paginate(request('limit', 15));
        
        return ApiResource::success(
            RecipeResource::collection($recipes),
            'Recipes retrieved successfully'
        );
    }

    #[OA\Post(
        path: "/api/recipes",
        operationId: "storeRecipe",
        tags: ["Recipes"],
        summary: "Create new recipe",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [
            new OA\Response(response: 201, description: "Recipe created successfully"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(StoreRecipeRequest $request)
    {
        try {
            $recipe = Recipe::create($request->validated() + [
                'user_id' => auth()->id(),
                'status' => 'draft'
            ]);
            
            return ApiResource::success(
                new RecipeResource($recipe),
                'Recipe created successfully',
                201
            );
        } catch (\Exception $e) {
            return ApiResource::error('Failed to create recipe', 422);
        }
    }

    #[OA\Get(
        path: "/api/recipes/{id}",
        operationId: "showRecipe",
        tags: ["Recipes"],
        summary: "Get recipe by ID",
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Recipe retrieved"),
            new OA\Response(response: 404, description: "Recipe not found")
        ]
    )]
    public function show(Recipe $recipe)
    {
        $recipe->load(['user', 'category', 'cuisine', 'ingredients', 'ratings']);
        return ApiResource::success(
            new RecipeResource($recipe),
            'Recipe retrieved successfully'
        );
    }

    #[OA\Put(
        path: "/api/recipes/{id}",
        operationId: "updateRecipe",
        tags: ["Recipes"],
        summary: "Update recipe",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent()),
        responses: [
            new OA\Response(response: 200, description: "Recipe updated successfully"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        // Проверка ownership
        if ($recipe->user_id !== auth()->id()) {
            return ApiResource::error('Unauthorized', 403);
        }

        try {
            $recipe->update($request->validated());
            $recipe->load(['user', 'category', 'cuisine', 'ingredients', 'ratings']);
            
            return ApiResource::success(
                new RecipeResource($recipe),
                'Recipe updated successfully'
            );
        } catch (\Exception $e) {
            return ApiResource::error('Failed to update recipe', 422);
        }
    }

    #[OA\Delete(
        path: "/api/recipes/{id}",
        operationId: "destroyRecipe",
        tags: ["Recipes"],
        summary: "Delete recipe",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Recipe deleted successfully"),
            new OA\Response(response: 403, description: "Unauthorized"),
            new OA\Response(response: 404, description: "Recipe not found")
        ]
    )]
    public function destroy(Recipe $recipe)
    {
        // Проверка ownership
        if ($recipe->user_id !== auth()->id()) {
            return ApiResource::error('Unauthorized', 403);
        }

        $recipe->delete();
        return ApiResource::success(null, 'Recipe deleted successfully', 200);
    }
}
