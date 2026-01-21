<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Course;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'subject_code' => 'CS101-SUB1',
                'subject_name' => 'Python Fundamentals',
                'credits' => 3,
                'description' => 'Basic Python programming',
                'course_code' => 'CS101',
            ],
            [
                'subject_code' => 'CS102-SUB1',
                'subject_name' => 'Arrays and Lists',
                'credits' => 2,
                'description' => 'Array and list data structures',
                'course_code' => 'CS102',
            ],
            [
                'subject_code' => 'CS102-SUB2',
                'subject_name' => 'Trees and Graphs',
                'credits' => 2,
                'description' => 'Tree and graph algorithms',
                'course_code' => 'CS102',
            ],
            [
                'subject_code' => 'CS201-SUB1',
                'subject_name' => 'HTML and CSS',
                'credits' => 2,
                'description' => 'Web markup and styling',
                'course_code' => 'CS201',
            ],
            [
                'subject_code' => 'CS201-SUB2',
                'subject_name' => 'JavaScript',
                'credits' => 2,
                'description' => 'JavaScript programming for web',
                'course_code' => 'CS201',
            ],
            [
                'subject_code' => 'CS202-SUB1',
                'subject_name' => 'SQL Basics',
                'credits' => 2,
                'description' => 'SQL queries and database design',
                'course_code' => 'CS202',
            ],
            [
                'subject_code' => 'CS301-SUB1',
                'subject_name' => 'Machine Learning',
                'credits' => 3,
                'description' => 'ML algorithms and applications',
                'course_code' => 'CS301',
            ],
            [
                'subject_code' => 'ECE101-SUB1',
                'subject_name' => 'AC Circuits',
                'credits' => 3,
                'description' => 'Alternating current circuits',
                'course_code' => 'ECE101',
            ],
            [
                'subject_code' => 'ECE102-SUB1',
                'subject_name' => 'Logic Gates',
                'credits' => 2,
                'description' => 'Digital logic and gates',
                'course_code' => 'ECE102',
            ],
            [
                'subject_code' => 'ME101-SUB1',
                'subject_name' => 'Stress Analysis',
                'credits' => 3,
                'description' => 'Stress and strain calculations',
                'course_code' => 'ME101',
            ],
        ];

        foreach ($subjects as $subject) {
            $course = Course::where('course_code', $subject['course_code'])->first();
            
            Subject::create([
                'subject_code' => $subject['subject_code'],
                'subject_name' => $subject['subject_name'],
                'course_id' => $course->id ?? null,
                'credits' => $subject['credits'],
                'description' => $subject['description'],
                'status' => 'Active',
            ]);
        }
    }
}
