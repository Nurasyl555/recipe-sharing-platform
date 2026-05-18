<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    public function test_guest_cannot_add_favorite()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->postJson("/api/favorites/{$recipe->id}");
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_add_favorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/favorites/{$recipe->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data', 'message'])
                 ->assertJson(['success' => true, 'message' => 'Added to favorites']);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_authenticated_user_can_delete_favorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $user->favorites()->attach($recipe->id);

        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson("/api/favorites/{$recipe->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data', 'message'])
                 ->assertJson(['success' => true, 'message' => 'Removed from favorites']);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_favorite()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $user1->favorites()->attach($recipe->id);

        $this->actingAs($user2, 'sanctum');
        $this->deleteJson("/api/favorites/{$recipe->id}");

        // Favorite user1 должен остаться
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user1->id,
            'recipe_id' => $recipe->id,
        ]);
    }
}