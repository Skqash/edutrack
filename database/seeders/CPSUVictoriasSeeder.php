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
use App\Models\Grade;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CPSUVictoriasSeeder extends Seeder
{
    /**
     * CPSU Victorias-focused seeder with comprehensive campus isolation
     * Majority of data will be for Victorias Campus with test data for other campuses
     */
    public function run(): void
    {
        $this->command->info('🏫 Starting CPSU Victorias-Focused Seeder...');

        $this->createCollegesAndDepartments();
        $this->createAdminUsers();
        $this->createTeachers();
        $this->createCourses();
        $this->createSubjects();
        $this->createStudents();
        $this->createClasses();
        $this->createGrades();
        $this->createAttendance();
        $this->createCourseAccessRequests();
        
        $this->command->info('✅ CPSU Victorias-Focused Seeder completed successfully!');
        $this->printSummary();
    }

    private function createCollegesAndDepartments(): void
    {
        $this->command->info('🏛️ Creating CPSU colleges and departments...');

        // Real CPSU Colleges
        $colleges = [
            ['name' => 'College of Computer Studies', 'desc' => 'Information Technology and Computer Science programs'],
            ['name' => 'College of Education', 'desc' => 'Teacher education and pedagogy programs'],
            ['name' => 'College of Agriculture and Food Engineering', 'desc' => 'Agricultural and food engineering programs'],
            ['name' => 'College of Business and Management', 'desc' => 'Business administration and management programs'],
            ['name' => 'College of Engineering', 'desc' => 'Engineering programs'],
        ];

        $collegeModels = [];
        foreach ($colleges as $college) {
            $collegeModels[] = College::updateOrCreate(
                ['college_name' => $college['name']],
                ['description' => $college['desc']]
            );
        }

        // Real CPSU Departments
        $departments = [
            ['name' => 'Information Technology', 'college_index' => 0],
            ['name' => 'Computer Science', 'college_index' => 0],
            ['name' => 'Elementary Education', 'college_index' => 1],
            ['name' => 'Secondary Education', 'college_index' => 1],
            ['name' => 'Agricultural Engineering', 'college_index' => 2],
            ['name' => 'Food Engineering', 'college_index' => 2],
            ['name' => 'Business Administration', 'college_index' => 3],
            ['name' => 'Hospitality Management', 'college_index' => 3],
            ['name' => 'Civil Engineering', 'college_index' => 4],
            ['name' => 'Mechanical Engineering', 'college_index' => 4],
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
        $this->command->info('👤 Creating campus admin users...');

        $campuses = [
            'Victorias Campus' => 'victorias',
            'Main Campus (Kabankalan City)' => 'main',
            'Sipalay Campus' => 'sipalay',
            'Cauayan Campus' => 'cauayan',
            'Candoni Campus' => 'candoni'
        ];

        foreach ($campuses as $campusName => $campusCode) {
            $adminUser = User::updateOrCreate(
                ['email' => "admin.{$campusCode}@cpsu.edu.ph"],
                [
                    'name' => "Admin {$campusName}",
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                    'campus' => $campusName,
                    'campus_status' => 'approved',
                    'email_verified_at' => now(),
                ]
            );

            // Create admin profile
            \App\Models\Admin::updateOrCreate(
                ['user_id' => $adminUser->id],
                [
                    'employee_id' => "ADM-{$campusCode}-001",
                    'department' => 'Administration',
                    'status' => 'Active'
                ]
            );
        }
    }

    private function createTeachers(): void
    {
        $this->command->info('👨‍🏫 Creating teachers with campus focus on Victorias...');

        // Majority of teachers for Victorias Campus (15 teachers)
        $victoriasTeachers = [
            ['name' => 'Prof. Maria Santos', 'email' => 'maria.santos@cpsu.edu.ph', 'dept' => 'Information Technology'],
            ['name' => 'Dr. Juan Dela Cruz', 'email' => 'juan.delacruz@cpsu.edu.ph', 'dept' => 'Computer Science'],
            ['name' => 'Prof. Ana Rodriguez', 'email' => 'ana.rodriguez@cpsu.edu.ph', 'dept' => 'Elementary Education'],
            ['name' => 'Dr. Carlos Mendoza', 'email' => 'carlos.mendoza@cpsu.edu.ph', 'dept' => 'Secondary Education'],
            ['name' => 'Prof. Elena Fernandez', 'email' => 'elena.fernandez@cpsu.edu.ph', 'dept' => 'Business Administration'],
            ['name' => 'Dr. Roberto Garcia', 'email' => 'roberto.garcia@cpsu.edu.ph', 'dept' => 'Civil Engineering'],
            ['name' => 'Prof. Lisa Morales', 'email' => 'lisa.morales@cpsu.edu.ph', 'dept' => 'Information Technology'],
            ['name' => 'Dr. Miguel Torres', 'email' => 'miguel.torres@cpsu.edu.ph', 'dept' => 'Agricultural Engineering'],
            ['name' => 'Prof. Carmen Valdez', 'email' => 'carmen.valdez@cpsu.edu.ph', 'dept' => 'Hospitality Management'],
            ['name' => 'Dr. Francisco Reyes', 'email' => 'francisco.reyes@cpsu.edu.ph', 'dept' => 'Mechanical Engineering'],
            ['name' => 'Prof. Isabella Cruz', 'email' => 'isabella.cruz@cpsu.edu.ph', 'dept' => 'Food Engineering'],
            ['name' => 'Dr. Antonio Lopez', 'email' => 'antonio.lopez@cpsu.edu.ph', 'dept' => 'Computer Science'],
            ['name' => 'Prof. Sofia Ramirez', 'email' => 'sofia.ramirez@cpsu.edu.ph', 'dept' => 'Elementary Education'],
            ['name' => 'Dr. Diego Herrera', 'email' => 'diego.herrera@cpsu.edu.ph', 'dept' => 'Secondary Education'],
            ['name' => 'Prof. Valentina Jimenez', 'email' => 'valentina.jimenez@cpsu.edu.ph', 'dept' => 'Business Administration']
        ];

        foreach ($victoriasTeachers as $index => $teacher) {
            $user = User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => 'Victorias Campus',
                    'campus_status' => 'approved',
                    'email_verified_at' => now(),
                ]
            );

            \App\Models\Teacher::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => 'VIC-T-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'department' => $teacher['dept'],
                    'qualification' => 'Master of Science',
                    'bio' => 'Experienced educator at CPSU Victorias Campus',
                    'status' => 'Active'
                ]
            );
        }

        // Smaller number of teachers for other campuses (2-3 each)
        $otherCampusTeachers = [
            'Main Campus (Kabankalan City)' => [
                ['name' => 'Dr. Pedro Gonzales', 'email' => 'pedro.gonzales@cpsu.edu.ph', 'dept' => 'Information Technology'],
                ['name' => 'Prof. Maria Flores', 'email' => 'maria.flores@cpsu.edu.ph', 'dept' => 'Business Administration'],
                ['name' => 'Dr. Jose Martinez', 'email' => 'jose.martinez@cpsu.edu.ph', 'dept' => 'Civil Engineering']
            ],
            'Sipalay Campus' => [
                ['name' => 'Prof. Rosa Castillo', 'email' => 'rosa.castillo@cpsu.edu.ph', 'dept' => 'Elementary Education'],
                ['name' => 'Dr. Luis Vargas', 'email' => 'luis.vargas@cpsu.edu.ph', 'dept' => 'Agricultural Engineering']
            ],
            'Cauayan Campus' => [
                ['name' => 'Prof. Carmen Ortega', 'email' => 'carmen.ortega@cpsu.edu.ph', 'dept' => 'Secondary Education'],
                ['name' => 'Dr. Rafael Moreno', 'email' => 'rafael.moreno@cpsu.edu.ph', 'dept' => 'Mechanical Engineering']
            ]
        ];

        foreach ($otherCampusTeachers as $campus => $teachers) {
            $campusCode = strtolower(explode(' ', $campus)[0]);
            foreach ($teachers as $index => $teacher) {
                $user = User::updateOrCreate(
                    ['email' => $teacher['email']],
                    [
                        'name' => $teacher['name'],
                        'password' => Hash::make('teacher123'),
                        'role' => 'teacher',
                        'campus' => $campus,
                        'campus_status' => 'approved',
                        'email_verified_at' => now(),
                    ]
                );

                \App\Models\Teacher::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_id' => strtoupper(substr($campusCode, 0, 3)) . '-T-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'department' => $teacher['dept'],
                        'qualification' => 'Master of Science',
                        'bio' => "Experienced educator at CPSU {$campus}",
                        'status' => 'Active'
                    ]
                );
            }
        }

        // Create a few independent teachers (no campus affiliation)
        $independentTeachers = [
            ['name' => 'Prof. Independent Teacher 1', 'email' => 'independent1@example.com'],
            ['name' => 'Prof. Independent Teacher 2', 'email' => 'independent2@example.com']
        ];

        foreach ($independentTeachers as $index => $teacher) {
            $user = User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => null,
                    'campus_status' => null,
                    'email_verified_at' => now(),
                ]
            );

            \App\Models\Teacher::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => 'IND-T-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'department' => 'Independent',
                    'qualification' => 'Bachelor of Science',
                    'bio' => 'Independent teacher',
                    'status' => 'Active'
                ]
            );
        }
    }

    private function createCourses(): void
    {
        $this->command->info('📚 Creating courses with campus isolation...');

        $departments = Department::all()->keyBy('department_name');

        // Victorias Campus courses (majority)
        $victoriasCourses = [
            ['code' => 'BSIT-VIC', 'name' => 'Bachelor of Science in Information Technology', 'dept' => 'Information Technology'],
            ['code' => 'BSCS-VIC', 'name' => 'Bachelor of Science in Computer Science', 'dept' => 'Computer Science'],
            ['code' => 'BEED-VIC', 'name' => 'Bachelor of Elementary Education', 'dept' => 'Elementary Education'],
            ['code' => 'BSED-VIC', 'name' => 'Bachelor of Secondary Education', 'dept' => 'Secondary Education'],
            ['code' => 'BSBA-VIC', 'name' => 'Bachelor of Science in Business Administration', 'dept' => 'Business Administration'],
            ['code' => 'BSCE-VIC', 'name' => 'Bachelor of Science in Civil Engineering', 'dept' => 'Civil Engineering'],
            ['code' => 'BSAE-VIC', 'name' => 'Bachelor of Science in Agricultural Engineering', 'dept' => 'Agricultural Engineering'],
            ['code' => 'BSHM-VIC', 'name' => 'Bachelor of Science in Hospitality Management', 'dept' => 'Hospitality Management']
        ];

        foreach ($victoriasCourses as $courseData) {
            Course::updateOrCreate(
                ['program_code' => $courseData['code']],
                [
                    'program_name' => $courseData['name'],
                    'campus' => 'Victorias Campus',
                    'total_years' => 4,
                    'status' => 'Active'
                ]
            );
        }

        // Other campuses - minimal courses
        $otherCourses = [
            ['code' => 'BSIT-MAIN', 'name' => 'BS Information Technology', 'campus' => 'Main Campus (Kabankalan City)'],
            ['code' => 'BSBA-MAIN', 'name' => 'BS Business Administration', 'campus' => 'Main Campus (Kabankalan City)'],
            ['code' => 'BEED-SIP', 'name' => 'Bachelor of Elementary Education', 'campus' => 'Sipalay Campus'],
        ];

        foreach ($otherCourses as $courseData) {
            Course::updateOrCreate(
                ['program_code' => $courseData['code']],
                [
                    'program_name' => $courseData['name'],
                    'campus' => $courseData['campus'],
                    'total_years' => 4,
                    'status' => 'Active'
                ]
            );
        }
    }

    private function createSubjects(): void
    {
        $this->command->info('📖 Creating subjects...');
        
        // This method can be expanded as needed
        $this->command->info('   Subjects will be created by teachers as needed');
    }

    private function createStudents(): void
    {
        $this->command->info('👨‍🎓 Creating students for Victorias Campus...');

        $courses = Course::where('campus', 'Victorias Campus')->get();
        
        if ($courses->isEmpty()) {
            $this->command->warn('   No courses found for Victorias Campus');
            return;
        }

        $studentCount = 0;
        foreach ($courses as $course) {
            // Create 10-15 students per course
            $numStudents = rand(10, 15);
            
            for ($i = 1; $i <= $numStudents; $i++) {
                $studentCount++;
                $studentNumber = 'VIC-' . date('Y') . '-' . str_pad($studentCount, 4, '0', STR_PAD_LEFT);
                
                Student::updateOrCreate(
                    ['student_id' => $studentNumber],
                    [
                        'first_name' => 'Student',
                        'last_name' => 'VIC-' . $studentCount,
                        'middle_name' => 'M',
                        'email' => "student.vic{$studentCount}@cpsu.edu.ph",
                        'course_id' => $course->id,
                        'year_level' => rand(1, 4),
                        'campus' => 'Victorias Campus',
                        'status' => 'Active'
                    ]
                );
            }
        }

        $this->command->info("   Created {$studentCount} students for Victorias Campus");
    }

    private function createClasses(): void
    {
        $this->command->info('🏫 Creating classes...');
        
        $courses = Course::where('campus', 'Victorias Campus')->get();
        $teachers = User::where('role', 'teacher')
            ->where('campus', 'Victorias Campus')
            ->get();

        if ($courses->isEmpty() || $teachers->isEmpty()) {
            $this->command->warn('   No courses or teachers found');
            return;
        }

        $classCount = 0;
        foreach ($courses as $course) {
            // Create 2-3 classes per course
            $numClasses = rand(2, 3);
            
            for ($i = 1; $i <= $numClasses; $i++) {
                $teacher = $teachers->random();
                
                ClassModel::updateOrCreate(
                    [
                        'class_name' => $course->program_code . '-' . $i,
                        'course_id' => $course->id
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'section' => 'Section ' . $i,
                        'year_level' => rand(1, 4),
                        'semester' => rand(1, 2),
                        'school_year' => '2025-2026',
                        'campus' => 'Victorias Campus',
                        'schedule' => 'MWF 10:00-11:30 AM',
                        'room' => 'Room ' . rand(101, 305)
                    ]
                );
                $classCount++;
            }
        }

        $this->command->info("   Created {$classCount} classes");
    }

    private function createGrades(): void
    {
        $this->command->info('📊 Creating sample grades...');
        $this->command->info('   Grades will be entered by teachers through the system');
    }

    private function createAttendance(): void
    {
        $this->command->info('📅 Creating sample attendance...');
        $this->command->info('   Attendance will be recorded by teachers through the system');
    }

    private function createCourseAccessRequests(): void
    {
        $this->command->info('📝 Creating sample course access requests...');
        $this->command->info('   Course access requests will be created by teachers as needed');
    }

    private function printSummary(): void
    {
        $this->command->info('');
        $this->command->info('📊 Seeding Summary:');
        $this->command->info('   Colleges: ' . College::count());
        $this->command->info('   Departments: ' . Department::count());
        $this->command->info('   Admin Users: ' . User::where('role', 'admin')->count());
        $this->command->info('   Teachers: ' . User::where('role', 'teacher')->count());
        $this->command->info('   Courses: ' . Course::count());
        $this->command->info('   Students: ' . Student::count());
        $this->command->info('   Classes: ' . ClassModel::count());
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('   Admin (Victorias): admin.victorias@cpsu.edu.ph / admin123');
        $this->command->info('   Teacher: maria.santos@cpsu.edu.ph / teacher123');
        $this->command->info('');
    }
}