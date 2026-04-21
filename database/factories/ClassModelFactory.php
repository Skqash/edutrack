<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassModel>
 */
class ClassModelFactory extends Factory
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
            'subject_id' => Subject::factory(),
            'teacher_id' => User::factory(['role' => 'teacher']),
            'class_name' => fake()->word() . ' ' . fake()->numerify('###'),
            'class_level' => fake()->randomElement([1, 2, 3, 4]),
            'section' => fake()->randomElement(['A', 'B', 'C', 'D']),
        ];
    }
}
