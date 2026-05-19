<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use OpenApi\Attributes as OA;
use Illuminate\Validation\ValidationException;
use Exception;

class FavoriteController extends Controller
{
    #[OA\Post(
        path: "/favorites",
        operationId: "storeFavorite",
        tags: ["Favorites"],
        summary: "Add recipe to favorites",
        description: "Add a recipe to user's favorite list",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Favorite data",
            content: new OA\JsonContent(
                required: ["recipe_id"],
                properties: [
                    new OA\Property(property: "recipe_id", type: "integer", example: 1, description: "Recipe ID"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Recipe added to favorites"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error or already favorited"),
        ]
    )]
    public function store()
    {
        try {
            $validated = request()->validate([
                'recipe_id' => ['required', 'integer', 'exists:recipes,id'],
            ]);

            $favorite = Favorite::create([
                'recipe_id' => $validated['recipe_id'],
                'user_id' => auth()->id()
            ]);

            return ApiResource::success(
                $favorite,
                'Recipe added to favorites',
                201
            );
        } catch (ValidationException $e) {
            return ApiResource::error('Validation failed', 422, $e->errors());
        } catch (Exception $e) {
            return ApiResource::error('Recipe is already in favorites', 422);
        }
    }

    #[OA\Delete(
        path: "/favorites/{recipeId}",
        operationId: "destroyFavorite",
        tags: ["Favorites"],
        summary: "Remove recipe from favorites",
        description: "Remove a recipe from user's favorite list",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "recipeId", in: "path", required: true, description: "Recipe ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Recipe removed from favorites"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 404, description: "Favorite not found"),
        ]
    )]
    public function destroy(int $recipeId)
    {
        $deleted = Favorite::where('recipe_id', $recipeId)
            ->where('user_id', auth()->id())
            ->delete();

        if (!$deleted) {
            return ApiResource::error('Favorite not found', 404);
        }

        return ApiResource::success(null, 'Recipe removed from favorites', 200);
    }
}
