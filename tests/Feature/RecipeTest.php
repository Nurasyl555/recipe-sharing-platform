<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_published_recipes()
    {
        Recipe::factory(5)->create(['status' => 'published']);
        Recipe::factory(2)->create(['status' => 'draft']);
        $response = $this->getJson('/api/recipes');
        $response->assertStatus(200)->assertJsonStructure(['success', 'data', 'message']);
    }

    public function test_guest_can_view_single_recipe()
    {
        $recipe = Recipe::factory()->create(['status' => 'published']);
        $response = $this->getJson("/api/recipes/{$recipe->id}");
        $response->assertStatus(200)->assertJsonStructure(['success', 'data', 'message']);
    }

    public function test_authenticated_user_can_create_recipe()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $cuisine = Cuisine::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/recipes', [
            'title' => 'Test Recipe',
            'description' => 'A test recipe description here',
            'instructions' => 'Step 1: Do this. Step 2: Do that. Step 3: Done.',
            'prep_time' => 10,
            'cook_time' => 20,
            'servings' => 4,
            'difficulty' => 'easy',
            'category_id' => $category->id,
            'cuisine_id' => $cuisine->id,
            'ingredients' => ['Pasta', 'Sauce'],
            'amounts' => ['500g', '300ml'],
        ]);
        $response->assertStatus(201);
    }

    public function test_user_can_update_own_recipe()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user, 'sanctum');
        $response = $this->putJson("/api/recipes/{$recipe->id}", [
            'title' => 'Updated',
            'description' => $recipe->description,
            'instructions' => $recipe->instructions,
            'prep_time' => $recipe->prep_time,
            'cook_time' => $recipe->cook_time,
            'servings' => $recipe->servings,
            'difficulty' => $recipe->difficulty,
            'category_id' => $recipe->category_id,
            'cuisine_id' => $recipe->cuisine_id,
            'ingredients' => ['Test'],
            'amounts' => ['100g'],
        ]);
        $response->assertStatus(200);
    }

    public function test_user_cannot_update_other_users_recipe()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $recipe = Recipe::factory()->create(['user_id' => $user1->id]);
        $this->actingAs($user2, 'sanctum');
        $response = $this->putJson("/api/recipes/{$recipe->id}", ['title' => 'Hacked']);
        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_recipe()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson("/api/recipes/{$recipe->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_guest_cannot_create_recipe()
    {
        $response = $this->postJson('/api/recipes', ['title' => 'Test']);
        $response->assertStatus(401);
    }
}