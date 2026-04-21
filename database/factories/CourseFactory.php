<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_code' => $this->faker->unique()->bothify('????##'),
            'program_name' => $this->faker->words(3, true),
            'total_years' => $this->faker->numberBetween(2, 4),
            'description' => $this->faker->sentence(),
            'status' => 'active',
            'school_id' => 1,
        ];
    }
}
