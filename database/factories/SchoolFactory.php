<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_name' => fake()->company(),
            'school_code' => strtoupper(fake()->unique()->bothify('??##')),
            'short_name' => strtoupper(fake()->bothify('??')),
            'campus_type' => fake()->randomElement(['main', 'satellite']),
            'location' => fake()->city(),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'region' => 'Region VI - Western Visayas',
            'contact_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->email(),
            'description' => fake()->sentence(),
            'status' => 'Active',
            'established_date' => fake()->date(),
        ];
    }
}

