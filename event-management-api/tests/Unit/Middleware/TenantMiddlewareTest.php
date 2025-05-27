<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

class TenantMiddlewareTest extends TestCase
{
    use WithFaker;

    protected $organization;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->organization = $this->createOrganization();
        $this->user = $this->createUser($this->organization);
    }

    public function test_organization_slug_is_required()
    {
        $this->actingAsUser($this->user);

        $response = $this->getJson('/api/test-tenant');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Organization not found.']);
    }

    public function test_invalid_organization_slug_returns_404()
    {
        $this->actingAsUser($this->user);

        $response = $this->getJson('/api/invalid-org-slug/test-middleware');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Organization not found.']);
    }

    public function test_cannot_access_other_organization()
    {
        $this->actingAsUser($this->user);

        // Create another organization
        $otherOrg = Organization::factory()->create();

        $response = $this->getJson("/api/{$otherOrg->slug}/test-middleware");

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized access']);
    }

    public function test_can_access_own_organization()
    {
        $this->actingAsUser($this->user);

        $response = $this->getJson("/api/{$this->organization->slug}/test-middleware");

        $response->assertOk()
            ->assertJson(['message' => 'Middleware passed']);
    }
}
