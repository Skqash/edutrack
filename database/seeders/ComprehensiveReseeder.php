<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Models\College;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ComprehensiveReseeder extends Seeder
{
    /**
     * Run the comprehensive reseeder with accurate table structures
     */
    public function run(): void
    {
        $this->command->info('🔄 Starting Comprehensive Reseeder...');

        // Create foundational data first
        $this->createCollegesAndDepartments();
        $this->createAdminUsers();
        $this->createTeachers();
        $this->createCourses();
        $this->createSubjects();
        $this->createStudents();
        $this->createClasses();
        $this->createCourseAccessRequests();
        
        $this->command->info('✅ Comprehensive Reseeder completed successfully!');
        $this->printSummary();
    }

    private function createCollegesAndDepartments(): void
    {
        $this->command->info('🏛️ Creating colleges and departments...');

        // Create Colleges
        $colleges = [
            ['name' => 'College of Computer Studies', 'desc' => 'Information Technology and Computer Science programs'],
            ['name' => 'College of Education', 'desc' => 'Teacher education and pedagogy programs'],
            ['name' => 'College of Agriculture', 'desc' => 'Agricultural and food engineering programs'],
            ['name' => 'College of Business', 'desc' => 'Business and hospitality management programs'],
        ];

        $collegeModels = [];
        foreach ($colleges as $college) {
            $collegeModels[] = College::updateOrCreate(
                ['college_name' => $college['name']],
                ['description' => $college['desc']]
            );
        }

        // Create Departments
        $departments = [
            ['name' => 'Information Technology', 'college_index' => 0],
            ['name' => 'Computer Science', 'college_index' => 0],
            ['name' => 'Elementary Education', 'college_index' => 1],
            ['name' => 'Secondary Education', 'college_index' => 1],
            ['name' => 'Agricultural Engineering', 'college_index' => 2],
            ['name' => 'Food Science', 'college_index' => 2],
            ['name' => 'Business Administration', 'college_index' => 3],
            ['name' => 'Hospitality Management', 'college_index' => 3],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['department_name' => $dept['name']],
                [
                    'college_id' => $collegeModels[$dept['college_index']]->id,
                    'description' => $dept['name'] . ' Department'
                ]
            );
        }
    }
    private function createAdminUsers(): void
    {
        $this->command->info('👤 Creating admin users...');

        // Super Admin
        User::updateOrCreate(
            ['email' => 'super@cpsu.edu.ph'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('super123'),
                'role' => 'super',
                'campus' => null,
                'campus_status' => 'approved',
                'status' => 'Active',
            ]
        );

        // Main Campus Admin
        User::updateOrCreate(
            ['email' => 'admin@cpsu.edu.ph'],
            [
                'name' => 'CPSU Main Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'campus' => 'CPSU Main Campus',
                'campus_status' => 'approved',
                'campus_approved_at' => now(),
                'status' => 'Active',
            ]
        );

        // Bayambang Campus Admin
        User::updateOrCreate(
            ['email' => 'admin.bayambang@cpsu.edu.ph'],
            [
                'name' => 'CPSU Bayambang Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'campus' => 'CPSU Bayambang Campus',
                'campus_status' => 'approved',
                'campus_approved_at' => now(),
                'status' => 'Active',
            ]
        );
    }

    private function createTeachers(): void
    {
        $this->command->info('👨‍🏫 Creating teachers...');

        // CPSU Main Campus Teachers
        $mainTeachers = [
            ['name' => 'Dr. Maria Santos', 'email' => 'maria.santos@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Prof. Juan Dela Cruz', 'email' => 'juan.delacruz@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Dr. Ana Reyes', 'email' => 'ana.reyes@cpsu.edu.ph', 'status' => 'pending'],
        ];

        foreach ($mainTeachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => 'CPSU Main Campus',
                    'campus_status' => $teacher['status'],
                    'campus_approved_at' => $teacher['status'] === 'approved' ? now() : null,
                    'campus_approved_by' => $teacher['status'] === 'approved' ? 2 : null,
                    'status' => 'Active',
                ]
            );
        }

        // CPSU Bayambang Campus Teachers
        $bayTeachers = [
            ['name' => 'Prof. Roberto Garcia', 'email' => 'roberto.garcia@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Dr. Carmen Lopez', 'email' => 'carmen.lopez@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Prof. Miguel Torres', 'email' => 'miguel.torres@cpsu.edu.ph', 'status' => 'rejected'],
        ];

        foreach ($bayTeachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => 'CPSU Bayambang Campus',
                    'campus_status' => $teacher['status'],
                    'campus_approved_at' => $teacher['status'] === 'approved' ? now() : null,
                    'campus_approved_by' => $teacher['status'] === 'approved' ? 3 : null,
                    'status' => 'Active',
                ]
            );
        }

        // Independent Teachers
        $independentTeachers = [
            ['name' => 'John Smith', 'email' => 'john.smith@gmail.com'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@yahoo.com'],
            ['name' => 'Michael Brown', 'email' => 'michael.brown@outlook.com'],
        ];

        foreach ($independentTeachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => null,
                    'campus_status' => 'approved',
                    'status' => 'Active',
                ]
            );
        }
    }
    private function createCourses(): void
    {
        $this->command->info('🎓 Creating courses...');

        // Get departments for proper foreign key relationships
        $itDept = Department::where('department_name', 'Information Technology')->first();
        $csDept = Department::where('department_name', 'Computer Science')->first();
        $elemDept = Department::where('department_name', 'Elementary Education')->first();
        $agriDept = Department::where('department_name', 'Agricultural Engineering')->first();
        $busDept = Department::where('department_name', 'Business Administration')->first();
        $hospDept = Department::where('department_name', 'Hospitality Management')->first();

        // CPSU Main Campus Courses
        $mainCourses = [
            [
                'program_code' => 'BSIT-MAIN',
                'program_name' => 'Bachelor of Science in Information Technology',
                'department_id' => $itDept?->id,
                'campus' => 'CPSU Main Campus',
                'description' => 'CPSU Main Campus IT Program'
            ],
            [
                'program_code' => 'BSCS-MAIN',
                'program_name' => 'Bachelor of Science in Computer Science',
                'department_id' => $csDept?->id,
                'campus' => 'CPSU Main Campus',
                'description' => 'CPSU Main Campus CS Program'
            ],
        ];

        // CPSU Bayambang Campus Courses
        $bayambangCourses = [
            [
                'program_code' => 'BSIT-BAY',
                'program_name' => 'Bachelor of Science in Information Technology',
                'department_id' => $itDept?->id,
                'campus' => 'CPSU Bayambang Campus',
                'description' => 'CPSU Bayambang Campus IT Program'
            ],
            [
                'program_code' => 'BEED-BAY',
                'program_name' => 'Bachelor of Elementary Education',
                'department_id' => $elemDept?->id,
                'campus' => 'CPSU Bayambang Campus',
                'description' => 'CPSU Bayambang Campus Education Program'
            ],
        ];

        // Independent Courses
        $independentCourses = [
            [
                'program_code' => 'WDB-IND',
                'program_name' => 'Web Development Bootcamp',
                'department_id' => null,
                'campus' => null,
                'description' => 'Independent Web Development Course'
            ],
            [
                'program_code' => 'DMC-IND',
                'program_name' => 'Digital Marketing Course',
                'department_id' => null,
                'campus' => null,
                'description' => 'Independent Digital Marketing Course'
            ],
        ];

        foreach (array_merge($mainCourses, $bayambangCourses, $independentCourses) as $course) {
            Course::updateOrCreate(
                ['program_code' => $course['program_code']],
                [
                    'program_name' => $course['program_name'],
                    'course_code' => $course['program_code'],
                    'department_id' => $course['department_id'],
                    'campus' => $course['campus'],
                    'description' => $course['description'],
                    'total_years' => 4,
                    'status' => 'Active',
                ]
            );
        }
    }

    private function createSubjects(): void
    {
        $this->command->info('📚 Creating subjects...');

        // Get courses for proper relationships
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bscsMain = Course::where('program_code', 'BSCS-MAIN')->first();
        $bsitBay = Course::where('program_code', 'BSIT-BAY')->first();
        $beedBay = Course::where('program_code', 'BEED-BAY')->first();
        $wdbInd = Course::where('program_code', 'WDB-IND')->first();

        $subjects = [
            // CPSU Main Campus Subjects
            [
                'subject_code' => 'PROG101-MAIN',
                'subject_name' => 'Programming Fundamentals',
                'program_id' => $bsitMain?->id,
                'campus' => 'CPSU Main Campus',
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],
            [
                'subject_code' => 'DSA201-MAIN',
                'subject_name' => 'Data Structures and Algorithms',
                'program_id' => $bscsMain?->id,
                'campus' => 'CPSU Main Campus',
                'year_level' => 2,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],
            // CPSU Bayambang Campus Subjects
            [
                'subject_code' => 'PROG101-BAY',
                'subject_name' => 'Programming Fundamentals',
                'program_id' => $bsitBay?->id,
                'campus' => 'CPSU Bayambang Campus',
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],
            [
                'subject_code' => 'EDUC101-BAY',
                'subject_name' => 'Foundations of Education',
                'program_id' => $beedBay?->id,
                'campus' => 'CPSU Bayambang Campus',
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],
            // Independent Subjects
            [
                'subject_code' => 'HTML101-IND',
                'subject_name' => 'HTML & CSS Basics',
                'program_id' => $wdbInd?->id,
                'campus' => null,
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Core',
                'credit_hours' => 2,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['subject_code' => $subject['subject_code']],
                [
                    'subject_name' => $subject['subject_name'],
                    'program_id' => $subject['program_id'],
                    'campus' => $subject['campus'],
                    'year_level' => $subject['year_level'],
                    'semester' => $subject['semester'],
                    'category' => $subject['category'],
                    'credit_hours' => $subject['credit_hours'],
                    'description' => $subject['subject_name'] . ' - Year ' . $subject['year_level'] . ' Semester ' . $subject['semester'],
                ]
            );
        }
    }
    private function createStudents(): void
    {
        $this->command->info('👨‍🎓 Creating students...');

        // CPSU Main Campus Students
        for ($i = 1; $i <= 10; $i++) {
            $user = User::updateOrCreate(
                ['email' => "student{$i}.main@cpsu.edu.ph"],
                [
                    'name' => "Main Student {$i}",
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'campus' => 'CPSU Main Campus',
                    'campus_status' => 'approved',
                    'status' => 'Active',
                ]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => "2024-MAIN-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'year' => rand(1, 4),
                    'section' => ['A', 'B', 'C'][rand(0, 2)],
                    'status' => 'Active',
                    'campus' => 'CPSU Main Campus',
                ]
            );
        }

        // CPSU Bayambang Campus Students
        for ($i = 1; $i <= 10; $i++) {
            $user = User::updateOrCreate(
                ['email' => "student{$i}.bay@cpsu.edu.ph"],
                [
                    'name' => "Bayambang Student {$i}",
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'campus' => 'CPSU Bayambang Campus',
                    'campus_status' => 'approved',
                    'status' => 'Active',
                ]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => "2024-BAY-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'year' => rand(1, 4),
                    'section' => ['A', 'B', 'C'][rand(0, 2)],
                    'status' => 'Active',
                    'campus' => 'CPSU Bayambang Campus',
                ]
            );
        }

        // Independent Students
        for ($i = 1; $i <= 5; $i++) {
            $user = User::updateOrCreate(
                ['email' => "independent.student{$i}@gmail.com"],
                [
                    'name' => "Independent Student {$i}",
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'campus' => null,
                    'campus_status' => 'approved',
                    'status' => 'Active',
                ]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => "2024-IND-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'year' => 1,
                    'section' => 'A',
                    'status' => 'Active',
                    'campus' => null,
                ]
            );
        }
    }

    private function createClasses(): void
    {
        $this->command->info('🏫 Creating classes...');

        // Get teachers and courses
        $mainTeacher = User::where('email', 'maria.santos@cpsu.edu.ph')->first();
        $bayTeacher = User::where('email', 'roberto.garcia@cpsu.edu.ph')->first();
        $indTeacher = User::where('email', 'john.smith@gmail.com')->first();
        
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bsitBay = Course::where('program_code', 'BSIT-BAY')->first();
        $wdbInd = Course::where('program_code', 'WDB-IND')->first();

        $classes = [
            [
                'class_name' => 'BSIT 1-A Main',
                'teacher_id' => $mainTeacher?->id,
                'course_id' => $bsitMain?->id,
                'class_level' => 1,
                'section' => 'A',
                'total_students' => 30,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'campus' => 'CPSU Main Campus',
            ],
            [
                'class_name' => 'BSIT 1-A Bayambang',
                'teacher_id' => $bayTeacher?->id,
                'course_id' => $bsitBay?->id,
                'class_level' => 1,
                'section' => 'A',
                'total_students' => 25,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'campus' => 'CPSU Bayambang Campus',
            ],
            [
                'class_name' => 'Web Dev Bootcamp Batch 1',
                'teacher_id' => $indTeacher?->id,
                'course_id' => $wdbInd?->id,
                'class_level' => 1,
                'section' => 'A',
                'total_students' => 15,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'campus' => null,
            ],
        ];

        foreach ($classes as $class) {
            if ($class['teacher_id'] && $class['course_id']) {
                ClassModel::updateOrCreate(
                    ['class_name' => $class['class_name']],
                    $class
                );
            }
        }
    }

    private function createCourseAccessRequests(): void
    {
        $this->command->info('📝 Creating course access requests...');

        // Get teachers and courses
        $mainTeacher = User::where('email', 'maria.santos@cpsu.edu.ph')->first();
        $bayTeacher = User::where('email', 'roberto.garcia@cpsu.edu.ph')->first();
        $pendingTeacher = User::where('email', 'ana.reyes@cpsu.edu.ph')->first();
        
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bscsMain = Course::where('program_code', 'BSCS-MAIN')->first();
        $bsitBay = Course::where('program_code', 'BSIT-BAY')->first();

        $requests = [
            [
                'teacher_id' => $mainTeacher?->id,
                'course_id' => $bsitMain?->id,
                'status' => 'approved',
                'reason' => 'I am qualified to teach BSIT courses',
                'approved_by' => 2,
                'approved_at' => now(),
            ],
            [
                'teacher_id' => $bayTeacher?->id,
                'course_id' => $bsitBay?->id,
                'status' => 'approved',
                'reason' => 'I have experience teaching IT subjects',
                'approved_by' => 3,
                'approved_at' => now(),
            ],
            [
                'teacher_id' => $pendingTeacher?->id,
                'course_id' => $bscsMain?->id,
                'status' => 'pending',
                'reason' => 'I would like to teach Computer Science courses',
            ],
        ];

        foreach ($requests as $request) {
            if ($request['teacher_id'] && $request['course_id']) {
                CourseAccessRequest::updateOrCreate(
                    [
                        'teacher_id' => $request['teacher_id'],
                        'course_id' => $request['course_id']
                    ],
                    $request
                );
            }
        }
    }

    private function printSummary(): void
    {
        $this->command->info("\n📊 SEEDER SUMMARY:");
        $this->command->info("==================");
        
        $collegeCount = College::count();
        $departmentCount = Department::count();
        $adminCount = User::where('role', 'admin')->count();
        $superCount = User::where('role', 'super')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $studentCount = User::where('role', 'student')->count();
        $courseCount = Course::count();
        $subjectCount = Subject::count();
        $classCount = ClassModel::count();
        $requestCount = CourseAccessRequest::count();
        
        $this->command->info("🏛️ Colleges: {$collegeCount}");
        $this->command->info("🏢 Departments: {$departmentCount}");
        $this->command->info("👤 Super Admins: {$superCount}");
        $this->command->info("👤 Admins: {$adminCount}");
        $this->command->info("👨‍🏫 Teachers: {$teacherCount}");
        $this->command->info("👨‍🎓 Students: {$studentCount}");
        $this->command->info("🎓 Courses: {$courseCount}");
        $this->command->info("📚 Subjects: {$subjectCount}");
        $this->command->info("🏫 Classes: {$classCount}");
        $this->command->info("📝 Course Access Requests: {$requestCount}");
        
        $this->command->info("\n🧪 TEST ACCOUNTS:");
        $this->command->info("Super Admin: super@cpsu.edu.ph / super123");
        $this->command->info("Main Admin: admin@cpsu.edu.ph / admin123");
        $this->command->info("Bay Admin: admin.bayambang@cpsu.edu.ph / admin123");
        $this->command->info("Approved Teacher: maria.santos@cpsu.edu.ph / teacher123");
        $this->command->info("Pending Teacher: ana.reyes@cpsu.edu.ph / teacher123");
        $this->command->info("Independent Teacher: john.smith@gmail.com / teacher123");
        $this->command->info("Student: student1.main@cpsu.edu.ph / student123");
    }
}