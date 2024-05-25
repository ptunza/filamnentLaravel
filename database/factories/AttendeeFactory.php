<?php

namespace Database\Factories;

use App\Models\Conference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendee>
 */
class AttendeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'conference_id' => Conference::factory(),
            'ticket_cost' => 5000,
            'is_paid' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now')
        ];
    }
}
