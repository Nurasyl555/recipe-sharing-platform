<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,    // Person 1 — creates admin + test user
            CategorySeeder::class, // Person 2 — creates recipe categories
            CuisineSeeder::class,  // Person 2 — creates cuisines
            RecipeSeeder::class,   // Person 2 — creates sample recipes with ingredients

            // Person 3 will add:
            // RatingSeeder::class,
            // FavoriteSeeder::class,
        ]);
    }
}
