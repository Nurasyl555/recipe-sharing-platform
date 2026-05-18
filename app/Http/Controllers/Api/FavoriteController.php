<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use OpenApi\Attributes as OA;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    #[OA\Post(
        path: "/api/favorites",
        operationId: "storeFavorite",
        tags: ["Favorites"],
        summary: "Add recipe to favorites",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["recipe_id"],
                properties: [
                    new OA\Property(property: "recipe_id", type: "integer")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Added to favorites successfully"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store()
    {
        try {
            $validated = request()->validate([
                'recipe_id' => ['required', 'integer', 'exists:recipes,id'],
            ]);

            $favorite = Favorite::firstOrCreate([
                'user_id' => auth()->id(),
                'recipe_id' => $validated['recipe_id']
            ]);

            return ApiResource::success(
                $favorite,
                'Recipe added to favorites successfully',
                201
            );
        } catch (ValidationException $e) {
            return ApiResource::error(
                'Validation failed',
                422,
                $e->errors()
            );
        }
    }

    #[OA\Delete(
        path: "/api/favorites/{recipeId}",
        operationId: "destroyFavorite",
        tags: ["Favorites"],
        summary: "Remove recipe from favorites",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "recipeId", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Removed from favorites successfully"),
            new OA\Response(response: 404, description: "Favorite not found")
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

        return ApiResource::success(null, 'Recipe removed from favorites successfully', 200);
    }
}
