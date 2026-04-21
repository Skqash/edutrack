<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\School;
use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
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
            'class_id' => ClassModel::factory(),
            'student_id' => strtoupper(fake()->unique()->bothify('STU####')),
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->email(),
        ];
    }
}
