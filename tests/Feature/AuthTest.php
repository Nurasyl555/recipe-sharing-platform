<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);
        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'data' => ['id', 'name', 'email'], 'message']);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data' => ['token', 'user' => ['id', 'name', 'email']], 'message']);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);
        $response->assertStatus(401)->assertJsonStructure(['success', 'message']);
    }
}