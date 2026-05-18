<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(name="Rating", description="Rating operations")
 */
class RatingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/ratings",
     *     summary="Create or update rating",
     *     description="Create a new rating or update existing one for a recipe",
     *     tags={"Rating"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"recipe_id","rating"},
     *             @OA\Property(property="recipe_id", type="integer", example=1),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
     *             @OA\Property(property="comment", type="string", nullable=true, example="Delicious recipe!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rating saved",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Rating"),
     *             @OA\Property(property="message", type="string", example="Rating saved")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'recipe_id' => ['required', 'exists:recipes,id'],
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'comment' => ['nullable', 'string', 'max:1000'],
            ]);
        } catch (ValidationException $e) {
            return ApiResource::error('Validation failed', 422, $e->errors());
        }

        $rating = Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'recipe_id' => $data['recipe_id'],
            ],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );

        $rating->load('user');

        return ApiResource::success(
            new RatingResource($rating),
            'Rating saved',
            200
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/ratings/{recipeId}",
     *     summary="Delete rating",
     *     description="Delete user's rating for a recipe",
     *     tags={"Rating"},
     *     @OA\Parameter(
     *         name="recipeId",
     *         in="path",
     *         required=true,
     *         description="Recipe ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rating removed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Rating removed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($recipeId)
    {
        Rating::where('user_id', Auth::id())
            ->where('recipe_id', $recipeId)
            ->delete();

        return ApiResource::success(null, 'Rating removed', 200);
    }
}
