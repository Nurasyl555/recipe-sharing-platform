<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
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
            new OA\Response(
                response: 200,
                description: "Recipes list retrieved",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "array", items: new OA\Items()),
                        new OA\Property(property: "total", type: "integer"),
                        new OA\Property(property: "per_page", type: "integer")
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        return Recipe::paginate(15);
    }

    #[OA\Post(
        path: "/api/recipes",
        operationId: "storeRecipe",
        tags: ["Recipes"],
        summary: "Create new recipe",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "description", "category_id"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Pasta Carbonara"),
                    new OA\Property(property: "description", type: "string"),
                    new OA\Property(property: "category_id", type: "integer"),
                    new OA\Property(property: "cuisine_id", type: "integer"),
                    new OA\Property(property: "ingredients", type: "array", items: new OA\Items())
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Recipe created"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(StoreRecipeRequest $request)
    {
        $recipe = Recipe::create($request->validated());
        return response()->json($recipe, 201);
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
        return response()->json($recipe);
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
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent()
        ),
        responses: [
            new OA\Response(response: 200, description: "Recipe updated"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 404, description: "Recipe not found")
        ]
    )]
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $recipe->update($request->validated());
        return response()->json($recipe);
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
            new OA\Response(response: 204, description: "Recipe deleted"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 404, description: "Recipe not found")
        ]
    )]
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return response()->noContent();
    }
}
