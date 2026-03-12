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

        $sections = ['A', 'B', 'C', 'D', 'E']; // Available sections
        $years = [1, 2, 3, 4]; // Available years
        $currentYear = date('Y'); // Current year for student ID

        foreach ($studentUsers as $index => $user) {
            // Check if student record already exists for this user
            $existingStudent = Student::where('user_id', $user->id)->first();

            if ($existingStudent) {
                // Skip if student record already exists
                continue;
            }

            // Generate student ID in format: YYYY-XXXX-S (e.g., 2022-0233-V)
            // YYYY: Enrollment year
            // XXXX: Sequential 4-digit number
            // S: Single letter section (A, B, C, D, E)
            $sequentialNumber = str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $section = $sections[$index % count($sections)];
            $year = $years[$index % count($years)];

            // Use 2022 as base enrollment year, you can change this
            $enrollmentYear = 2022 + ($index % 3); // Varies between 2022-2024

            $studentId = sprintf('%d-%s-%s', $enrollmentYear, $sequentialNumber, $section);

            // Check if student ID already exists, if so, generate a new one
            while (Student::where('student_id', $studentId)->exists()) {
                $sequentialNumber = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $studentId = sprintf('%d-%s-%s', $enrollmentYear, $sequentialNumber, $section);
            }

            Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,  // Format: 2022-0001-A
                'year' => $year,              // 1, 2, 3, or 4
                'section' => $section,        // A, B, C, D, or E
                'gpa' => rand(250, 400) / 100,
                'status' => 'Active',
            ]);
        }
    }
}
