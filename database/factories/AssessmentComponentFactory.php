<?php

namespace Database\Factories;

use App\Models\AssessmentComponent;
use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssessmentComponent>
 */
class AssessmentComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_id' => ClassModel::factory(),
            'name' => fake()->word(),
            'category' => fake()->randomElement(['Knowledge', 'Skills', 'Attitude', 'Custom']),
            'max_score' => fake()->numberBetween(10, 100),
            'weight' => fake()->numberBetween(1, 100),
            'sort_order' => fake()->numberBetween(1, 20),
            'entry_mode' => fake()->randomElement(['manual', 'automated']),
            'is_active' => true,
        ];
    }
}
