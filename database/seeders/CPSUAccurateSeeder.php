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

class CPSUAccurateSeeder extends Seeder
{
    /**
     * Accurate CPSU Seeder with real campus information
     */
    public function run(): void
    {
        $this->command->info('🏫 Starting CPSU Accurate Seeder...');

        $this->createCollegesAndDepartments();
        $this->createAdminUsers();
        $this->createTeachers();
        $this->createCourses();
        $this->createSubjects();
        $this->createStudents();
        $this->createClasses();
        $this->createCourseAccessRequests();
        
        $this->command->info('✅ CPSU Accurate Seeder completed successfully!');
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
        $this->command->info('👤 Creating CPSU admin users...');

        // Super Admin
        User::updateOrCreate(
            ['email' => 'super@cpsu.edu.ph'],
            [
                'name' => 'CPSU Super Administrator',
                'password' => Hash::make('super123'),
                'role' => 'super',
                'campus' => null,
                'campus_status' => 'approved',
                'status' => 'Active',
            ]
        );

        // Main Campus Admin (Kabankalan City)
        User::updateOrCreate(
            ['email' => 'admin.main@cpsu.edu.ph'],
            [
                'name' => 'CPSU Main Campus Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'campus' => 'CPSU Main Campus - Kabankalan City',
                'campus_status' => 'approved',
                'campus_approved_at' => now(),
                'status' => 'Active',
            ]
        );

        // Victorias Campus Admin (Your campus)
        User::updateOrCreate(
            ['email' => 'admin.victorias@cpsu.edu.ph'],
            [
                'name' => 'CPSU Victorias Campus Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'campus' => 'CPSU Victorias Campus',
                'campus_status' => 'approved',
                'campus_approved_at' => now(),
                'status' => 'Active',
            ]
        );

        // Sipalay Campus Admin
        User::updateOrCreate(
            ['email' => 'admin.sipalay@cpsu.edu.ph'],
            [
                'name' => 'CPSU Sipalay Campus Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                'campus_status' => 'approved',
                'campus_approved_at' => now(),
                'status' => 'Active',
            ]
        );

        // Cauayan Campus Admin
        User::updateOrCreate(
            ['email' => 'admin.cauayan@cpsu.edu.ph'],
            [
                'name' => 'CPSU Cauayan Campus Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'campus' => 'CPSU Cauayan Campus',
                'campus_status' => 'approved',
                'campus_approved_at' => now(),
                'status' => 'Active',
            ]
        );
    }

