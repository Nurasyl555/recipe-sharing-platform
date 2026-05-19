<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_add_favorite()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->postJson('/api/favorites', ['recipe_id' => $recipe->id]);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_add_favorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/favorites', ['recipe_id' => $recipe->id]);
        $response->assertStatus(201)->assertJson(['success' => true, 'message' => 'Recipe added to favorites']);
        $this->assertDatabaseHas('favorites', ['user_id' => $user->id, 'recipe_id' => $recipe->id]);
    }

    public function test_authenticated_user_can_remove_favorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        Favorite::factory()->create(['user_id' => $user->id, 'recipe_id' => $recipe->id]);
        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson("/api/favorites/{$recipe->id}");
        $response->assertStatus(200)->assertJson(['success' => true, 'message' => 'Recipe removed from favorites']);
        $this->assertDatabaseMissing('favorites', ['user_id' => $user->id, 'recipe_id' => $recipe->id]);
    }

    public function test_user_cannot_add_nonexistent_recipe_to_favorites()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/favorites', ['recipe_id' => 999]);
        $response->assertStatus(422);
    }

    public function test_user_cannot_add_same_recipe_twice_to_favorites()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $this->actingAs($user, 'sanctum');
        $this->postJson('/api/favorites', ['recipe_id' => $recipe->id]);
        $response = $this->postJson('/api/favorites', ['recipe_id' => $recipe->id]);
        $response->assertStatus(422);
    }
}