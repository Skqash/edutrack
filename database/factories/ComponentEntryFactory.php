<?php

namespace Database\Factories;

use App\Models\ComponentEntry;
use App\Models\Student;
use App\Models\AssessmentComponent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComponentEntry>
 */
class ComponentEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'component_id' => AssessmentComponent::factory(),
            'score' => fake()->numberBetween(0, 100),
            'remarks' => fake()->optional()->sentence(),
            'entry_type' => fake()->randomElement(['manual', 'automated']),
            'entered_by' => fake()->numerify('user_###'),
        ];
    }
}
