<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
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

        return ApiResource::success(null, 'Added to favorites', 200);
    }

    public function destroy($recipeId)
    {
        Favorite::where('user_id', Auth::id())
            ->where('recipe_id', $recipeId)
            ->delete();

        return ApiResource::success(null, 'Removed from favorites', 200);
    }
}
