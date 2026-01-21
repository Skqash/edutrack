<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all student users
        $studentUsers = User::where('role', 'student')->get();

        foreach ($studentUsers as $index => $user) {
            Student::create([
                'user_id' => $user->id,
                'student_id' => 'STU' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'roll_number' => str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'gpa' => rand(250, 400) / 100,
                'status' => 'Active',
            ]);
        }
    }
}
