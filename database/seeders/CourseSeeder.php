<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'program_code' => 'BSCS',
                'program_name' => 'Bachelor of Science in Computer Science',
                'department' => 'Computer Science',
                'description' => 'Covers programming, algorithms, data structures, and software engineering.',
                'status' => 'Active',
                'duration' => '4 years',
                'max_students' => 50,
            ],
            [
                'program_code' => 'BSIT',
                'program_name' => 'Bachelor of Science in Information Technology',
                'department' => 'Information Technology',
                'description' => 'Focuses on web development, networking, and database management.',
                'status' => 'Active',
                'duration' => '4 years',
                'max_students' => 50,
            ],
            [
                'program_code' => 'BSECE',
                'program_name' => 'Bachelor of Science in Electronics Engineering',
                'department' => 'Electronics Engineering',
                'description' => 'Covers circuit analysis, digital electronics, and communications.',
                'status' => 'Active',
                'duration' => '5 years',
                'max_students' => 40,
            ],
            [
                'program_code' => 'BSME',
                'program_name' => 'Bachelor of Science in Mechanical Engineering',
                'department' => 'Mechanical Engineering',
                'description' => 'Study of mechanics, thermodynamics, and materials science.',
                'status' => 'Active',
                'duration' => '5 years',
                'max_students' => 40,
            ],
            [
                'program_code' => 'BSBA',
                'program_name' => 'Bachelor of Science in Business Administration',
                'department' => 'Business Administration',
                'description' => 'Covers management, marketing, finance, and entrepreneurship.',
                'status' => 'Active',
                'duration' => '4 years',
                'max_students' => 60,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
