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
        $response = $this->postJson('/api/favorites', [
            'recipe_id' => $recipe->id,
        ]);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_add_favorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/favorites', [
            'recipe_id' => $recipe->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'data', 'message'])
                 ->assertJson(['success' => true, 'message' => 'Recipe added to favorites successfully']);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_authenticated_user_can_delete_favorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        
        // Создаём избранное правильно
        \App\Models\Favorite::create([
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);

        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson("/api/favorites/{$recipe->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data', 'message'])
                 ->assertJson(['success' => true, 'message' => 'Recipe removed from favorites successfully']);

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
        
        \App\Models\Favorite::create([
            'user_id' => $user1->id,
            'recipe_id' => $recipe->id,
        ]);

        $this->actingAs($user2, 'sanctum');
        $response = $this->deleteJson("/api/favorites/{$recipe->id}");

        $response->assertStatus(404)
                 ->assertJson(['success' => false]);

        // Favorite user1 должен остаться
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user1->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_favorite_validation_fails_with_nonexistent_recipe()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/favorites', [
            'recipe_id' => 999,
        ]);

        $response->assertStatus(422)
                 ->assertJson(['success' => false]);
    }

    public function test_duplicate_favorite_is_not_created()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Первый раз добавляем
        $this->postJson('/api/favorites', ['recipe_id' => $recipe->id]);
        
        // Второй раз добавляем же рецепт
        $response = $this->postJson('/api/favorites', ['recipe_id' => $recipe->id]);

        $response->assertStatus(201);
        
        // Но в БД только одна запись
        $this->assertEquals(1, \App\Models\Favorite::where('user_id', $user->id)->count());
    }
}