<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\SuperAdmin;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Classes;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only seed if database is empty
        if (SuperAdmin::exists()) {
            return;
        }

        /* ============= SUPER ADMIN ============= */
        SuperAdmin::create([
            'super_id' => 'SA001',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password123'),
            'contact_number' => '1234567890',
        ]);

        /* ============= ADMIN USER ============= */
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        /* ============= TEACHERS ============= */
        $teachers = [];
        $teacherData = [
            ['name' => 'Dr. Smith', 'email' => 'teacher1@example.com', 'subject' => 'Physics'],
            ['name' => 'Prof. Johnson', 'email' => 'teacher2@example.com', 'subject' => 'Mathematics'],
            ['name' => 'Ms. Williams', 'email' => 'teacher3@example.com', 'subject' => 'English'],
            ['name' => 'Dr. Brown', 'email' => 'teacher4@example.com', 'subject' => 'Chemistry'],
            ['name' => 'Prof. Davis', 'email' => 'teacher5@example.com', 'subject' => 'Computer Science'],
            ['name' => 'Ms. Miller', 'email' => 'teacher6@example.com', 'subject' => 'Biology'],
            ['name' => 'Dr. Wilson', 'email' => 'teacher7@example.com', 'subject' => 'History'],
            ['name' => 'Prof. Moore', 'email' => 'teacher8@example.com', 'subject' => 'Economics'],
        ];

        foreach ($teacherData as $tData) {
            $teacher = User::create([
                'name' => $tData['name'],
                'email' => $tData['email'],
                'password' => bcrypt('password123'),
                'role' => 'teacher',
            ]);
            $teachers[] = $teacher;
        }

        /* ============= COURSES ============= */
        $courses = [];
        $courseData = [
            ['program_code' => 'BSCS', 'program_name' => 'Bachelor of Science in Computer Science', 'department' => 'Computer Science', 'desc' => 'Covers programming, algorithms, data structures, and software engineering.', 'duration' => '4 years', 'max_students' => 50],
            ['program_code' => 'BSIT', 'program_name' => 'Bachelor of Science in Information Technology', 'department' => 'Information Technology', 'desc' => 'Focuses on web development, networking, and database management.', 'duration' => '4 years', 'max_students' => 50],
            ['program_code' => 'BSECE', 'program_name' => 'Bachelor of Science in Electronics Engineering', 'department' => 'Electronics Engineering', 'desc' => 'Covers circuit analysis, digital electronics, and communications.', 'duration' => '5 years', 'max_students' => 40],
            ['program_code' => 'BSME', 'program_name' => 'Bachelor of Science in Mechanical Engineering', 'department' => 'Mechanical Engineering', 'desc' => 'Study of mechanics, thermodynamics, and materials science.', 'duration' => '5 years', 'max_students' => 40],
            ['program_code' => 'BSBA', 'program_name' => 'Bachelor of Science in Business Administration', 'department' => 'Business Administration', 'desc' => 'Covers management, marketing, finance, and entrepreneurship.', 'duration' => '4 years', 'max_students' => 60],
            ['program_code' => 'BSMATH', 'program_name' => 'Bachelor of Science in Mathematics', 'department' => 'Mathematics', 'desc' => 'Advanced mathematics including calculus, algebra, and statistics.', 'duration' => '4 years', 'max_students' => 40],
            ['program_code' => 'BSENG', 'program_name' => 'Bachelor of Arts in English', 'department' => 'Languages', 'desc' => 'English language, literature, and communication.', 'duration' => '4 years', 'max_students' => 45],
            ['program_code' => 'BSPHY', 'program_name' => 'Bachelor of Science in Physics', 'department' => 'Physics', 'desc' => 'Classical and modern physics principles.', 'duration' => '4 years', 'max_students' => 35],
            ['program_code' => 'BSCHEM', 'program_name' => 'Bachelor of Science in Chemistry', 'department' => 'Chemistry', 'desc' => 'General and applied chemistry.', 'duration' => '4 years', 'max_students' => 35],
            ['program_code' => 'BSBIO', 'program_name' => 'Bachelor of Science in Biology', 'department' => 'Biology', 'desc' => 'Cell biology, genetics, and ecology.', 'duration' => '4 years', 'max_students' => 40],
        ];

        foreach ($courseData as $cData) {
            $course = Course::create([
                'program_code' => $cData['program_code'],
                'program_name' => $cData['program_name'],
                'department' => $cData['department'],
                'description' => $cData['desc'],
                'status' => 'Active',
                'duration' => $cData['duration'],
                'max_students' => $cData['max_students'],
            ]);
            $courses[] = $course;
        }

        /* ============= SUBJECTS ============= */
        $subjects = [];
        $subjectData = [
            ['code' => 'SCI101', 'name' => 'Physics I', 'category' => 'Science', 'credit_hours' => 4, 'course' => 7, 'teacher' => 0],
            ['code' => 'MATH101', 'name' => 'Algebra Fundamentals', 'category' => 'Mathematics', 'credit_hours' => 3, 'course' => 5, 'teacher' => 1],
            ['code' => 'ENG101', 'name' => 'English Grammar', 'category' => 'Languages', 'credit_hours' => 3, 'course' => 6, 'teacher' => 2],
            ['code' => 'CS201', 'name' => 'Data Structures', 'category' => 'Computer Science', 'credit_hours' => 4, 'course' => 0, 'teacher' => 4],
            ['code' => 'CS202', 'name' => 'Web Development Basics', 'category' => 'Computer Science', 'credit_hours' => 3, 'course' => 1, 'teacher' => 4],
            ['code' => 'CHEM101', 'name' => 'General Chemistry', 'category' => 'Science', 'credit_hours' => 4, 'course' => 8, 'teacher' => 3],
            ['code' => 'BIO101', 'name' => 'Cell Biology', 'category' => 'Science', 'credit_hours' => 3, 'course' => 9, 'teacher' => 5],
            ['code' => 'HIST101', 'name' => 'World History', 'category' => 'Social Studies', 'credit_hours' => 3, 'course' => 6, 'teacher' => 6],
            ['code' => 'ECO101', 'name' => 'Microeconomics', 'category' => 'Economics', 'credit_hours' => 3, 'course' => 4, 'teacher' => 7],
            ['code' => 'MATH301', 'name' => 'Matrix Theory', 'category' => 'Mathematics', 'credit_hours' => 3, 'course' => 5, 'teacher' => 1],
        ];

        foreach ($subjectData as $sData) {
            $subject = Subject::create([
                'subject_code' => $sData['code'],
                'subject_name' => $sData['name'],
                'category' => $sData['category'],
                'credit_hours' => $sData['credit_hours'],
                'course_id' => $courses[$sData['course']]->id,
                'instructor_id' => $teachers[$sData['teacher']]->id,
                'description' => 'Comprehensive course on ' . $sData['name'],
            ]);
            $subjects[] = $subject;
        }

        /* ============= CLASSES ============= */
        $classes = [];
        $classData = [
            ['name' => 'Class 10-A', 'level' => 10, 'section' => 'A', 'capacity' => 60, 'teacher' => 0],
            ['name' => 'Class 10-B', 'level' => 10, 'section' => 'B', 'capacity' => 60, 'teacher' => 1],
            ['name' => 'Class 11-A', 'level' => 11, 'section' => 'A', 'capacity' => 50, 'teacher' => 2],
            ['name' => 'Class 11-B', 'level' => 11, 'section' => 'B', 'capacity' => 50, 'teacher' => 3],
            ['name' => 'Class 12-A', 'level' => 12, 'section' => 'A', 'capacity' => 45, 'teacher' => 4],
        ];

        foreach ($classData as $clData) {
            $class = ClassModel::create([
                'class_name' => $clData['name'],
                'class_level' => $clData['level'],
                'section' => $clData['section'],
                'capacity' => $clData['capacity'],
                'teacher_id' => $teachers[$clData['teacher']]->id,
                'description' => $clData['name'] . ' - ' . ($clData['level'] >= 11 ? 'Science Stream' : 'General Stream'),
                'status' => 'Active',
            ]);
            $classes[] = $class;
        }

        /* ============= STUDENTS ============= */
        $students = [];
        $firstNames = ['John', 'Jane', 'Michael', 'Emily', 'David', 'Sarah', 'Robert', 'Jessica', 'James', 'Lisa', 
                      'William', 'Mary', 'Richard', 'Patricia', 'Joseph', 'Jennifer', 'Thomas', 'Linda', 'Charles', 'Barbara'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
                     'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];

        for ($i = 1; $i <= 30; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            
            $student = User::create([
                'name' => "$firstName $lastName",
                'email' => "student$i@example.com",
                'phone' => '555' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'password' => bcrypt('password123'),
                'role' => 'student',
            ]);

            $studentModel = Student::create([
                'user_id' => $student->id,
                'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'admission_number' => 'ADM' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'roll_number' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'class_id' => $classes[array_rand($classes)]->id,
                'gpa' => rand(2, 4) + (rand(0, 9) / 10),
                'status' => 'Active',
            ]);
            $students[] = $studentModel;
        }

        /* ============= GRADES ============= */
        $semesters = ['1', '2'];
        $academicYears = ['2023-24', '2024-25', '2025-26'];

        foreach ($students as $student) {
            // Get random subjects from the array
            $randomKeys = array_rand($subjects, min(rand(3, 6), count($subjects)));
            $randomSubjects = is_array($randomKeys) ? array_map(fn($k) => $subjects[$k], $randomKeys) : [$subjects[$randomKeys]];
            
            foreach ($randomSubjects as $subject) {
                foreach ($semesters as $semester) {
                    $marksObtained = rand(40, 100);
                    $totalMarks = 100;
                    $percentage = ($marksObtained / $totalMarks) * 100;

                    // Determine grade
                    if ($percentage >= 90) {
                        $grade = 'A+';
                    } elseif ($percentage >= 80) {
                        $grade = 'A';
                    } elseif ($percentage >= 70) {
                        $grade = 'B';
                    } elseif ($percentage >= 60) {
                        $grade = 'C';
                    } elseif ($percentage >= 50) {
                        $grade = 'D';
                    } else {
                        $grade = 'F';
                    }

                    Grade::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'marks_obtained' => $marksObtained,
                        'total_marks' => $totalMarks,
                        'grade' => $grade,
                        'semester' => $semester,
                        'academic_year' => $academicYears[array_rand($academicYears)],
                    ]);
                }
            }
        }

        /* ============= ATTENDANCE ============= */
        $statuses = ['Present', 'Absent', 'Late', 'Leave'];

        // Create attendance records for the last 20 days
        for ($day = 1; $day <= 20; $day++) {
            $date = Carbon::now()->subDays($day)->toDateString();

            foreach ($students as $student) {
                // Each student attends 3-4 classes per day
                $randomClassKeys = array_rand($classes, min(rand(3, 4), count($classes)));
                $randomClasses = is_array($randomClassKeys) ? array_map(fn($k) => $classes[$k], $randomClassKeys) : [$classes[$randomClassKeys]];
                
                foreach ($randomClasses as $class) {
                    Attendance::create([
                        'student_id' => $student->id,
                        'class_id' => $class->id,
                        'date' => $date,
                        'status' => $statuses[array_rand($statuses)],
                        'notes' => rand(0, 2) ? 'Regular attendance' : null,
                    ]);
                }
            }
        }

        echo "✓ Database seeded successfully with comprehensive sample data!\n";
    }
}
