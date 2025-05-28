<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;
use App\Models\Attendee;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Force SQLite for testing
        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Run migrations
        // $this->artisan('migrate:fresh --seed');

        $this->withoutVite();
    }

    protected function createOrganization(array $attributes = []): Organization
    {
        return Organization::factory()->create($attributes);
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function createEvent(Organization $organization, array $attributes = []): Event
    {
        return Event::factory()->create(array_merge([
            'organization_id' => $organization->id
        ], $attributes));
    }

    protected function actingAsUser(User $user)
    {
        Sanctum::actingAs($user);
        return $this;
    }

    protected function createAttendee(array $attributes = []): Attendee
    {
        return Attendee::factory()->create($attributes);
    }
}
