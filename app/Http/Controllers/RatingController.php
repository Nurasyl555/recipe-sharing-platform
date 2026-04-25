<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'recipe_id' => $request->recipe_id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        return response()->json([
            'message' => 'Rating saved',
            'rating' => $rating
        ]);
    }

    public function destroy($recipeId)
    {
        Rating::where('user_id', Auth::id())
            ->where('recipe_id', $recipeId)
            ->delete();

        return response()->json([
            'message' => 'Rating removed'
        ]);
    }
}
