<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\Organization;
use App\Models\Event;
use Illuminate\Foundation\Testing\WithFaker;

class PublicEventControllerTest extends TestCase
{
    use WithFaker;

    public function test_can_list_public_events_for_organization()
    {
        $organization = $this->createOrganization();
        $events = Event::factory()->count(3)->create([
            'organization_id' => $organization->id,
            'is_public' => true
        ]);

        $response = $this->getJson("/api/{$organization->slug}/events/public");

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'venue',
                        'event_date',
                        'price',
                        'max_attendees',
                        'is_active',
                        'status'
                    ]
                ]
            ]);
    }

    public function test_can_show_public_event()
    {
        $organization = $this->createOrganization();
        $event = $this->createEvent($organization, ['is_public' => true]);

        // Debug: Verify event is created correctly
        $this->assertTrue($event->is_public, 'Event should be public');
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'is_public' => true
        ]);

        $response = $this->getJson("/api/{$organization->slug}/events/public/{$event->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'venue',
                    'event_date',
                    'price',
                    'max_attendees',
                    'is_active',
                    'status',
                    'attendees'
                ]
            ]);
    }

    public function test_can_register_for_public_event()
    {
        $organization = $this->createOrganization();
        $event = $this->createEvent($organization, [
            'is_public' => true,
            'max_attendees' => 100
        ]);

        $registrationData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber
        ];

        $response = $this->postJson("/api/{$organization->slug}/events/public/{$event->id}/register", $registrationData);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'venue',
                    'event_date',
                    'price',
                    'max_attendees',
                    'is_active',
                    'status'
                ]
            ]);

        $this->assertDatabaseHas('attendees', [
            'event_id' => $event->id,
            'email' => $registrationData['email']
        ]);
    }

    public function test_cannot_register_for_full_event()
    {
        $organization = $this->createOrganization();
        $event = $this->createEvent($organization, [
            'is_public' => true,
            'max_attendees' => 1
        ]);

        // Register first attendee
        $this->postJson("/api/{$organization->slug}/events/public/{$event->id}/register", [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber
        ]);

        // Try to register second attendee
        $response = $this->postJson("/api/{$organization->slug}/events/public/{$event->id}/register", [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber
        ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Event is at full capacity']);
    }
}
