<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\Rating;
use Tests\TestCase;

class RatingTest extends TestCase
{
    public function test_recipe_average_rating_calculated_correctly()
    {
        $recipe = Recipe::factory()->create();

        Rating::factory(3)->create([
            'recipe_id' => $recipe->id,
            'rating' => 5,
        ]);

        Rating::factory(1)->create([
            'recipe_id' => $recipe->id,
            'rating' => 3,
        ]);

        $avgRating = $recipe->ratings()->avg('rating');
        $this->assertEquals(4.5, $avgRating);
    }

    public function test_recipe_rating_count()
    {
        $recipe = Recipe::factory()->create();

        Rating::factory(5)->create([
            'recipe_id' => $recipe->id,
        ]);

        $count = $recipe->ratings()->count();
        $this->assertEquals(5, $count);
    }

    public function test_recipe_has_no_ratings_initially()
    {
        $recipe = Recipe::factory()->create();

        $count = $recipe->ratings()->count();
        $this->assertEquals(0, $count);
    }
}