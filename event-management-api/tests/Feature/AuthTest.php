<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create([
            'organization_id' => $organization->id,
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson("/api/{$organization->slug}/login", [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create([
            'organization_id' => $organization->id,
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson("/api/{$organization->slug}/login", [
            'email' => $user->email,
            'password' => 'WrongPassword123!',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    public function test_user_cannot_access_organization_without_belonging_to_it()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create([
            'organization_id' => $organization->id,
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson("/api/{$organization->slug}/login", [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $token = $response->json('access_token');

        $otherOrganization = Organization::factory()->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/{$otherOrganization->slug}/profile");

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized access']);
    }
}
