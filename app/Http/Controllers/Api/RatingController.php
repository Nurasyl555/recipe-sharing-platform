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
                    new OA\Property(property: "comment", type: "string", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Rating created successfully"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store()
    {
        try {
            $validated = request()->validate([
                'recipe_id' => ['required', 'integer', 'exists:recipes,id'],
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'comment' => ['nullable', 'string'],
            ]);

            $rating = Rating::create($validated + ['user_id' => auth()->id()]);
            
            return ApiResource::success(
                new RatingResource($rating),
                'Rating created successfully',
                201
            );
        } catch (ValidationException $e) {
            return ApiResource::error(
                'Validation failed',
                422,
                $e->errors()
            );
        } catch (Exception $e) {
            // Ловим ошибки БД (например UNIQUE constraint)
            return ApiResource::error(
                'You have already rated this recipe',
                422
            );
        }
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
            new OA\Response(response: 200, description: "Rating deleted successfully"),
            new OA\Response(response: 404, description: "Rating not found")
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
