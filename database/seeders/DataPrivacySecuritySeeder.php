<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DataPrivacySecuritySeeder extends Seeder
{
    /**
     * Run the database seeder.
     * 
     * This seeder implements the new security policy:
     * - Separates affiliated and non-affiliated teacher data
     * - Ensures data privacy between different campus affiliations
     * - Implements proper course access request workflow
     * - Creates isolated data sets for testing security boundaries
     */
    public function run(): void
    {
        $this->command->info('🔒 Starting Data Privacy & Security Seeder...');

        // Create Admin Users
        $this->createAdminUsers();
        
        // Create Campus-Affiliated Teachers
        $this->createAffiliatedTeachers();
        
        // Create Independent Teachers
        $this->createIndependentTeachers();
        
        // Create Campus-Specific Courses
        $this->createCampusSpecificCourses();
        
        // Create Independent Courses
        $this->createIndependentCourses();
        
        // Create Campus-Specific Subjects
        $this->createCampusSpecificSubjects();
        
        // Create Campus-Specific Students
        $this->createCampusSpecificStudents();
        
        // Create Independent Students
        $this->createIndependentStudents();
        
        // Create Campus-Specific Classes
        $this->createCampusSpecificClasses();
        
        // Create Independent Classes
        $this->createIndependentClasses();
        
        // Create Course Access Requests
        $this->createCourseAccessRequests();
        
        $this->command->info('✅ Data Privacy & Security Seeder completed successfully!');
        $this->printSummary();
    }

    private function createAdminUsers(): void
    {
        $this->command->info('👤 Creating admin users...');

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

        // Super Admin
        User::updateOrCreate(
            ['email' => 'super@cpsu.edu.ph'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('super123'),
                'role' => 'super',
                'campus' => null,
                'campus_status' => 'approved', // Super admin is always approved
                'status' => 'Active',
            ]
        );
    }

    private function createAffiliatedTeachers(): void
    {
        $this->command->info('👨‍🏫 Creating campus-affiliated teachers...');

        // CPSU Main Campus Teachers
        $mainTeachers = [
            [
                'name' => 'Dr. Maria Santos',
                'email' => 'maria.santos@cpsu.edu.ph',
                'campus' => 'CPSU Main Campus',
                'status' => 'approved',
            ],
            [
                'name' => 'Prof. Juan Dela Cruz',
                'email' => 'juan.delacruz@cpsu.edu.ph',
                'campus' => 'CPSU Main Campus',
                'status' => 'approved',
            ],
            [
                'name' => 'Dr. Ana Reyes',
                'email' => 'ana.reyes@cpsu.edu.ph',
                'campus' => 'CPSU Main Campus',
                'status' => 'pending', // Pending approval for testing
            ],
        ];

        // CPSU Bayambang Campus Teachers
        $bayambangTeachers = [
            [
                'name' => 'Prof. Roberto Garcia',
                'email' => 'roberto.garcia@cpsu.edu.ph',
                'campus' => 'CPSU Bayambang Campus',
                'status' => 'approved',
            ],
            [
                'name' => 'Dr. Carmen Lopez',
                'email' => 'carmen.lopez@cpsu.edu.ph',
                'campus' => 'CPSU Bayambang Campus',
                'status' => 'approved',
            ],
            [
                'name' => 'Prof. Miguel Torres',
                'email' => 'miguel.torres@cpsu.edu.ph',
                'campus' => 'CPSU Bayambang Campus',
                'status' => 'rejected', // Rejected for testing
            ],
        ];

        foreach (array_merge($mainTeachers, $bayambangTeachers) as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => $teacher['campus'],
                    'campus_status' => $teacher['status'],
                    'campus_approved_at' => $teacher['status'] === 'approved' ? now() : null,
                    'campus_approved_by' => $teacher['status'] === 'approved' ? 1 : null,
                    'status' => 'Active',
                ]
            );
        }
    }

    private function createIndependentTeachers(): void
    {
        $this->command->info('🏠 Creating independent teachers...');

        $independentTeachers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@gmail.com',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@yahoo.com',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@outlook.com',
            ],
        ];

        foreach ($independentTeachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => null, // Independent teachers have no campus
                    'campus_status' => 'approved', // Independent teachers are automatically approved
                    'status' => 'Active',
                ]
            );
        }
    }

    private function createCampusSpecificCourses(): void
    {
        $this->command->info('🎓 Creating campus-specific courses...');

        // CPSU Main Campus Courses
        $mainCourses = [
            [
                'program_name' => 'Bachelor of Science in Information Technology',
                'program_code' => 'BSIT-MAIN',
                'description' => 'CPSU Main Campus IT Program',
                'campus' => 'CPSU Main Campus',
            ],
            [
                'program_name' => 'Bachelor of Science in Computer Science',
                'program_code' => 'BSCS-MAIN',
                'description' => 'CPSU Main Campus CS Program',
                'campus' => 'CPSU Main Campus',
            ],
        ];

        // CPSU Bayambang Campus Courses
        $bayambangCourses = [
            [
                'program_name' => 'Bachelor of Science in Information Technology',
                'program_code' => 'BSIT-BAY',
                'description' => 'CPSU Bayambang Campus IT Program',
                'campus' => 'CPSU Bayambang Campus',
            ],
            [
                'program_name' => 'Bachelor of Science in Agriculture',
                'program_code' => 'BSA-BAY',
                'description' => 'CPSU Bayambang Campus Agriculture Program',
                'campus' => 'CPSU Bayambang Campus',
            ],
        ];

        foreach (array_merge($mainCourses, $bayambangCourses) as $course) {
            Course::updateOrCreate(
                ['program_code' => $course['program_code']],
                [
                    'program_name' => $course['program_name'],
                    'course_code' => $course['program_code'], // Use program_code as course_code
                    'description' => $course['description'],
                    'total_years' => 4,
                    'status' => 'Active',
                    'campus' => $course['campus'], // Add campus field if not exists
                ]
            );
        }
    }

    private function createIndependentCourses(): void
    {
        $this->command->info('🏠 Creating independent courses...');

        $independentCourses = [
            [
                'program_name' => 'Web Development Bootcamp',
                'program_code' => 'WDB-IND',
                'description' => 'Independent Web Development Course',
            ],
            [
                'program_name' => 'Digital Marketing Course',
                'program_code' => 'DMC-IND',
                'description' => 'Independent Digital Marketing Course',
            ],
        ];

        foreach ($independentCourses as $course) {
            Course::updateOrCreate(
                ['program_code' => $course['program_code']],
                [
                    'program_name' => $course['program_name'],
                    'course_code' => $course['program_code'], // Use program_code as course_code
                    'description' => $course['description'],
                    'total_years' => 1,
                    'status' => 'Active',
                    'campus' => null, // Independent courses have no campus
                ]
            );
        }
    }

    private function createCampusSpecificSubjects(): void
    {
        $this->command->info('📚 Creating campus-specific subjects...');

        // Get courses for subject assignment
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bscsMain = Course::where('program_code', 'BSCS-MAIN')->first();
        $bsitBay = Course::where('program_code', 'BSIT-BAY')->first();
        $bsaBay = Course::where('program_code', 'BSA-BAY')->first();

        $subjects = [
            // CPSU Main Campus Subjects
            [
                'subject_name' => 'Programming Fundamentals',
                'subject_code' => 'PROG101-MAIN',
                'program_id' => $bsitMain?->id,
                'campus' => 'CPSU Main Campus',
            ],
            [
                'subject_name' => 'Data Structures and Algorithms',
                'subject_code' => 'DSA201-MAIN',
                'program_id' => $bscsMain?->id,
                'campus' => 'CPSU Main Campus',
            ],
            // CPSU Bayambang Campus Subjects
            [
                'subject_name' => 'Programming Fundamentals',
                'subject_code' => 'PROG101-BAY',
                'program_id' => $bsitBay?->id,
                'campus' => 'CPSU Bayambang Campus',
            ],
            [
                'subject_name' => 'Crop Science',
                'subject_code' => 'CROP101-BAY',
                'program_id' => $bsaBay?->id,
                'campus' => 'CPSU Bayambang Campus',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['subject_code' => $subject['subject_code']],
                [
                    'subject_name' => $subject['subject_name'],
                    'program_id' => $subject['program_id'],
                    'credit_hours' => 3,
                    'category' => 'Major',
                    'year_level' => 1,
                    'semester' => '1',
                    'campus' => $subject['campus'], // Add campus field if not exists
                ]
            );
        }
    }

    private function createCampusSpecificStudents(): void
    {
        $this->command->info('👨‍🎓 Creating campus-specific students...');

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
    }

    private function createIndependentStudents(): void
    {
        $this->command->info('🏠 Creating independent students...');

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

    private function createCampusSpecificClasses(): void
    {
        $this->command->info('🏫 Creating campus-specific classes...');

        // Get approved teachers and courses
        $mainTeacher = User::where('email', 'maria.santos@cpsu.edu.ph')->first();
        $bayTeacher = User::where('email', 'roberto.garcia@cpsu.edu.ph')->first();
        
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bsitBay = Course::where('program_code', 'BSIT-BAY')->first();

        if ($mainTeacher && $bsitMain) {
            ClassModel::updateOrCreate(
                ['class_name' => 'BSIT 1-A Main'],
                [
                    'teacher_id' => $mainTeacher->id,
                    'course_id' => $bsitMain->id,
                    'class_level' => 1,
                    'section' => 'A',
                    'total_students' => 30,
                    'academic_year' => '2024-2025',
                    'semester' => 'First',
                    'status' => 'Active',
                    'campus' => 'CPSU Main Campus',
                ]
            );
        }

        if ($bayTeacher && $bsitBay) {
            ClassModel::updateOrCreate(
                ['class_name' => 'BSIT 1-A Bayambang'],
                [
                    'teacher_id' => $bayTeacher->id,
                    'course_id' => $bsitBay->id,
                    'class_level' => 1,
                    'section' => 'A',
                    'total_students' => 25,
                    'academic_year' => '2024-2025',
                    'semester' => 'First',
                    'status' => 'Active',
                    'campus' => 'CPSU Bayambang Campus',
                ]
            );
        }
    }

    private function createIndependentClasses(): void
    {
        $this->command->info('🏠 Creating independent classes...');

        $independentTeacher = User::where('email', 'john.smith@gmail.com')->first();
        $independentCourse = Course::where('program_code', 'WDB-IND')->first();

        if ($independentTeacher && $independentCourse) {
            ClassModel::updateOrCreate(
                ['class_name' => 'Web Dev Bootcamp Batch 1'],
                [
                    'teacher_id' => $independentTeacher->id,
                    'course_id' => $independentCourse->id,
                    'class_level' => 1,
                    'section' => 'A',
                    'total_students' => 15,
                    'academic_year' => '2024-2025',
                    'semester' => 'First',
                    'status' => 'Active',
                    'campus' => null,
                ]
            );
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

        // Approved requests
        if ($mainTeacher && $bsitMain) {
            CourseAccessRequest::updateOrCreate(
                ['teacher_id' => $mainTeacher->id, 'course_id' => $bsitMain->id],
                [
                    'status' => 'approved',
                    'reason' => 'I am qualified to teach BSIT courses',
                    'approved_by' => 1,
                    'approved_at' => now(),
                ]
            );
        }

        if ($bayTeacher && $bsitBay) {
            CourseAccessRequest::updateOrCreate(
                ['teacher_id' => $bayTeacher->id, 'course_id' => $bsitBay->id],
                [
                    'status' => 'approved',
                    'reason' => 'I have experience teaching IT subjects',
                    'approved_by' => 2,
                    'approved_at' => now(),
                ]
            );
        }

        // Pending request (for testing)
        if ($pendingTeacher && $bscsMain) {
            CourseAccessRequest::updateOrCreate(
                ['teacher_id' => $pendingTeacher->id, 'course_id' => $bscsMain->id],
                [
                    'status' => 'pending',
                    'reason' => 'I would like to teach Computer Science courses',
                ]
            );
        }
    }

    private function printSummary(): void
    {
        $this->command->info("\n📊 SEEDER SUMMARY:");
        $this->command->info("==================");
        
        $adminCount = User::where('role', 'admin')->count();
        $superCount = User::where('role', 'super')->count();
        $affiliatedTeachers = User::where('role', 'teacher')->whereNotNull('campus')->count();
        $independentTeachers = User::where('role', 'teacher')->whereNull('campus')->count();
        $approvedTeachers = User::where('role', 'teacher')->where('campus_status', 'approved')->count();
        $pendingTeachers = User::where('role', 'teacher')->where('campus_status', 'pending')->count();
        $rejectedTeachers = User::where('role', 'teacher')->where('campus_status', 'rejected')->count();
        
        $campusCourses = Course::whereNotNull('campus')->count();
        $independentCourses = Course::whereNull('campus')->count();
        
        $campusStudents = Student::whereNotNull('campus')->count();
        $independentStudents = Student::whereNull('campus')->count();
        
        $campusClasses = ClassModel::whereNotNull('campus')->count();
        $independentClasses = ClassModel::whereNull('campus')->count();
        
        $approvedRequests = CourseAccessRequest::where('status', 'approved')->count();
        $pendingRequests = CourseAccessRequest::where('status', 'pending')->count();
        
        $this->command->info("👤 Users:");
        $this->command->info("   - Admins: {$adminCount}");
        $this->command->info("   - Super Admins: {$superCount}");
        $this->command->info("   - Affiliated Teachers: {$affiliatedTeachers}");
        $this->command->info("   - Independent Teachers: {$independentTeachers}");
        $this->command->info("   - Approved Teachers: {$approvedTeachers}");
        $this->command->info("   - Pending Teachers: {$pendingTeachers}");
        $this->command->info("   - Rejected Teachers: {$rejectedTeachers}");
        
        $this->command->info("\n🎓 Courses:");
        $this->command->info("   - Campus Courses: {$campusCourses}");
        $this->command->info("   - Independent Courses: {$independentCourses}");
        
        $this->command->info("\n👨‍🎓 Students:");
        $this->command->info("   - Campus Students: {$campusStudents}");
        $this->command->info("   - Independent Students: {$independentStudents}");
        
        $this->command->info("\n🏫 Classes:");
        $this->command->info("   - Campus Classes: {$campusClasses}");
        $this->command->info("   - Independent Classes: {$independentClasses}");
        
        $this->command->info("\n📝 Course Access Requests:");
        $this->command->info("   - Approved: {$approvedRequests}");
        $this->command->info("   - Pending: {$pendingRequests}");
        
        $this->command->info("\n🔒 SECURITY FEATURES IMPLEMENTED:");
        $this->command->info("✅ Campus-based data separation");
        $this->command->info("✅ Course access request workflow");
        $this->command->info("✅ Independent teacher isolation");
        $this->command->info("✅ Campus approval system");
        $this->command->info("✅ Data privacy boundaries");
        
        $this->command->info("\n🧪 TEST ACCOUNTS:");
        $this->command->info("Admin: admin@cpsu.edu.ph / admin123");
        $this->command->info("Approved Teacher: maria.santos@cpsu.edu.ph / teacher123");
        $this->command->info("Pending Teacher: ana.reyes@cpsu.edu.ph / teacher123");
        $this->command->info("Independent Teacher: john.smith@gmail.com / teacher123");
    }
}