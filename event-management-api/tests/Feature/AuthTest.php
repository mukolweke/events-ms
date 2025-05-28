<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\WithFaker;

class AuthTest extends TestCase
{
    use WithFaker;

    public function test_user_can_login_with_valid_credentials()
    {
        $organization = $this->createOrganization();
        $user = $this->createUser([
            'password' => 'Password123!'
        ]);

        $response = $this->postJson("/api/login", [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                    ]
                ]
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $organization = $this->createOrganization();
        $user = $this->createUser($organization, [
            'password' => 'Password123!'
        ]);

        $response = $this->postJson("/api/login", [
            'email' => $user->email,
            'password' => 'WrongPassword123!',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    // public function test_user_cannot_access_organization_without_belonging_to_it()
    // {
    //     $organization = $this->createOrganization();
    //     $user = $this->createUser($organization);
    //     $token = $user->createToken('test-token')->plainTextToken;

    //     $otherOrganization = $this->createOrganization();
    //     $response = $this->withHeader('Authorization', 'Bearer ' . $token)
    //         ->getJson("/api/profile");

    //     $response->assertStatus(403)
    //         ->assertJson(['message' => 'Unauthorized access']);
    // }
}
