<?php

namespace Database\Factories;

use App\Models\AttendanceSignature;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceSignature>
 */
class AttendanceSignatureFactory extends Factory
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
            'class_id' => ClassModel::factory(),
            'teacher_id' => User::factory(['role' => 'teacher']),
            'term' => fake()->randomElement(['midterm', 'final', 'general']),
            'signature_type' => fake()->randomElement(['digital', 'upload', 'pen-based']),
            'signature_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
            'signature_filename' => fake()->fileName('storage'),
            'signature_mime_type' => 'image/png',
            'signature_size' => 150,
            'signed_date' => now(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => 'Mozilla/5.0',
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'archived']),
            'is_verified' => fake()->boolean(),
            'approved_by' => fake()->randomElement([null, User::factory()]),
            'approved_at' => fake()->boolean() ? now() : null,
        ];
    }
}
