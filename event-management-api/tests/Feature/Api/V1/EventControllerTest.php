<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\WithFaker;

class EventControllerTest extends TestCase
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

    public function test_can_list_events()
    {
        $this->actingAsUser($this->user);
        Event::factory()->count(3)->create([
            'organization_id' => $this->organization->id
        ]);

        $response = $this->getJson("/api/{$this->organization->slug}/events");
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

    public function test_can_create_event()
    {
        $this->actingAsUser($this->user);
        $eventData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'venue' => $this->faker->address,
            'event_date' => now()->addDays(7)->toDateTimeString(),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'max_attendees' => $this->faker->numberBetween(10, 100),
            'is_active' => true,
            'organization_id' => $this->organization->id,
            'start_date' => now()->addDays(7)->toDateTimeString(),
            'end_date' => now()->addDays(8)->toDateTimeString(),
            'location' => $this->faker->address,
            'capacity' => $this->faker->numberBetween(10, 100)
        ];

        $response = $this->postJson("/api/{$this->organization->slug}/events", $eventData);
        $response->assertCreated()
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

        $this->assertDatabaseHas('events', [
            'title' => $eventData['title'],
            'organization_id' => $this->organization->id
        ]);
    }

    public function test_can_update_event()
    {
        $this->actingAsUser($this->user);
        $event = $this->createEvent($this->organization);
        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'venue' => 'Updated Venue',
            'event_date' => now()->addDays(14)->toDateTimeString(),
            'price' => 50.00,
            'max_attendees' => 50,
            'is_active' => true,
            'organization_id' => $this->organization->id,
            'start_date' => now()->addDays(14)->toDateTimeString(),
            'end_date' => now()->addDays(15)->toDateTimeString(),
            'location' => 'Updated Location',
            'capacity' => 50
        ];

        $response = $this->putJson("/api/{$this->organization->slug}/events/{$event->id}", $updateData);
        $response->assertOk()
            ->assertJson([
                'data' => [
                    'title' => 'Updated Title',
                    'description' => 'Updated Description'
                ]
            ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_can_delete_event()
    {
        $this->actingAsUser($this->user);
        $event = $this->createEvent($this->organization);

        $response = $this->deleteJson("/api/{$this->organization->slug}/events/{$event->id}");
        $response->assertNoContent();

        // Verify the event is soft deleted
        $this->assertSoftDeleted('events', ['id' => $event->id]);

        // Verify we can't find it in normal queries
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
            'deleted_at' => null
        ]);
    }

    public function test_can_restore_deleted_event()
    {
        $this->actingAsUser($this->user);
        $event = $this->createEvent($this->organization);
        $event->delete();

        $response = $this->postJson("/api/{$this->organization->slug}/events/{$event->id}/restore");
        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $event->id,
                    'title' => $event->title
                ]
            ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => null
        ]);
    }

    public function test_can_list_past_events()
    {
        $this->actingAsUser($this->user);
        Event::factory()->count(3)->create([
            'organization_id' => $this->organization->id,
            'end_date' => now()->subDays(7)
        ]);

        $response = $this->getJson("/api/{$this->organization->slug}/events/past");
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
}
