<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RatingController extends Controller
{
    #[OA\Post(
        path: "/api/ratings",
        operationId: "storeRating",
        tags: ["Ratings"],
        summary: "Rate a recipe",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["recipe_id", "rating"],
                properties: [
                    new OA\Property(property: "recipe_id", type: "integer"),
                    new OA\Property(property: "rating", type: "integer", minimum: 1, maximum: 5),
                    new OA\Property(property: "review", type: "string", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Rating created"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipe_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string'],
        ]);

        $rating = Rating::create($validated + ['user_id' => auth()->id()]);
        return response()->json($rating, 201);
    }

    #[OA\Delete(
        path: "/api/ratings/{recipeId}",
        operationId: "destroyRating",
        tags: ["Ratings"],
        summary: "Delete rating",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "recipeId", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 204, description: "Rating deleted"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 404, description: "Rating not found")
        ]
    )]
    public function destroy(int $recipeId)
    {
        Rating::where('recipe_id', $recipeId)
            ->where('user_id', auth()->id())
            ->delete();
        
        return response()->noContent();
    }
}
