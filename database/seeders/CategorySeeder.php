<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Breakfast',  'description' => 'Morning meals and brunch ideas'],
            ['name' => 'Lunch',      'description' => 'Midday meals'],
            ['name' => 'Dinner',     'description' => 'Evening main courses'],
            ['name' => 'Desserts',   'description' => 'Sweet treats and pastries'],
            ['name' => 'Snacks',     'description' => 'Light bites and appetizers'],
            ['name' => 'Soups',      'description' => 'Warm soups and stews'],
            ['name' => 'Salads',     'description' => 'Fresh salads and dressings'],
            ['name' => 'Beverages',  'description' => 'Drinks, smoothies and juices'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
            ]);
        }
    }
}