    private function createTeachers(): void
    {
        $this->command->info('👨‍🏫 Creating CPSU teachers...');

        // Main Campus Teachers (Kabankalan)
        $mainTeachers = [
            ['name' => 'Dr. Maria Santos', 'email' => 'maria.santos@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Prof. Juan Dela Cruz', 'email' => 'juan.delacruz@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Dr. Ana Reyes', 'email' => 'ana.reyes@cpsu.edu.ph', 'status' => 'approved'],
        ];

        foreach ($mainTeachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => 'CPSU Main Campus - Kabankalan City',
                    'campus_status' => $teacher['status'],
                    'campus_approved_at' => $teacher['status'] === 'approved' ? now() : null,
                    'campus_approved_by' => $teacher['status'] === 'approved' ? 2 : null,
                    'status' => 'Active',
                ]
            );
        }

        // Victorias Campus Teachers (Your campus)
        $victoriasTeachers = [
            ['name' => 'Prof. Roberto Garcia', 'email' => 'roberto.garcia@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Dr. Carmen Lopez', 'email' => 'carmen.lopez@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Prof. Miguel Torres', 'email' => 'miguel.torres@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Ms. Lisa Fernandez', 'email' => 'lisa.fernandez@cpsu.edu.ph', 'status' => 'pending'],
        ];

        foreach ($victoriasTeachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => 'CPSU Victorias Campus',
                    'campus_status' => $teacher['status'],
                    'campus_approved_at' => $teacher['status'] === 'approved' ? now() : null,
                    'campus_approved_by' => $teacher['status'] === 'approved' ? 3 : null,
                    'status' => 'Active',
                ]
            );
        }

        // Sipalay Campus Teachers
        $sipalayTeachers = [
            ['name' => 'Prof. Carlos Mendoza', 'email' => 'carlos.mendoza@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Dr. Elena Rodriguez', 'email' => 'elena.rodriguez@cpsu.edu.ph', 'status' => 'approved'],
        ];

        foreach ($sipalayTeachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                    'campus_status' => $teacher['status'],
                    'campus_approved_at' => $teacher['status'] === 'approved' ? now() : null,
                    'campus_approved_by' => $teacher['status'] === 'approved' ? 4 : null,
                    'status' => 'Active',
                ]
            );
        }

        // Independent Teachers
        $independentTeachers = [
            ['name' => 'John Smith', 'email' => 'john.smith@gmail.com'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@yahoo.com'],
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
        $this->command->info('🎓 Creating CPSU courses...');

        // Get departments for proper relationships
        $itDept = Department::where('department_name', 'Information Technology')->first();
        $csDept = Department::where('department_name', 'Computer Science')->first();
        $elemDept = Department::where('department_name', 'Elementary Education')->first();
        $agriDept = Department::where('department_name', 'Agricultural Engineering')->first();
        $busDept = Department::where('department_name', 'Business Administration')->first();
        $hospDept = Department::where('department_name', 'Hospitality Management')->first();
        $civilDept = Department::where('department_name', 'Civil Engineering')->first();

        // Main Campus Courses (Kabankalan)
        $mainCourses = [
            [
                'program_code' => 'BSIT-MAIN',
                'program_name' => 'Bachelor of Science in Information Technology',
                'department_id' => $itDept?->id,
                'campus' => 'CPSU Main Campus - Kabankalan City',
                'description' => 'CPSU Main Campus IT Program'
            ],
            [
                'program_code' => 'BSCS-MAIN',
                'program_name' => 'Bachelor of Science in Computer Science',
                'department_id' => $csDept?->id,
                'campus' => 'CPSU Main Campus - Kabankalan City',
                'description' => 'CPSU Main Campus CS Program'
            ],
            [
                'program_code' => 'BSAFE-MAIN',
                'program_name' => 'Bachelor of Science in Agriculture and Food Engineering',
                'department_id' => $agriDept?->id,
                'campus' => 'CPSU Main Campus - Kabankalan City',
                'description' => 'CPSU Main Campus Agriculture Program'
            ],
        ];

        // Victorias Campus Courses (Your campus)
        $victoriasCourses = [
            [
                'program_code' => 'BSIT-VIC',
                'program_name' => 'Bachelor of Science in Information Technology',
                'department_id' => $itDept?->id,
                'campus' => 'CPSU Victorias Campus',
                'description' => 'CPSU Victorias Campus IT Program'
            ],
            [
                'program_code' => 'BEED-VIC',
                'program_name' => 'Bachelor of Elementary Education',
                'department_id' => $elemDept?->id,
                'campus' => 'CPSU Victorias Campus',
                'description' => 'CPSU Victorias Campus Education Program'
            ],
            [
                'program_code' => 'BSHM-VIC',
                'program_name' => 'Bachelor of Science in Hospitality Management',
                'department_id' => $hospDept?->id,
                'campus' => 'CPSU Victorias Campus',
                'description' => 'CPSU Victorias Campus Hospitality Program'
            ],
        ];

        // Sipalay Campus Courses
        $sipalayCoures = [
            [
                'program_code' => 'BSAFE-SIP',
                'program_name' => 'Bachelor of Science in Agriculture and Food Engineering',
                'department_id' => $agriDept?->id,
                'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                'description' => 'CPSU Sipalay Campus Agriculture Program'
            ],
            [
                'program_code' => 'BSCE-SIP',
                'program_name' => 'Bachelor of Science in Civil Engineering',
                'department_id' => $civilDept?->id,
                'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                'description' => 'CPSU Sipalay Campus Civil Engineering Program'
            ],
        ];

        // Cauayan Campus Courses
        $cauayanCourses = [
            [
                'program_code' => 'BEED-CAU',
                'program_name' => 'Bachelor of Elementary Education',
                'department_id' => $elemDept?->id,
                'campus' => 'CPSU Cauayan Campus',
                'description' => 'CPSU Cauayan Campus Education Program'
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
        ];

        foreach (array_merge($mainCourses, $victoriasCourses, $sipalayCoures, $cauayanCourses, $independentCourses) as $course) {
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
        $this->command->info('📚 Creating CPSU subjects...');

        // Get courses for proper relationships
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bsitVic = Course::where('program_code', 'BSIT-VIC')->first();
        $beedVic = Course::where('program_code', 'BEED-VIC')->first();
        $bshmVic = Course::where('program_code', 'BSHM-VIC')->first();
        $bsafeSip = Course::where('program_code', 'BSAFE-SIP')->first();

        $subjects = [
            // Main Campus Subjects (Kabankalan)
            [
                'subject_code' => 'IT101-MAIN',
                'subject_name' => 'Introduction to Computing',
                'program_id' => $bsitMain?->id,
                'campus' => 'CPSU Main Campus - Kabankalan City',
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],
            [
                'subject_code' => 'PROG101-MAIN',
                'subject_name' => 'Programming Fundamentals',
                'program_id' => $bsitMain?->id,
                'campus' => 'CPSU Main Campus - Kabankalan City',
                'year_level' => 1,
                'semester' => '2',
                'category' => 'Major',
                'credit_hours' => 3,
            ],

            // Victorias Campus Subjects (Your campus)
            [
                'subject_code' => 'IT101-VIC',
                'subject_name' => 'Introduction to Computing',
                'program_id' => $bsitVic?->id,
                'campus' => 'CPSU Victorias Campus',
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],
            [
                'subject_code' => 'PROG101-VIC',
                'subject_name' => 'Programming Fundamentals',
                'program_id' => $bsitVic?->id,
                'campus' => 'CPSU Victorias Campus',
                'year_level' => 1,
                'semester' => '2',
                'category' => 'Major',
                'credit_hours' => 3,
            ],
            [
                'subject_code' => 'EDUC101-VIC',
                'subject_name' => 'Foundations of Education',
                'program_id' => $beedVic?->id,
                'campus' => 'CPSU Victorias Campus',
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],
            [
                'subject_code' => 'HM101-VIC',
                'subject_name' => 'Introduction to Hospitality Management',
                'program_id' => $bshmVic?->id,
                'campus' => 'CPSU Victorias Campus',
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],

            // Sipalay Campus Subjects
            [
                'subject_code' => 'AGRI101-SIP',
                'subject_name' => 'Introduction to Agriculture',
                'program_id' => $bsafeSip?->id,
                'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                'year_level' => 1,
                'semester' => '1',
                'category' => 'Major',
                'credit_hours' => 3,
            ],

            // General Education Subjects (Available across campuses)
            [
                'subject_code' => 'GE101',
                'subject_name' => 'Understanding the Self',
                'program_id' => null,
                'campus' => null,
                'year_level' => 1,
                'semester' => '1',
                'category' => 'General Education',
                'credit_hours' => 3,
            ],
            [
                'subject_code' => 'GE102',
                'subject_name' => 'Readings in Philippine History',
                'program_id' => null,
                'campus' => null,
                'year_level' => 1,
                'semester' => '2',
                'category' => 'General Education',
                'credit_hours' => 3,
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
        $this->command->info('👨‍🎓 Creating CPSU students...');

        // Main Campus Students (Kabankalan)
        for ($i = 1; $i <= 8; $i++) {
            $user = User::updateOrCreate(
                ['email' => "student{$i}.main@cpsu.edu.ph"],
                [
                    'name' => "Main Student {$i}",
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'campus' => 'CPSU Main Campus - Kabankalan City',
                    'campus_status' => 'approved',
                    'status' => 'Active',
                ]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => "2024-MAIN-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'year' => rand(1, 4),
                    'section' => ['A', 'B'][rand(0, 1)],
                    'status' => 'Active',
                    'campus' => 'CPSU Main Campus - Kabankalan City',
                ]
            );
        }

        // Victorias Campus Students (Your campus)
        for ($i = 1; $i <= 12; $i++) {
            $user = User::updateOrCreate(
                ['email' => "student{$i}.victorias@cpsu.edu.ph"],
                [
                    'name' => "Victorias Student {$i}",
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'campus' => 'CPSU Victorias Campus',
                    'campus_status' => 'approved',
                    'status' => 'Active',
                ]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => "2024-VIC-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'year' => rand(1, 4),
                    'section' => ['A', 'B', 'C'][rand(0, 2)],
                    'status' => 'Active',
                    'campus' => 'CPSU Victorias Campus',
                ]
            );
        }

        // Sipalay Campus Students
        for ($i = 1; $i <= 6; $i++) {
            $user = User::updateOrCreate(
                ['email' => "student{$i}.sipalay@cpsu.edu.ph"],
                [
                    'name' => "Sipalay Student {$i}",
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                    'campus_status' => 'approved',
                    'status' => 'Active',
                ]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => "2024-SIP-" . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'year' => rand(1, 4),
                    'section' => 'A',
                    'status' => 'Active',
                    'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                ]
            );
        }

        // Independent Students
        for ($i = 1; $i <= 3; $i++) {
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
        $this->command->info('🏫 Creating CPSU classes...');

        // Get teachers and courses
        $mainTeacher = User::where('email', 'maria.santos@cpsu.edu.ph')->first();
        $victoriasTeacher = User::where('email', 'roberto.garcia@cpsu.edu.ph')->first();
        $sipalayTeacher = User::where('email', 'carlos.mendoza@cpsu.edu.ph')->first();
        $indTeacher = User::where('email', 'john.smith@gmail.com')->first();
        
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bsitVic = Course::where('program_code', 'BSIT-VIC')->first();
        $beedVic = Course::where('program_code', 'BEED-VIC')->first();
        $bsafeSip = Course::where('program_code', 'BSAFE-SIP')->first();
        $wdbInd = Course::where('program_code', 'WDB-IND')->first();

        $classes = [
            // Main Campus Classes (Kabankalan)
            [
                'class_name' => 'BSIT 1-A Main Campus',
                'teacher_id' => $mainTeacher?->id,
                'course_id' => $bsitMain?->id,
                'class_level' => 1,
                'section' => 'A',
                'total_students' => 25,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'campus' => 'CPSU Main Campus - Kabankalan City',
            ],

            // Victorias Campus Classes (Your campus)
            [
                'class_name' => 'BSIT 1-A Victorias',
                'teacher_id' => $victoriasTeacher?->id,
                'course_id' => $bsitVic?->id,
                'class_level' => 1,
                'section' => 'A',
                'total_students' => 30,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'campus' => 'CPSU Victorias Campus',
            ],
            [
                'class_name' => 'BEED 1-A Victorias',
                'teacher_id' => $victoriasTeacher?->id,
                'course_id' => $beedVic?->id,
                'class_level' => 1,
                'section' => 'A',
                'total_students' => 28,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'campus' => 'CPSU Victorias Campus',
            ],

            // Sipalay Campus Classes
            [
                'class_name' => 'BSAFE 1-A Sipalay',
                'teacher_id' => $sipalayTeacher?->id,
                'course_id' => $bsafeSip?->id,
                'class_level' => 1,
                'section' => 'A',
                'total_students' => 20,
                'academic_year' => '2024-2025',
                'semester' => 'First',
                'status' => 'Active',
                'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
            ],

            // Independent Classes
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
        $this->command->info('📝 Creating CPSU course access requests...');

        // Get teachers and courses
        $mainTeacher = User::where('email', 'maria.santos@cpsu.edu.ph')->first();
        $victoriasTeacher = User::where('email', 'roberto.garcia@cpsu.edu.ph')->first();
        $pendingTeacher = User::where('email', 'lisa.fernandez@cpsu.edu.ph')->first();
        
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bsitVic = Course::where('program_code', 'BSIT-VIC')->first();
        $beedVic = Course::where('program_code', 'BEED-VIC')->first();

        $requests = [
            [
                'teacher_id' => $mainTeacher?->id,
                'course_id' => $bsitMain?->id,
                'status' => 'approved',
                'reason' => 'I am qualified to teach BSIT courses at Main Campus',
                'approved_by' => 2,
                'approved_at' => now(),
            ],
            [
                'teacher_id' => $victoriasTeacher?->id,
                'course_id' => $bsitVic?->id,
                'status' => 'approved',
                'reason' => 'I have experience teaching IT subjects at Victorias Campus',
                'approved_by' => 3,
                'approved_at' => now(),
            ],
            [
                'teacher_id' => $pendingTeacher?->id,
                'course_id' => $beedVic?->id,
                'status' => 'pending',
                'reason' => 'I would like to teach Education courses at Victorias Campus',
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
        $this->command->info("\n📊 CPSU SEEDER SUMMARY:");
        $this->command->info("======================");
        
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
        
        // Campus-specific counts
        $mainCampusStudents = Student::where('campus', 'CPSU Main Campus - Kabankalan City')->count();
        $victoriasCampusStudents = Student::where('campus', 'CPSU Victorias Campus')->count();
        $sipalayStudents = Student::where('campus', 'CPSU Sipalay Campus - Brgy. Gil Montilla')->count();
        
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
        
        $this->command->info("\n🏫 CAMPUS BREAKDOWN:");
        $this->command->info("Main Campus (Kabankalan): {$mainCampusStudents} students");
        $this->command->info("Victorias Campus: {$victoriasCampusStudents} students");
        $this->command->info("Sipalay Campus: {$sipalayStudents} students");
        
        $this->command->info("\n🧪 TEST ACCOUNTS:");
        $this->command->info("Super Admin: super@cpsu.edu.ph / super123");
        $this->command->info("Main Admin: admin.main@cpsu.edu.ph / admin123");
        $this->command->info("Victorias Admin: admin.victorias@cpsu.edu.ph / admin123");
        $this->command->info("Sipalay Admin: admin.sipalay@cpsu.edu.ph / admin123");
        $this->command->info("Main Teacher: maria.santos@cpsu.edu.ph / teacher123");
        $this->command->info("Victorias Teacher: roberto.garcia@cpsu.edu.ph / teacher123");
        $this->command->info("Pending Teacher: lisa.fernandez@cpsu.edu.ph / teacher123");
        $this->command->info("Victorias Student: student1.victorias@cpsu.edu.ph / student123");
        $this->command->info("Main Student: student1.main@cpsu.edu.ph / student123");
        
        $this->command->info("\n🎯 CPSU CAMPUSES INCLUDED:");
        $this->command->info("✅ Main Campus - Kabankalan City, Negros Occidental");
        $this->command->info("✅ Victorias Campus (Your campus)");
        $this->command->info("✅ Sipalay Campus - Brgy. Gil Montilla, Sipalay City");
        $this->command->info("✅ Cauayan Campus");
        $this->command->info("📝 Other campuses can be added: Candoni, Hinoba-an, Ilog, Hinigaran, Moises Padilla, San Carlos");
    }
}