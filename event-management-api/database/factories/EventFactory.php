<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('+1 days', '+1 year');
        $endDate = (clone $startDate)->modify('+2 hours');

        return [
            'organization_id' => Organization::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'venue' => $this->faker->address(),
            'event_date' => $startDate,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => $this->faker->randomFloat(2, 0, 500),
            'max_attendees' => $this->faker->numberBetween(10, 200),
            'is_active' => true,
            'is_public' => false,
            'status' => 'active',
        ];
    }
}
