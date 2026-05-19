<?php

namespace App\Http\Controllers\Api;

use App\Models\Rating;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Http\Resources\RatingResource;
use OpenApi\Attributes as OA;
use Illuminate\Validation\ValidationException;
use Exception;

class RatingController extends Controller
{
    #[OA\Post(
        path: "/ratings",
        operationId: "storeRating",
        tags: ["Ratings"],
        summary: "Rate a recipe",
        description: "Add or update a rating for a recipe",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Rating data",
            content: new OA\JsonContent(
                required: ["recipe_id", "rating"],
                properties: [
                    new OA\Property(property: "recipe_id", type: "integer", example: 1, description: "Recipe ID"),
                    new OA\Property(property: "rating", type: "integer", minimum: 1, maximum: 5, example: 5, description: "Rating score (1-5)"),
                    new OA\Property(property: "comment", type: "string", nullable: true, example: "Delicious!", maxLength: 1000, description: "Optional comment"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Rating created successfully"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error or duplicate rating"),
        ]
    )]
    public function store()
    {
        try {
            $validated = request()->validate([
                'recipe_id' => ['required', 'integer', 'exists:recipes,id'],
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'comment' => ['nullable', 'string', 'max:1000'],
            ]);

            $data = [
                'recipe_id' => $validated['recipe_id'],
                'score' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'user_id' => auth()->id()
            ];

            $rating = Rating::create($data);
            
            return ApiResource::success(
                new RatingResource($rating),
                'Rating created successfully',
                201
            );
        } catch (ValidationException $e) {
            return ApiResource::error('Validation failed', 422, $e->errors());
        } catch (Exception $e) {
            return ApiResource::error('You have already rated this recipe', 422);
        }
    }

    #[OA\Delete(
        path: "/ratings/{recipeId}",
        operationId: "destroyRating",
        tags: ["Ratings"],
        summary: "Delete rating",
        description: "Remove a rating for a recipe",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "recipeId", in: "path", required: true, description: "Recipe ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Rating deleted successfully"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 404, description: "Rating not found"),
        ]
    )]
    public function destroy(int $recipeId)
    {
        $deleted = Rating::where('recipe_id', $recipeId)
            ->where('user_id', auth()->id())
            ->delete();

        if (!$deleted) {
            return ApiResource::error('Rating not found', 404);
        }

        return ApiResource::success(null, 'Rating deleted successfully', 200);
    }
}
