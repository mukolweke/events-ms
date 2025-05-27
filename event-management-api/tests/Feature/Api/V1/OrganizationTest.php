<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\User;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganizationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $organization;
    protected $user;
    protected $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->organization = Organization::factory()->create();
        $this->user = User::factory()->create([
            'organization_id' => $this->organization->id
        ]);
        $this->event = Event::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user);
    }

    public function test_cannot_access_other_organization_attendees()
    {
        $otherOrg = Organization::factory()->create();
        $otherEvent = Event::factory()->create(['organization_id' => $otherOrg->id]);
        Attendee::factory()->count(2)->create([
            'event_id' => $otherEvent->id
        ]);
        Attendee::factory()->count(3)->create([
            'event_id' => $this->event->id
        ]);
        $response = $this->getJson("/api/{$this->organization->slug}/events/{$this->event->id}/attendees");
        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_organization_events_are_isolated()
    {
        $otherOrg = Organization::factory()->create();
        $otherEvent = Event::factory()->create(['organization_id' => $otherOrg->id]);
        Event::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $response = $this->getJson("/api/{$this->organization->slug}/events");
        $response->assertOk()->assertJsonCount(4, 'data'); // 3 new events + 1 from setUp
    }

    public function test_organization_users_are_isolated()
    {
        $otherOrg = Organization::factory()->create();
        $otherUser = User::factory()->create(['organization_id' => $otherOrg->id]);

        // Create an event as the other user
        $this->actingAs($otherUser);
        $otherEvent = Event::factory()->create([
            'organization_id' => $otherOrg->id
        ]);

        // Switch back to our test user
        $this->actingAs($this->user);

        // Try to access the other user's event
        $response = $this->getJson("/api/{$this->organization->slug}/events/{$otherEvent->id}");
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Event not found'
            ]);

        // Verify we can only see our own events
        $response = $this->getJson("/api/{$this->organization->slug}/events");
        $response->assertOk()
            ->assertJsonCount(1, 'data'); // Only the event from setUp
    }

    public function test_organization_attendees_are_isolated()
    {
        $otherOrg = Organization::factory()->create();
        $otherEvent = Event::factory()->create(['organization_id' => $otherOrg->id]);
        Attendee::factory()->count(2)->create(['event_id' => $otherEvent->id]);
        Attendee::factory()->count(3)->create(['event_id' => $this->event->id]);
        $response = $this->getJson("/api/{$this->organization->slug}/events/{$this->event->id}/attendees");
        $response->assertOk()->assertJsonCount(3, 'data');
    }
}
