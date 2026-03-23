<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\ClassModel;
use App\Models\Student;

class TeacherTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create a teacher user
        $teacher = User::where('email', 'teacher@example.com')->first();
        
        if (!$teacher) {
            $teacher = User::create([
                'name' => 'John Teacher',
                'email' => 'teacher@example.com',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'status' => 'active',
            ]);
        }

        // Create some test courses directly (they are now programs)
        $courses = [
            [
                'program_name' => 'Bachelor of Science in Information Technology',
                'program_code' => 'BSIT',
                'course_code' => 'BSIT',
                'description' => 'Information Technology Program',
                'status' => 'Active',
            ],
            [
                'program_name' => 'Bachelor of Science in Computer Science', 
                'program_code' => 'BSCS',
                'course_code' => 'BSCS',
                'description' => 'Computer Science Program',
                'status' => 'Active',
            ],
        ];

        $createdCourses = [];
        foreach ($courses as $courseData) {
            $course = Course::firstOrCreate(
                ['program_code' => $courseData['program_code']],
                $courseData
            );
            $createdCourses[] = $course;
        }

        // Create some test subjects
        $subjects = [
            [
                'subject_name' => 'Introduction to Programming',
                'subject_code' => 'IT101',
                'credit_hours' => 3,
                'category' => 'Programming',
                'description' => 'Basic programming concepts and logic',
                'year_level' => 1,
                'semester' => '1',
                'program_id' => $createdCourses[0]->id,
            ],
            [
                'subject_name' => 'Web Development Fundamentals',
                'subject_code' => 'IT201',
                'credit_hours' => 3,
                'category' => 'Programming',
                'description' => 'HTML, CSS, and JavaScript basics',
                'year_level' => 2,
                'semester' => '1',
                'program_id' => $createdCourses[0]->id,
            ],
            [
                'subject_name' => 'Database Management Systems',
                'subject_code' => 'IT301',
                'credit_hours' => 3,
                'category' => 'Core',
                'description' => 'Database design and SQL',
                'year_level' => 3,
                'semester' => '1',
                'program_id' => $createdCourses[0]->id,
            ],
            [
                'subject_name' => 'Independent Study - Mobile Development',
                'subject_code' => 'IND101',
                'credit_hours' => 3,
                'category' => 'Specialization',
                'description' => 'Mobile app development using modern frameworks',
                'year_level' => 3,
                'semester' => '2',
                'program_id' => null, // Independent subject
            ],
        ];

        $createdSubjects = [];
        foreach ($subjects as $subjectData) {
            $subject = Subject::firstOrCreate(
                ['subject_code' => $subjectData['subject_code']],
                $subjectData
            );
            $createdSubjects[] = $subject;
        }

        // Assign subjects to the teacher
        foreach ($createdSubjects as $subject) {
            // Check if already assigned
            if (!$subject->teachers()->where('teacher_id', $teacher->id)->exists()) {
                $subject->teachers()->attach($teacher->id, [
                    'status' => 'active',
                    'assigned_at' => now(),
                ]);
            }
        }

        // Create some test classes
        $classes = [
            [
                'class_name' => 'BSIT-1A',
                'year_level' => 1,
                'section' => 'A',
                'total_students' => 35,
                'teacher_id' => $teacher->id,
                'course_id' => $createdCourses[0]->id,
                'subject_id' => $createdSubjects[0]->id,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'description' => 'First year IT students, Section A',
            ],
            [
                'class_name' => 'BSIT-2A',
                'year_level' => 2,
                'section' => 'A',
                'total_students' => 32,
                'teacher_id' => $teacher->id,
                'course_id' => $createdCourses[0]->id,
                'subject_id' => $createdSubjects[1]->id,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'description' => 'Second year IT students, Section A',
            ],
            [
                'class_name' => 'Mobile Dev Class',
                'year_level' => 3,
                'section' => 'A',
                'total_students' => 15,
                'teacher_id' => $teacher->id,
                'course_id' => null, // Independent class
                'subject_id' => $createdSubjects[3]->id,
                'academic_year' => '2024-2025',
                'semester' => 'Second',
                'status' => 'Active',
                'description' => 'Independent mobile development class',
            ],
        ];

        foreach ($classes as $classData) {
            ClassModel::firstOrCreate(
                [
                    'class_name' => $classData['class_name'],
                    'teacher_id' => $teacher->id,
                ],
                $classData
            );
        }

        // Create some test students
        $studentNames = [
            'Alice Johnson', 'Bob Smith', 'Carol Davis', 'David Wilson', 'Emma Brown',
            'Frank Miller', 'Grace Lee', 'Henry Taylor', 'Ivy Chen', 'Jack Anderson',
            'Kate Thompson', 'Liam Garcia', 'Mia Rodriguez', 'Noah Martinez', 'Olivia Lopez',
        ];

        $createdClasses = ClassModel::where('teacher_id', $teacher->id)->get();
        
        foreach ($createdClasses as $class) {
            // Create students for this class
            $studentsToCreate = min(count($studentNames), $class->total_students);
            
            for ($i = 0; $i < $studentsToCreate; $i++) {
                $name = $studentNames[$i];
                $email = strtolower(str_replace(' ', '.', $name)) . '@student.edu';
                
                // Create user if doesn't exist
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'password' => bcrypt('password'),
                        'role' => 'student',
                        'status' => 'active',
                    ]
                );

                // Create student record if doesn't exist
                $studentId = date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '-' . $class->section;
                
                Student::firstOrCreate(
                    ['student_id' => $studentId],
                    [
                        'user_id' => $user->id,
                        'class_id' => $class->id,
                        'year_level' => $class->year_level,
                        'section' => $class->section,
                        'status' => 'Active',
                    ]
                );
            }
        }

        $this->command->info('Teacher test data seeded successfully!');
        $this->command->info('Teacher login: teacher@example.com / password');
        $this->command->info('Created ' . count($createdSubjects) . ' subjects');
        $this->command->info('Created ' . count($classes) . ' classes');
        $this->command->info('Created students for each class');
    }
}