<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store($recipeId)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'recipe_id' => $recipeId,
        ]);

        return response()->json([
            'message' => 'Added to favorites'
        ]);
    }

    public function destroy($recipeId)
    {
        Favorite::where('user_id', Auth::id())
            ->where('recipe_id', $recipeId)
            ->delete();

        return response()->json([
            'message' => 'Removed from favorites'
        ]);
    }
}
