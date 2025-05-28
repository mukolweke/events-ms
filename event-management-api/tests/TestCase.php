<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createOrganization(array $attributes = []): Organization
    {
        return Organization::factory()->create($attributes);
    }

    protected function createUser(Organization $organization, array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'organization_id' => $organization->id
        ], $attributes));
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

    protected function createAttendee(Event $event, array $attributes = [])
    {
        return $event->attendees()->create($attributes);
    }
}
