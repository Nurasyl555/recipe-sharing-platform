<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuisine;
use Illuminate\Support\Str;

class CuisineSeeder extends Seeder
{
    public function run(): void
    {
        $cuisines = [
            'Italian',
            'Asian',
            'Mexican',
            'French',
            'American',
            'Mediterranean',
            'Indian',
            'Japanese',
            'Middle Eastern',
            'Kazakh',
        ];

        foreach ($cuisines as $name) {
            Cuisine::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
