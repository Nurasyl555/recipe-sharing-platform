<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Ingredient;
use Illuminate\Support\Str;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $user      = User::where('email', 'user@recipe.com')->first();
        $admin     = User::where('email', 'admin@recipe.com')->first();
        $breakfast = Category::where('name', 'Breakfast')->first();
        $dinner    = Category::where('name', 'Dinner')->first();
        $dessert   = Category::where('name', 'Desserts')->first();
        $italian   = Cuisine::where('name', 'Italian')->first();
        $american  = Cuisine::where('name', 'American')->first();
        $kazakh    = Cuisine::where('name', 'Kazakh')->first();

        $recipes = [
            [
                'title'        => 'Classic Spaghetti Carbonara',
                'description'  => 'A creamy Italian pasta dish made with eggs, cheese, pancetta and black pepper.',
                'instructions' => "1. Cook spaghetti in salted boiling water until al dente.\n2. Fry pancetta until crispy.\n3. Mix eggs and pecorino cheese in a bowl.\n4. Drain pasta and toss with pancetta off heat.\n5. Add egg mixture quickly, tossing to coat. Season with black pepper.",
                'prep_time'    => 10,
                'cook_time'    => 20,
                'servings'     => 4,
                'difficulty'   => 'medium',
                'status'       => 'published',
                'user_id'      => $admin->id,
                'category_id'  => $dinner->id,
                'cuisine_id'   => $italian->id,
                'ingredients'  => [
                    ['name' => 'spaghetti',       'amount' => '400g'],
                    ['name' => 'pancetta',         'amount' => '150g'],
                    ['name' => 'eggs',             'amount' => '3 pcs'],
                    ['name' => 'pecorino cheese',  'amount' => '100g'],
                    ['name' => 'black pepper',     'amount' => '1 tsp'],
                ],
            ],
            [
                'title'        => 'Fluffy Pancakes',
                'description'  => 'Light and airy American-style pancakes, perfect for weekend brunch.',
                'instructions' => "1. Mix flour, baking powder, sugar and salt.\n2. Whisk together milk, egg and melted butter.\n3. Combine wet and dry ingredients until just mixed (lumps are ok).\n4. Cook on a greased skillet over medium heat until bubbles form.\n5. Flip and cook another minute. Serve with maple syrup.",
                'prep_time'    => 10,
                'cook_time'    => 15,
                'servings'     => 4,
                'difficulty'   => 'easy',
                'status'       => 'published',
                'user_id'      => $user->id,
                'category_id'  => $breakfast->id,
                'cuisine_id'   => $american->id,
                'ingredients'  => [
                    ['name' => 'flour',         'amount' => '200g'],
                    ['name' => 'baking powder', 'amount' => '2 tsp'],
                    ['name' => 'sugar',         'amount' => '2 tbsp'],
                    ['name' => 'milk',          'amount' => '250ml'],
                    ['name' => 'egg',           'amount' => '1 pc'],
                    ['name' => 'butter',        'amount' => '30g'],
                ],
            ],
            [
                'title'        => 'Beshbarmak',
                'description'  => 'Traditional Kazakh dish with boiled meat and flat noodles in broth.',
                'instructions' => "1. Boil lamb or beef in a large pot with onion and salt for 2 hours.\n2. Remove meat and slice thinly.\n3. Prepare flat noodles (zhaispa) and cook in the broth.\n4. Slice onion into rings and simmer in broth with black pepper.\n5. Layer noodles, then meat, then onion. Serve with warm broth in a bowl.",
                'prep_time'    => 30,
                'cook_time'    => 120,
                'servings'     => 6,
                'difficulty'   => 'hard',
                'status'       => 'published',
                'user_id'      => $admin->id,
                'category_id'  => $dinner->id,
                'cuisine_id'   => $kazakh->id,
                'ingredients'  => [
                    ['name' => 'lamb',   'amount' => '1kg'],
                    ['name' => 'onion',  'amount' => '3 pcs'],
                    ['name' => 'flour',  'amount' => '300g'],
                    ['name' => 'egg',    'amount' => '2 pcs'],
                    ['name' => 'salt',   'amount' => '1 tbsp'],
                    ['name' => 'pepper', 'amount' => '1 tsp'],
                ],
            ],
            [
                'title'        => 'Chocolate Lava Cake',
                'description'  => 'Decadent individual chocolate cakes with a molten center.',
                'instructions' => "1. Melt chocolate and butter together.\n2. Whisk in eggs, yolks and sugar.\n3. Fold in flour.\n4. Pour into greased ramekins and refrigerate 30 minutes.\n5. Bake at 220°C for 12 minutes. Serve immediately.",
                'prep_time'    => 15,
                'cook_time'    => 12,
                'servings'     => 4,
                'difficulty'   => 'medium',
                'status'       => 'published',
                'user_id'      => $user->id,
                'category_id'  => $dessert->id,
                'cuisine_id'   => $american->id,
                'ingredients'  => [
                    ['name' => 'dark chocolate', 'amount' => '200g'],
                    ['name' => 'butter',          'amount' => '100g'],
                    ['name' => 'eggs',            'amount' => '2 pcs'],
                    ['name' => 'egg yolks',       'amount' => '2 pcs'],
                    ['name' => 'sugar',           'amount' => '80g'],
                    ['name' => 'flour',           'amount' => '30g'],
                ],
            ],
        ];

        foreach ($recipes as $data) {
            $ingredientsData = $data['ingredients'];
            unset($data['ingredients']);

            $recipe = Recipe::create($data);

            // Attach ingredients via pivot
            $sync = [];
            foreach ($ingredientsData as $ing) {
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => strtolower(trim($ing['name']))]
                );
                $sync[$ingredient->id] = ['amount' => $ing['amount']];
            }
            $recipe->ingredients()->sync($sync);
        }
    }
}
