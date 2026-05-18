<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use OpenApi\Attributes as OA;

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
            new OA\Response(response: 201, description: "Added to favorites"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store()
    {
        $favorite = Favorite::create([
            'user_id' => auth()->id(),
            'recipe_id' => request()->input('recipe_id')
        ]);
        
        return response()->json($favorite, 201);
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
            new OA\Response(response: 204, description: "Removed from favorites"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 404, description: "Favorite not found")
        ]
    )]
    public function destroy(int $recipeId)
    {
        Favorite::where('recipe_id', $recipeId)
            ->where('user_id', auth()->id())
            ->delete();
        
        return response()->noContent();
    }
}
