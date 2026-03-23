<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only seed if a superadmin user already exists
        if (User::where('role', 'superadmin')->exists()) {
            return;
        }

        /* ============= SUPER ADMIN ============= */
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'superadmin',
            'status' => 'Active',
        ]);

        /* ============= ADMIN USER ============= */
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'Active',
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

        /* ============= COURSES (Programs) ============= */
        $courses = [];
        $courseData = [
            ['program_code' => 'BSIT', 'program_name' => 'Bachelor of Science in Information Technology', 'desc' => 'Advanced IT program focusing on software development and systems', 'teacher' => 4],
            ['program_code' => 'BEED', 'program_name' => 'Bachelor of Elementary Education', 'desc' => 'Teacher education program for elementary schools', 'teacher' => 2],
            ['program_code' => 'BSAFE', 'program_name' => 'Bachelor of Science in Agriculture and Food Engineering', 'desc' => 'Agriculture and food engineering degree program', 'teacher' => 6],
            ['program_code' => 'BSHM', 'program_name' => 'Bachelor of Science in Hospitality Management', 'desc' => 'Hospitality and tourism management program', 'teacher' => 7],
        ];

        foreach ($courseData as $cData) {
            $course = Course::create([
                'program_code' => $cData['program_code'],
                'course_code' => $cData['program_code'],
                'description' => $cData['desc'],
                'instructor_id' => $teachers[$cData['teacher']]->id,
                'status' => 'Active',
                'credit_hours' => 120,
                'college' => 'College of Education and Technology',
                'department' => ucfirst(strtolower(substr($cData['program_code'], 0, 3))),
            ]);
            $courses[] = $course;
        }

        /* ============= SUBJECTS ============= */
        /* Organized by Program (Course), Year, and Semester */
        $subjects = [];

        // BSIT Subjects
        $bsitSubjects = [
            // First Year - First Semester
            ['code' => 'BSIT101', 'name' => 'Introduction to Programming', 'category' => 'CS Core', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 4],
            ['code' => 'BSIT102', 'name' => 'Computer Fundamentals', 'category' => 'CS Core', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 4],
            ['code' => 'BSIT103', 'name' => 'Mathematics for IT', 'category' => 'General Ed', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 1],
            // First Year - Second Semester
            ['code' => 'BSIT104', 'name' => 'Data Structures', 'category' => 'CS Core', 'credits' => 4, 'year' => 1, 'sem' => '2', 'teacher' => 4],
            ['code' => 'BSIT105', 'name' => 'Database Systems', 'category' => 'CS Core', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 4],
            ['code' => 'BSIT106', 'name' => 'English Communication', 'category' => 'General Ed', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 2],
            // Second Year - First Semester
            ['code' => 'BSIT201', 'name' => 'Web Development I', 'category' => 'CS Core', 'credits' => 4, 'year' => 2, 'sem' => '1', 'teacher' => 4],
            ['code' => 'BSIT202', 'name' => 'Software Engineering', 'category' => 'CS Core', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 4],
            ['code' => 'BSIT203', 'name' => 'Operating Systems', 'category' => 'CS Core', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 0],
            // Second Year - Second Semester
            ['code' => 'BSIT204', 'name' => 'Web Development II', 'category' => 'CS Core', 'credits' => 4, 'year' => 2, 'sem' => '2', 'teacher' => 4],
            ['code' => 'BSIT205', 'name' => 'Network Administration', 'category' => 'CS Core', 'credits' => 3, 'year' => 2, 'sem' => '2', 'teacher' => 0],
        ];

        // BEED Subjects
        $beedSubjects = [
            // First Year - First Semester
            ['code' => 'BEED101', 'name' => 'Foundations of Education', 'category' => 'Education', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 2],
            ['code' => 'BEED102', 'name' => 'Child Development', 'category' => 'Education', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 2],
            ['code' => 'BEED103', 'name' => 'English Language Arts', 'category' => 'Specialization', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 2],
            // First Year - Second Semester
            ['code' => 'BEED104', 'name' => 'Mathematics Education', 'category' => 'Specialization', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 1],
            ['code' => 'BEED105', 'name' => 'Science Education', 'category' => 'Specialization', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 5],
            ['code' => 'BEED106', 'name' => 'Educational Psychology', 'category' => 'Education', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 2],
            // Second Year - First Semester
            ['code' => 'BEED201', 'name' => 'Curriculum Development', 'category' => 'Education', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 2],
            ['code' => 'BEED202', 'name' => 'Teaching Methods', 'category' => 'Education', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 2],
            ['code' => 'BEED203', 'name' => 'Social Studies Teaching', 'category' => 'Specialization', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 6],
            // Second Year - Second Semester
            ['code' => 'BEED204', 'name' => 'Educational Assessment', 'category' => 'Education', 'credits' => 3, 'year' => 2, 'sem' => '2', 'teacher' => 2],
            ['code' => 'BEED205', 'name' => 'Practicum I', 'category' => 'Practicum', 'credits' => 6, 'year' => 2, 'sem' => '2', 'teacher' => 2],
        ];

        // BSAFE Subjects
        $bsafeSubjects = [
            // First Year - First Semester
            ['code' => 'BSAFE101', 'name' => 'Agricultural Science Fundamentals', 'category' => 'Agriculture', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 6],
            ['code' => 'BSAFE102', 'name' => 'Soil Science', 'category' => 'Agriculture', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 6],
            ['code' => 'BSAFE103', 'name' => 'Plant Science', 'category' => 'Agriculture', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 5],
            // First Year - Second Semester
            ['code' => 'BSAFE104', 'name' => 'Farm Machinery', 'category' => 'Engineering', 'credits' => 4, 'year' => 1, 'sem' => '2', 'teacher' => 0],
            ['code' => 'BSAFE105', 'name' => 'Food Science Basics', 'category' => 'Food Engineering', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 3],
            ['code' => 'BSAFE106', 'name' => 'Agricultural Economics', 'category' => 'Management', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 7],
            // Second Year - First Semester
            ['code' => 'BSAFE201', 'name' => 'Horticulture', 'category' => 'Agriculture', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 6],
            ['code' => 'BSAFE202', 'name' => 'Food Preservation', 'category' => 'Food Engineering', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 3],
            ['code' => 'BSAFE203', 'name' => 'Agricultural Engineering Design', 'category' => 'Engineering', 'credits' => 4, 'year' => 2, 'sem' => '1', 'teacher' => 0],
            // Second Year - Second Semester
            ['code' => 'BSAFE204', 'name' => 'Food Quality Management', 'category' => 'Food Engineering', 'credits' => 3, 'year' => 2, 'sem' => '2', 'teacher' => 3],
            ['code' => 'BSAFE205', 'name' => 'AGRribusiness Management', 'category' => 'Management', 'credits' => 3, 'year' => 2, 'sem' => '2', 'teacher' => 7],
        ];

        // BSHM Subjects
        $bshmSubjects = [
            // First Year - First Semester
            ['code' => 'BSHM101', 'name' => 'Hospitality Management Fundamentals', 'category' => 'Hospitality', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 7],
            ['code' => 'BSHM102', 'name' => 'Food and Beverage Service', 'category' => 'Hospitality', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 7],
            ['code' => 'BSHM103', 'name' => 'Front Office Operations', 'category' => 'Hospitality', 'credits' => 3, 'year' => 1, 'sem' => '1', 'teacher' => 7],
            // First Year - Second Semester
            ['code' => 'BSHM104', 'name' => 'Housekeeping Management', 'category' => 'Hospitality', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 7],
            ['code' => 'BSHM105', 'name' => 'Hotel Accounting', 'category' => 'Business', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 1],
            ['code' => 'BSHM106', 'name' => 'Tourism Planning', 'category' => 'Tourism', 'credits' => 3, 'year' => 1, 'sem' => '2', 'teacher' => 7],
            // Second Year - First Semester
            ['code' => 'BSHM201', 'name' => 'Event Management', 'category' => 'Hospitality', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 7],
            ['code' => 'BSHM202', 'name' => 'Restaurant Management', 'category' => 'F&B', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 7],
            ['code' => 'BSHM203', 'name' => 'Hotel Marketing', 'category' => 'Business', 'credits' => 3, 'year' => 2, 'sem' => '1', 'teacher' => 7],
            // Second Year - Second Semester
            ['code' => 'BSHM204', 'name' => 'Destination Management', 'category' => 'Tourism', 'credits' => 3, 'year' => 2, 'sem' => '2', 'teacher' => 7],
            ['code' => 'BSHM205', 'name' => 'Hospitality Internship', 'category' => 'Practicum', 'credits' => 6, 'year' => 2, 'sem' => '2', 'teacher' => 7],
        ];

        // Combine all subjects
        $allSubjects = [
            ['subjects' => $bsitSubjects, 'course_index' => 0],
            ['subjects' => $beedSubjects, 'course_index' => 1],
            ['subjects' => $bsafeSubjects, 'course_index' => 2],
            ['subjects' => $bshmSubjects, 'course_index' => 3],
        ];

        foreach ($allSubjects as $programSubjects) {
            foreach ($programSubjects['subjects'] as $sData) {
                $subject = Subject::create([
                    'subject_code' => $sData['code'],
                    'subject_name' => $sData['name'],
                    'category' => $sData['category'],
                    'semester' => $sData['sem'],
                    'credit_hours' => $sData['credits'],
                    'course_id' => $courses[$programSubjects['course_index']]->id,
                    'instructor_id' => $teachers[$sData['teacher']]->id,
                    'description' => $sData['name'].' - Year '.$sData['year'].' Semester '.$sData['sem'],
                ]);
                $subjects[] = $subject;
            }
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
                'description' => $clData['name'].' - '.($clData['level'] >= 11 ? 'Science Stream' : 'General Stream'),
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
                'phone' => '555'.str_pad($i, 7, '0', STR_PAD_LEFT),
                'password' => bcrypt('password123'),
                'role' => 'student',
            ]);

            $studentModel = Student::create([
                'user_id' => $student->id,
                'student_id' => 'STU'.str_pad($i, 5, '0', STR_PAD_LEFT),
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
            $randomSubjects = is_array($randomKeys) ? array_map(fn ($k) => $subjects[$k], $randomKeys) : [$subjects[$randomKeys]];

            foreach ($randomSubjects as $subject) {
                foreach ($semesters as $semester) {
                    $marksObtained = rand(40, 100);
                    $totalMarks = 100;
                    $percentage = ($marksObtained / $totalMarks) * 100;

                    Grade::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'marks_obtained' => $marksObtained,
                        'total_marks' => $totalMarks,
                        'grade' => $percentage,
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
                $randomClasses = is_array($randomClassKeys) ? array_map(fn ($k) => $classes[$k], $randomClassKeys) : [$classes[$randomClassKeys]];

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
