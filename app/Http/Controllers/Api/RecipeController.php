<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\RecipeResource;
use OpenApi\Attributes as OA;
use Exception;

class RecipeController extends Controller
{
    #[OA\Get(
        path: "/recipes",
        operationId: "listRecipes",
        tags: ["Recipes"],
        summary: "Get all published recipes",
        description: "Retrieve a paginated list of published recipes",
        parameters: [
            new OA\Parameter(name: "page", in: "query", description: "Page number", schema: new OA\Schema(type: "integer", default: 1)),
            new OA\Parameter(name: "limit", in: "query", description: "Items per page", schema: new OA\Schema(type: "integer", default: 15))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Recipes list retrieved",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object")),
                        new OA\Property(property: "message", type: "string"),
                    ]
                )
            ),
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
        path: "/recipes",
        operationId: "storeRecipe",
        tags: ["Recipes"],
        summary: "Create new recipe",
        description: "Create a new recipe (requires authentication)",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Recipe data",
            content: new OA\JsonContent(
                required: ["title", "description", "instructions", "prep_time", "cook_time", "servings", "difficulty", "category_id", "cuisine_id", "ingredients", "amounts"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Pasta Carbonara", minLength: 3, maxLength: 255),
                    new OA\Property(property: "description", type: "string", example: "Delicious Italian pasta", minLength: 10),
                    new OA\Property(property: "instructions", type: "string", example: "Step 1... Step 2...", minLength: 20),
                    new OA\Property(property: "prep_time", type: "integer", example: 10, minimum: 1),
                    new OA\Property(property: "cook_time", type: "integer", example: 20, minimum: 1),
                    new OA\Property(property: "servings", type: "integer", example: 4, minimum: 1),
                    new OA\Property(property: "difficulty", type: "string", enum: ["easy", "medium", "hard"]),
                    new OA\Property(property: "category_id", type: "integer", example: 1),
                    new OA\Property(property: "cuisine_id", type: "integer", example: 1),
                    new OA\Property(property: "ingredients", type: "array", items: new OA\Items(type: "string"), example: ["Pasta", "Tomato"]),
                    new OA\Property(property: "amounts", type: "array", items: new OA\Items(type: "string"), example: ["500g", "300ml"]),
                    new OA\Property(property: "image", type: "string", format: "binary", nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Recipe created successfully"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error"),
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
        } catch (Exception $e) {
            return ApiResource::error('Failed to create recipe', 422);
        }
    }

    #[OA\Get(
        path: "/recipes/{recipe}",
        operationId: "showRecipe",
        tags: ["Recipes"],
        summary: "Get recipe by ID",
        description: "Retrieve a single recipe with all details",
        parameters: [
            new OA\Parameter(name: "recipe", in: "path", required: true, description: "Recipe ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Recipe retrieved"),
            new OA\Response(response: 404, description: "Recipe not found"),
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
        path: "/recipes/{recipe}",
        operationId: "updateRecipe",
        tags: ["Recipes"],
        summary: "Update recipe",
        description: "Update a recipe (owner only)",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "recipe", in: "path", required: true, description: "Recipe ID", schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Updated recipe data",
            content: new OA\JsonContent(
                required: ["title", "description", "instructions", "prep_time", "cook_time", "servings", "difficulty", "category_id", "cuisine_id"],
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "description", type: "string"),
                    new OA\Property(property: "instructions", type: "string"),
                    new OA\Property(property: "prep_time", type: "integer"),
                    new OA\Property(property: "cook_time", type: "integer"),
                    new OA\Property(property: "servings", type: "integer"),
                    new OA\Property(property: "difficulty", type: "string"),
                    new OA\Property(property: "category_id", type: "integer"),
                    new OA\Property(property: "cuisine_id", type: "integer"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Recipe updated successfully"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 403, description: "Forbidden - Not owner"),
            new OA\Response(response: 404, description: "Recipe not found"),
            new OA\Response(response: 422, description: "Validation error"),
        ]
    )]
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
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
        } catch (Exception $e) {
            return ApiResource::error('Failed to update recipe', 422);
        }
    }

    #[OA\Delete(
        path: "/recipes/{recipe}",
        operationId: "destroyRecipe",
        tags: ["Recipes"],
        summary: "Delete recipe",
        description: "Delete a recipe (owner only)",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "recipe", in: "path", required: true, description: "Recipe ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Recipe deleted successfully"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 403, description: "Forbidden - Not owner"),
            new OA\Response(response: 404, description: "Recipe not found"),
        ]
    )]
    public function destroy(Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            return ApiResource::error('Unauthorized', 403);
        }

        $recipe->delete();
        return ApiResource::success(null, 'Recipe deleted successfully', 200);
    }
}
