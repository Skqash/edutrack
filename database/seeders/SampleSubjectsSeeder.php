<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Course;

class SampleSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        
        $subjects = [
            // IT Subjects
            [
                'subject_code' => 'IT101',
                'subject_name' => 'Introduction to Programming',
                'category' => 'Programming',
                'credit_hours' => 3,
                'description' => 'Learn fundamental programming concepts and problem-solving techniques using modern programming languages',
                'type' => 'Core',
                'program' => 'Bachelor of Science in Information Technology',
                'course_id' => null, // Will be set below
                'instructor_id' => 1
            ],
            [
                'subject_code' => 'IT102',
                'subject_name' => 'Web Development Fundamentals',
                'category' => 'Programming',
                'credit_hours' => 3,
                'description' => 'Introduction to HTML, CSS, and JavaScript for creating modern web applications',
                'type' => 'Core',
                'program' => 'Bachelor of Science in Information Technology',
                'course_id' => null,
                'instructor_id' => 1
            ],
            [
                'subject_code' => 'CS201',
                'subject_name' => 'Data Structures and Algorithms',
                'category' => 'Programming',
                'credit_hours' => 4,
                'description' => 'Study of fundamental data structures and algorithmic problem-solving techniques',
                'type' => 'Core',
                'program' => 'Bachelor of Science in Computer Science',
                'course_id' => null,
                'instructor_id' => 1
            ],
            // Education Subjects
            [
                'subject_code' => 'ED101',
                'subject_name' => 'Principles of Teaching',
                'category' => 'Education',
                'credit_hours' => 3,
                'description' => 'Fundamental principles and methods of effective teaching in elementary education',
                'type' => 'Core',
                'program' => 'Bachelor of Elementary Education',
                'course_id' => null,
                'instructor_id' => 1
            ],
            [
                'subject_code' => 'ED102',
                'subject_name' => 'Child Development',
                'category' => 'Education',
                'credit_hours' => 3,
                'description' => 'Understanding child psychology and developmental stages for effective teaching',
                'type' => 'Core',
                'program' => 'Bachelor of Elementary Education',
                'course_id' => null,
                'instructor_id' => 1
            ],
            // Agriculture Subjects
            [
                'subject_code' => 'AGR101',
                'subject_name' => 'Introduction to Agriculture',
                'category' => 'Agriculture',
                'credit_hours' => 3,
                'description' => 'Overview of modern agricultural practices and sustainable farming techniques',
                'type' => 'Core',
                'program' => 'Bachelor of Science in Agriculture',
                'course_id' => null,
                'instructor_id' => 1
            ],
            [
                'subject_code' => 'AGR201',
                'subject_name' => 'Crop Science',
                'category' => 'Agriculture',
                'credit_hours' => 4,
                'description' => 'Study of crop production, soil management, and agricultural sustainability',
                'type' => 'Core',
                'program' => 'Bachelor of Science in Agriculture',
                'course_id' => null,
                'instructor_id' => 1
            ],
            // Business Subjects
            [
                'subject_code' => 'BUS101',
                'subject_name' => 'Principles of Management',
                'category' => 'Business',
                'credit_hours' => 3,
                'description' => 'Fundamental concepts of business management and organizational leadership',
                'type' => 'Core',
                'program' => 'Bachelor of Science in Business Administration',
                'course_id' => null,
                'instructor_id' => 1
            ],
            // General Education
            [
                'subject_code' => 'GEN101',
                'subject_name' => 'Mathematics in the Modern World',
                'category' => 'Mathematics',
                'credit_hours' => 3,
                'description' => 'Application of mathematical concepts in real-world scenarios and problem-solving',
                'type' => 'General',
                'program' => 'General Education',
                'course_id' => null,
                'instructor_id' => 1
            ],
            [
                'subject_code' => 'GEN102',
                'subject_name' => 'Purposive Communication',
                'category' => 'Languages',
                'credit_hours' => 3,
                'description' => 'Development of effective communication skills for academic and professional contexts',
                'type' => 'General',
                'program' => 'General Education',
                'course_id' => null,
                'instructor_id' => 1
            ]
        ];

        // Assign course_id based on program
        foreach ($subjects as &$subject) {
            $course = $courses->where('program_name', $subject['program'])->first();
            if ($course) {
                $subject['course_id'] = $course->id;
            }
        }

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('Sample subjects created successfully!');
    }
}
