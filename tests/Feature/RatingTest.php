<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_average_rating_calculated_correctly()
    {
        $recipe = Recipe::factory()->create();
        Rating::factory(3)->create(['recipe_id' => $recipe->id, 'score' => 5]);
        Rating::factory(1)->create(['recipe_id' => $recipe->id, 'score' => 3]);
        $avgRating = $recipe->ratings()->avg('score');
        $this->assertEquals(4.5, $avgRating);
    }

    public function test_recipe_rating_count()
    {
        $recipe = Recipe::factory()->create();
        Rating::factory(5)->create(['recipe_id' => $recipe->id]);
        $count = $recipe->ratings()->count();
        $this->assertEquals(5, $count);
    }

    public function test_recipe_has_no_ratings_initially()
    {
        $recipe = Recipe::factory()->create();
        $count = $recipe->ratings()->count();
        $this->assertEquals(0, $count);
    }

    public function test_guest_cannot_create_rating()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->postJson('/api/ratings', ['recipe_id' => $recipe->id, 'rating' => 5]);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_rating()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/ratings', [
            'recipe_id' => $recipe->id,
            'rating' => 5,
            'comment' => 'Delicious!',
        ]);
        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'data' => ['id', 'score', 'comment'], 'message'])
                 ->assertJson(['success' => true, 'message' => 'Rating created successfully']);
        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'score' => 5,
        ]);
    }

    public function test_rating_validation_fails_with_invalid_rating()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/ratings', ['recipe_id' => $recipe->id, 'rating' => 10]);
        $response->assertStatus(422)->assertJson(['success' => false]);
    }

    public function test_rating_validation_fails_with_nonexistent_recipe()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/ratings', ['recipe_id' => 999, 'rating' => 5]);
        $response->assertStatus(422)->assertJson(['success' => false]);
    }

    public function test_authenticated_user_can_delete_rating()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        Rating::factory()->create(['user_id' => $user->id, 'recipe_id' => $recipe->id, 'score' => 4]);
        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson("/api/ratings/{$recipe->id}");
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Rating deleted successfully']);
        $this->assertDatabaseMissing('ratings', ['user_id' => $user->id, 'recipe_id' => $recipe->id]);
    }

    public function test_user_cannot_delete_other_users_rating()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $recipe = Recipe::factory()->create();
        Rating::factory()->create(['user_id' => $user1->id, 'recipe_id' => $recipe->id, 'score' => 5]);
        $this->actingAs($user2, 'sanctum');
        $response = $this->deleteJson("/api/ratings/{$recipe->id}");
        $response->assertStatus(404)->assertJson(['success' => false]);
        $this->assertDatabaseHas('ratings', ['user_id' => $user1->id, 'recipe_id' => $recipe->id]);
    }

    public function test_user_cannot_rate_same_recipe_twice()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $this->actingAs($user, 'sanctum');
        $this->postJson('/api/ratings', ['recipe_id' => $recipe->id, 'rating' => 5]);
        $response = $this->postJson('/api/ratings', ['recipe_id' => $recipe->id, 'rating' => 3]);
        $response->assertStatus(422)->assertJson(['success' => false]);
    }
}
