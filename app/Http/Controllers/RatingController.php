<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
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

    public function destroy($recipeId)
    {
        Rating::where('user_id', Auth::id())
            ->where('recipe_id', $recipeId)
            ->delete();

        return ApiResource::success(null, 'Rating removed', 200);
    }
}
