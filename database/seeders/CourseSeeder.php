<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'course_code' => 'CS101',
                'course_name' => 'Introduction to Programming',
                'credit_hours' => 3,
                'description' => 'Learn basic programming concepts and languages',
            ],
            [
                'course_code' => 'CS102',
                'course_name' => 'Data Structures',
                'credit_hours' => 4,
                'description' => 'Study arrays, linked lists, trees, and graphs',
            ],
            [
                'course_code' => 'CS201',
                'course_name' => 'Web Development',
                'credit_hours' => 4,
                'description' => 'Frontend and backend web development',
            ],
            [
                'course_code' => 'CS202',
                'course_name' => 'Database Management',
                'credit_hours' => 3,
                'description' => 'SQL and NoSQL database systems',
            ],
            [
                'course_code' => 'CS301',
                'course_name' => 'Artificial Intelligence',
                'credit_hours' => 4,
                'description' => 'Machine learning and AI algorithms',
            ],
            [
                'course_code' => 'ECE101',
                'course_name' => 'Circuit Analysis',
                'credit_hours' => 3,
                'description' => 'Basic electrical circuits and analysis',
            ],
            [
                'course_code' => 'ECE102',
                'course_name' => 'Digital Electronics',
                'credit_hours' => 4,
                'description' => 'Digital systems and logic gates',
            ],
            [
                'course_code' => 'ME101',
                'course_name' => 'Mechanics of Materials',
                'credit_hours' => 3,
                'description' => 'Study of stress, strain, and material properties',
            ],
        ];

        // Get teachers
        $teachers = User::where('role', 'teacher')->get();

        foreach ($courses as $index => $course) {
            Course::create([
                'course_code' => $course['course_code'],
                'course_name' => $course['course_name'],
                'instructor_id' => $teachers[$index % count($teachers)]->id,
                'credit_hours' => $course['credit_hours'],
                'description' => $course['description'],
                'status' => 'Active',
            ]);
        }
    }
}
