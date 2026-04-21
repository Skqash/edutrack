<?php

namespace Database\Factories;

use App\Models\GradingScaleSetting;
use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GradingScaleSetting>
 */
class GradingScaleSettingFactory extends Factory
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
            'grading_mode' => fake()->randomElement(['standard', 'manual', 'automated', 'hybrid']),
            'is_locked' => false,
            'hybrid_config' => json_encode([]),
            'output_format' => 'standard',
        ];
    }
}
