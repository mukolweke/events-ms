<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Foundation\Testing\WithFaker;

class AttendeeControllerTest extends TestCase
{
    use WithFaker;

    protected $organization;
    protected $user;
    protected $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->organization = $this->createOrganization();
        $this->user = $this->createUser($this->organization);
        $this->event = $this->createEvent($this->organization);
    }

    public function test_can_list_event_attendees()
    {
        $this->actingAsUser($this->user);

        // Load the event's organization relationship
        $this->event->load('organization');

        // Create attendees using the helper to ensure correct relationship
        $this->createAttendee($this->event, [
            'user_id' => $this->user->id,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber,
            'registered_at' => now()
        ]);

        $this->createAttendee($this->event, [
            'user_id' => $this->user->id,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber,
            'registered_at' => now()
        ]);

        $this->createAttendee($this->event, [
            'user_id' => $this->user->id,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber,
            'registered_at' => now()
        ]);

        // dd(DB::table('attendees')->get(), $this->event->id);

        $response = $this->getJson("/api/{$this->organization->slug}/events/".$this->event->id ."/attendees");

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'status',
                        'event_id'
                    ]
                ]
            ]);
    }

    public function test_can_register_for_event()
    {
        $this->actingAsUser($this->user);
        $registrationData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber
        ];

        $response = $this->postJson(
            "/api/{$this->organization->slug}/events/{$this->event->id}/register",
            $registrationData
        );

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'event_id',
                    'user_id',
                    'registration_date',
                    'status',
                    'user',
                    'event'
                ]
            ]);

        $this->assertDatabaseHas('attendees', [
            'event_id' => $this->event->id,
            'user_id' => $this->user->id
        ]);
    }

    public function test_can_cancel_registration()
    {
        $this->actingAsUser($this->user);
        $attendee = Attendee::factory()->create([
            'event_id' => $this->event->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->deleteJson("/api/{$this->organization->slug}/events/{$this->event->id}/cancel");

        $response->assertNoContent();
        $this->assertDatabaseMissing('attendees', [
            'id' => $attendee->id
        ]);
    }

    public function test_can_export_attendees()
    {
        $this->actingAsUser($this->user);
        $attendees = Attendee::factory()->count(3)->create([
            'event_id' => $this->event->id
        ]);

        $response = $this->getJson("/api/{$this->organization->slug}/events/{$this->event->id}/attendees/export");

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertHeader('Content-Disposition', 'attachment; filename="attendees.csv"');
    }

    public function test_cannot_register_twice_for_same_event()
    {
        $this->actingAsUser($this->user);

        // First registration
        $this->postJson("/api/{$this->organization->slug}/events/{$this->event->id}/register", [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber
        ]);

        // Try to register again
        $response = $this->postJson("/api/{$this->organization->slug}/events/{$this->event->id}/register", [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber
        ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'User is already registered for this event']);
    }

    public function test_can_list_attendees_for_organization()
    {
        $this->actingAsUser($this->user);

        // Load the event's organization relationship
        $this->event->load('organization');

        Attendee::factory()->count(3)->create([
            'event_id' => $this->event->id
        ]);

        $response = $this->getJson("/api/{$this->organization->slug}/attendees");

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'status',
                        'event_id'
                    ]
                ]
            ]);
    }

    public function test_cannot_access_other_organization_attendees()
    {
        $this->actingAsUser($this->user);

        // Create another organization and its event
        $otherOrg = Organization::factory()->create();
        $otherEvent = Event::factory()->create(['organization_id' => $otherOrg->id]);

        // Create attendees for the other organization
        Attendee::factory()->count(2)->create([
            'event_id' => $otherEvent->id
        ]);

        // Create attendees for current organization
        Attendee::factory()->count(3)->create([
            'event_id' => $this->event->id
        ]);

        // Try to access the other organization's attendees
        $response = $this->getJson("/api/{$otherOrg->slug}/attendees");

        // Should get 403 because we don't have access to this organization
        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized access']);

        // Verify we can still access our own organization's attendees
        $response = $this->getJson("/api/{$this->organization->slug}/attendees");
        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }
}
