<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(name="Favorite", description="Favorite operations")
 */
class FavoriteController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/favorites/{recipeId}",
     *     summary="Add recipe to favorites",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="recipeId",
     *         in="path",
     *         required=true,
     *         description="Recipe ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Added to favorites",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Added to favorites")
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
    public function store($recipeId)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'recipe_id' => $recipeId,
        ]);

        return ApiResource::success(null, 'Added to favorites', 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/favorites/{recipeId}",
     *     summary="Remove recipe from favorites",
     *     tags={"Favorite"},
     *     @OA\Parameter(
     *         name="recipeId",
     *         in="path",
     *         required=true,
     *         description="Recipe ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Removed from favorites",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Removed from favorites")
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
        Favorite::where('user_id', Auth::id())
            ->where('recipe_id', $recipeId)
            ->delete();

        return ApiResource::success(null, 'Removed from favorites', 200);
    }
}
