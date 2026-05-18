<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'instructions' => fake()->paragraph(),
            'prep_time' => fake()->numberBetween(5, 60),
            'cook_time' => fake()->numberBetween(5, 120),
            'servings' => fake()->numberBetween(1, 8),
            'difficulty' => 'easy',
            'status' => 'published',
            'user_id' => User::factory(),
        ];
    }
}