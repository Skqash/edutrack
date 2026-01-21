<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\SuperAdmin;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Department;
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

        /* ============= DEPARTMENTS ============= */
        $departments = [];
        $departmentData = [
            ['code' => 'CS', 'name' => 'Computer Science', 'desc' => 'Department of Computer Science and Engineering'],
            ['code' => 'ECE', 'name' => 'Electronics & Communication', 'desc' => 'Department of Electronics and Communication Engineering'],
            ['code' => 'ME', 'name' => 'Mechanical Engineering', 'desc' => 'Department of Mechanical Engineering'],
            ['code' => 'CE', 'name' => 'Civil Engineering', 'desc' => 'Department of Civil Engineering'],
            ['code' => 'EE', 'name' => 'Electrical Engineering', 'desc' => 'Department of Electrical Engineering'],
        ];

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

        // Create departments with heads
        foreach ($departmentData as $index => $dData) {
            $dept = Department::create([
                'department_code' => $dData['code'],
                'department_name' => $dData['name'],
                'head_id' => $teachers[$index]->id,
                'description' => $dData['desc'],
                'status' => 'Active',
            ]);
            $departments[] = $dept;
        }

        /* ============= COURSES ============= */
        $courses = [];
        $courseData = [
            ['code' => 'CS101', 'name' => 'Introduction to Programming', 'credits' => 3, 'desc' => 'Learn basic programming concepts and languages', 'teacher' => 0],
            ['code' => 'CS102', 'name' => 'Data Structures', 'credits' => 4, 'desc' => 'Study arrays, linked lists, trees, and graphs', 'teacher' => 4],
            ['code' => 'CS201', 'name' => 'Web Development', 'credits' => 4, 'desc' => 'Frontend and backend web development', 'teacher' => 4],
            ['code' => 'CS301', 'name' => 'Artificial Intelligence', 'credits' => 4, 'desc' => 'Machine learning and AI algorithms', 'teacher' => 4],
            ['code' => 'MATH201', 'name' => 'Calculus I', 'credits' => 4, 'desc' => 'Advanced calculus concepts', 'teacher' => 1],
            ['code' => 'MATH301', 'name' => 'Linear Algebra', 'credits' => 3, 'desc' => 'Matrices and vector spaces', 'teacher' => 1],
            ['code' => 'ENG102', 'name' => 'English Literature', 'credits' => 3, 'desc' => 'Classic and modern literature', 'teacher' => 2],
            ['code' => 'PHY101', 'name' => 'Physics I', 'credits' => 4, 'desc' => 'Mechanics and waves', 'teacher' => 0],
            ['code' => 'CHEM101', 'name' => 'Chemistry I', 'credits' => 4, 'desc' => 'General chemistry principles', 'teacher' => 3],
            ['code' => 'BIO101', 'name' => 'Biology I', 'credits' => 3, 'desc' => 'Cell biology and genetics', 'teacher' => 5],
        ];

        foreach ($courseData as $cData) {
            $course = Course::create([
                'course_code' => $cData['code'],
                'course_name' => $cData['name'],
                'description' => $cData['desc'],
                'instructor_id' => $teachers[$cData['teacher']]->id,
                'status' => 'Active',
                'credit_hours' => $cData['credits'],
            ]);
            $courses[] = $course;
        }

        /* ============= SUBJECTS ============= */
        $subjects = [];
        $subjectData = [
            ['code' => 'SCI101', 'name' => 'Physics I', 'category' => 'Science', 'credits' => 4, 'course' => 0, 'teacher' => 0],
            ['code' => 'MATH101', 'name' => 'Algebra Fundamentals', 'category' => 'Mathematics', 'credits' => 3, 'course' => 4, 'teacher' => 1],
            ['code' => 'ENG101', 'name' => 'English Grammar', 'category' => 'Languages', 'credits' => 3, 'course' => 6, 'teacher' => 2],
            ['code' => 'CS201', 'name' => 'Data Structures', 'category' => 'Computer Science', 'credits' => 4, 'course' => 1, 'teacher' => 4],
            ['code' => 'CS202', 'name' => 'Web Development Basics', 'category' => 'Computer Science', 'credits' => 3, 'course' => 2, 'teacher' => 4],
            ['code' => 'CHEM101', 'name' => 'General Chemistry', 'category' => 'Science', 'credits' => 4, 'course' => 8, 'teacher' => 3],
            ['code' => 'BIO101', 'name' => 'Cell Biology', 'category' => 'Science', 'credits' => 3, 'course' => 9, 'teacher' => 5],
            ['code' => 'HIST101', 'name' => 'World History', 'category' => 'Social Studies', 'credits' => 3, 'course' => 6, 'teacher' => 6],
            ['code' => 'ECO101', 'name' => 'Microeconomics', 'category' => 'Economics', 'credits' => 3, 'course' => 4, 'teacher' => 7],
            ['code' => 'MATH301', 'name' => 'Matrix Theory', 'category' => 'Mathematics', 'credits' => 3, 'course' => 5, 'teacher' => 1],
        ];

        foreach ($subjectData as $sData) {
            $subject = Subject::create([
                'subject_code' => $sData['code'],
                'subject_name' => $sData['name'],
                'category' => $sData['category'],
                'credit_hours' => $sData['credits'],
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
