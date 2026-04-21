<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'subject_name' => fake()->word(),
            'subject_code' => strtoupper(fake()->unique()->bothify('???###')),
            'category' => fake()->randomElement(['Core', 'Elective', 'GE', 'Specialization']),
            'credit_hours' => fake()->numberBetween(1, 5),
            'year_level' => fake()->randomElement([1, 2, 3, 4]),
            'description' => fake()->sentence(),
        ];
    }
}
