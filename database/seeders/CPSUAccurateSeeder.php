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

        $mainAdmin = User::where('email', 'admin.main@cpsu.edu.ph')->first();
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
                    'campus_approved_by' => $teacher['status'] === 'approved' ? $mainAdmin?->id : null,
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

        $victoriasAdmin = User::where('email', 'admin.victorias@cpsu.edu.ph')->first();
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
                    'campus_approved_by' => $teacher['status'] === 'approved' ? $victoriasAdmin?->id : null,
                    'status' => 'Active',
                ]
            );
        }

        // Sipalay Campus Teachers
        $sipalayTeachers = [
            ['name' => 'Prof. Carlos Mendoza', 'email' => 'carlos.mendoza@cpsu.edu.ph', 'status' => 'approved'],
            ['name' => 'Dr. Elena Rodriguez', 'email' => 'elena.rodriguez@cpsu.edu.ph', 'status' => 'approved'],
        ];

        $sipalayAdmin = User::where('email', 'admin.sipalay@cpsu.edu.ph')->first();
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
                    'campus_approved_by' => $teacher['status'] === 'approved' ? $sipalayAdmin?->id : null,
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
        $this->command->info('📚 Creating CPSU subjects and assigning to teachers...');

        // Schools
        $mainSchool      = \App\Models\School::where('short_name', 'CPSU-Main')->first();
        $victoriasSchool = \App\Models\School::where('short_name', 'CPSU-Victorias')->first();
        $sipalaySchool   = \App\Models\School::where('short_name', 'CPSU-Sipalay')->first();

        // Courses
        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bscsMain = Course::where('program_code', 'BSCS-MAIN')->first();
        $bsafeMain = Course::where('program_code', 'BSAFE-MAIN')->first();
        $bsitVic  = Course::where('program_code', 'BSIT-VIC')->first();
        $beedVic  = Course::where('program_code', 'BEED-VIC')->first();
        $bshmVic  = Course::where('program_code', 'BSHM-VIC')->first();
        $bsafeSip = Course::where('program_code', 'BSAFE-SIP')->first();
        $bsceSip  = Course::where('program_code', 'BSCE-SIP')->first();

        $subjects = [
            // ── Main Campus ──────────────────────────────────────
            ['subject_code' => 'IT101-MAIN',   'subject_name' => 'Introduction to Computing',        'program_id' => $bsitMain?->id,  'campus' => 'CPSU Main Campus - Kabankalan City', 'school_id' => $mainSchool?->id,      'year_level' => 1, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'PROG101-MAIN', 'subject_name' => 'Programming Fundamentals',         'program_id' => $bsitMain?->id,  'campus' => 'CPSU Main Campus - Kabankalan City', 'school_id' => $mainSchool?->id,      'year_level' => 1, 'semester' => '2', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'CS101-MAIN',   'subject_name' => 'Discrete Mathematics',             'program_id' => $bscsMain?->id,  'campus' => 'CPSU Main Campus - Kabankalan City', 'school_id' => $mainSchool?->id,      'year_level' => 1, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'AGRI101-MAIN', 'subject_name' => 'Introduction to Agriculture',      'program_id' => $bsafeMain?->id, 'campus' => 'CPSU Main Campus - Kabankalan City', 'school_id' => $mainSchool?->id,      'year_level' => 1, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],

            // ── Victorias Campus ─────────────────────────────────
            ['subject_code' => 'IT101-VIC',    'subject_name' => 'Introduction to Computing',        'program_id' => $bsitVic?->id,   'campus' => 'CPSU Victorias Campus',              'school_id' => $victoriasSchool?->id, 'year_level' => 1, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'PROG101-VIC',  'subject_name' => 'Programming Fundamentals',         'program_id' => $bsitVic?->id,   'campus' => 'CPSU Victorias Campus',              'school_id' => $victoriasSchool?->id, 'year_level' => 1, 'semester' => '2', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'WEBDEV-VIC',   'subject_name' => 'Web Development',                  'program_id' => $bsitVic?->id,   'campus' => 'CPSU Victorias Campus',              'school_id' => $victoriasSchool?->id, 'year_level' => 2, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'DBMS-VIC',     'subject_name' => 'Database Management Systems',      'program_id' => $bsitVic?->id,   'campus' => 'CPSU Victorias Campus',              'school_id' => $victoriasSchool?->id, 'year_level' => 2, 'semester' => '2', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'EDUC101-VIC',  'subject_name' => 'Foundations of Education',         'program_id' => $beedVic?->id,   'campus' => 'CPSU Victorias Campus',              'school_id' => $victoriasSchool?->id, 'year_level' => 1, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'EDUC201-VIC',  'subject_name' => 'Child and Adolescent Development', 'program_id' => $beedVic?->id,   'campus' => 'CPSU Victorias Campus',              'school_id' => $victoriasSchool?->id, 'year_level' => 2, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'HM101-VIC',    'subject_name' => 'Introduction to Hospitality Mgmt', 'program_id' => $bshmVic?->id,   'campus' => 'CPSU Victorias Campus',              'school_id' => $victoriasSchool?->id, 'year_level' => 1, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],
            ['subject_code' => 'HM201-VIC',    'subject_name' => 'Food and Beverage Management',     'program_id' => $bshmVic?->id,   'campus' => 'CPSU Victorias Campus',              'school_id' => $victoriasSchool?->id, 'year_level' => 2, 'semester' => '1', 'category' => 'Major',            'credit_hours' => 3],

            // ── Sipalay Campus ───────────────────────────────────
            ['subject_code' => 'AGRI101-SIP',  'subject_name' => 'Introduction to Agriculture',      'program_id' => $bsafeSip?->id,  'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla', 'school_id' => $sipalaySchool?->id, 'year_level' => 1, 'semester' => '1', 'category' => 'Major',  'credit_hours' => 3],
            ['subject_code' => 'AGRI201-SIP',  'subject_name' => 'Soil Science and Management',      'program_id' => $bsafeSip?->id,  'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla', 'school_id' => $sipalaySchool?->id, 'year_level' => 2, 'semester' => '1', 'category' => 'Major',  'credit_hours' => 3],
            ['subject_code' => 'CE101-SIP',    'subject_name' => 'Engineering Drawing',              'program_id' => $bsceSip?->id,   'campus' => 'CPSU Sipalay Campus - Brgy. Gil Montilla', 'school_id' => $sipalaySchool?->id, 'year_level' => 1, 'semester' => '1', 'category' => 'Major',  'credit_hours' => 3],

            // ── General Education (campus-neutral) ───────────────
            ['subject_code' => 'GE101', 'subject_name' => 'Understanding the Self',          'program_id' => null, 'campus' => null, 'school_id' => null, 'year_level' => 1, 'semester' => '1', 'category' => 'General Education', 'credit_hours' => 3],
            ['subject_code' => 'GE102', 'subject_name' => 'Readings in Philippine History',  'program_id' => null, 'campus' => null, 'school_id' => null, 'year_level' => 1, 'semester' => '2', 'category' => 'General Education', 'credit_hours' => 3],
        ];

        foreach ($subjects as $s) {
            Subject::updateOrCreate(
                ['subject_code' => $s['subject_code']],
                [
                    'subject_name' => $s['subject_name'],
                    'program_id'   => $s['program_id'],
                    'campus'       => $s['campus'],
                    'school_id'    => $s['school_id'],
                    'year_level'   => $s['year_level'],
                    'semester'     => $s['semester'],
                    'category'     => $s['category'],
                    'credit_hours' => $s['credit_hours'],
                    'description'  => $s['subject_name'] . ' — Year ' . $s['year_level'] . ', Sem ' . $s['semester'],
                ]
            );
        }

        // ── Assign subjects to teachers via pivot ─────────────────
        $this->command->info('🔗 Assigning subjects to teachers...');

        $assignments = [
            // Main Campus teachers
            'maria.santos@cpsu.edu.ph'    => ['IT101-MAIN', 'PROG101-MAIN', 'GE101'],
            'juan.delacruz@cpsu.edu.ph'   => ['CS101-MAIN', 'PROG101-MAIN', 'GE102'],
            'ana.reyes@cpsu.edu.ph'       => ['AGRI101-MAIN', 'GE101', 'GE102'],

            // Victorias Campus teachers
            'roberto.garcia@cpsu.edu.ph'  => ['IT101-VIC', 'PROG101-VIC', 'WEBDEV-VIC', 'DBMS-VIC'],
            'carmen.lopez@cpsu.edu.ph'    => ['EDUC101-VIC', 'EDUC201-VIC', 'GE101'],
            'miguel.torres@cpsu.edu.ph'   => ['HM101-VIC', 'HM201-VIC', 'GE102'],
            'lisa.fernandez@cpsu.edu.ph'  => ['IT101-VIC', 'WEBDEV-VIC'],

            // Sipalay Campus teachers
            'carlos.mendoza@cpsu.edu.ph'  => ['AGRI101-SIP', 'AGRI201-SIP', 'GE101'],
            'elena.rodriguez@cpsu.edu.ph' => ['CE101-SIP', 'AGRI101-SIP', 'GE102'],

            // Independent teachers — GE subjects only
            'john.smith@gmail.com'        => ['GE101', 'GE102'],
            'sarah.johnson@yahoo.com'     => ['GE101', 'GE102'],
        ];

        foreach ($assignments as $email => $codes) {
            $teacher = User::where('email', $email)->first();
            if (!$teacher) continue;

            foreach ($codes as $code) {
                $subject = Subject::where('subject_code', $code)->first();
                if (!$subject) continue;

                // syncWithoutDetaching so re-seeding doesn't duplicate
                $teacher->subjects()->syncWithoutDetaching([
                    $subject->id => [
                        'status'      => 'active',
                        'assigned_at' => now(),
                    ],
                ]);
            }
        }
    }

    private function createStudents(): void
    {
        $this->command->info('👨‍🎓 Creating CPSU students with real names...');

        $mainSchool      = \App\Models\School::where('short_name', 'CPSU-Main')->first();
        $victoriasSchool = \App\Models\School::where('short_name', 'CPSU-Victorias')->first();
        $sipalaySchool   = \App\Models\School::where('short_name', 'CPSU-Sipalay')->first();

        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bscsMain = Course::where('program_code', 'BSCS-MAIN')->first();
        $bsafeMain = Course::where('program_code', 'BSAFE-MAIN')->first();

        $bsitVic  = Course::where('program_code', 'BSIT-VIC')->first();
        $beedVic  = Course::where('program_code', 'BEED-VIC')->first();
        $bshmVic  = Course::where('program_code', 'BSHM-VIC')->first();

        $bsafeSip = Course::where('program_code', 'BSAFE-SIP')->first();
        $bsceSip  = Course::where('program_code', 'BSCE-SIP')->first();

        // ── MAIN CAMPUS STUDENTS ──────────────────────────────────────────────
        $mainStudents = [
            ['first_name' => 'Jose',       'middle_name' => 'Rizal',      'last_name' => 'Dela Cruz',    'gender' => 'Male',   'year' => 1, 'section' => 'A', 'course' => $bsitMain],
            ['first_name' => 'Maria',      'middle_name' => 'Clara',      'last_name' => 'Santos',       'gender' => 'Female', 'year' => 1, 'section' => 'A', 'course' => $bsitMain],
            ['first_name' => 'Juan',       'middle_name' => 'Pablo',      'last_name' => 'Reyes',        'gender' => 'Male',   'year' => 2, 'section' => 'A', 'course' => $bscsMain],
            ['first_name' => 'Ana',        'middle_name' => 'Liza',       'last_name' => 'Garcia',       'gender' => 'Female', 'year' => 2, 'section' => 'B', 'course' => $bscsMain],
            ['first_name' => 'Pedro',      'middle_name' => 'Jose',       'last_name' => 'Mendoza',      'gender' => 'Male',   'year' => 3, 'section' => 'A', 'course' => $bsafeMain],
            ['first_name' => 'Luisa',      'middle_name' => 'Marie',      'last_name' => 'Torres',       'gender' => 'Female', 'year' => 3, 'section' => 'B', 'course' => $bsafeMain],
            ['first_name' => 'Ramon',      'middle_name' => 'Antonio',    'last_name' => 'Villanueva',   'gender' => 'Male',   'year' => 4, 'section' => 'A', 'course' => $bsitMain],
            ['first_name' => 'Cristina',   'middle_name' => 'Joy',        'last_name' => 'Aquino',       'gender' => 'Female', 'year' => 4, 'section' => 'A', 'course' => $bscsMain],
        ];

        foreach ($mainStudents as $i => $s) {
            $num = str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $slug = strtolower(str_replace(' ', '.', $s['last_name']));
            Student::updateOrCreate(
                ['student_id' => "2024-MAIN-{$num}"],
                [
                    'first_name'      => $s['first_name'],
                    'middle_name'     => $s['middle_name'],
                    'last_name'       => $s['last_name'],
                    'email'           => "{$slug}.main{$num}@cpsu.edu.ph",
                    'password'        => Hash::make('student123'),
                    'gender'          => $s['gender'],
                    'year'            => $s['year'],
                    'year_level'      => $s['year'],
                    'section'         => $s['section'],
                    'course_id'       => $s['course']?->id,
                    'department'      => $s['course']?->program_code,
                    'status'          => 'Active',
                    'campus'          => 'CPSU Main Campus - Kabankalan City',
                    'school_id'       => $mainSchool?->id,
                    'enrollment_date' => '2024-08-01',
                    'academic_year'   => '2024-2025',
                ]
            );
        }

        // ── VICTORIAS CAMPUS STUDENTS ─────────────────────────────────────────
        $victoriasStudents = [
            ['first_name' => 'Rodrigo',    'middle_name' => 'Andres',     'last_name' => 'Fernandez',    'gender' => 'Male',   'year' => 1, 'section' => 'A', 'course' => $bsitVic],
            ['first_name' => 'Maricel',    'middle_name' => 'Grace',      'last_name' => 'Lopez',        'gender' => 'Female', 'year' => 1, 'section' => 'A', 'course' => $bsitVic],
            ['first_name' => 'Danilo',     'middle_name' => 'Cruz',       'last_name' => 'Ramos',        'gender' => 'Male',   'year' => 1, 'section' => 'B', 'course' => $beedVic],
            ['first_name' => 'Rosario',    'middle_name' => 'Luz',        'last_name' => 'Castillo',     'gender' => 'Female', 'year' => 1, 'section' => 'B', 'course' => $beedVic],
            ['first_name' => 'Eduardo',    'middle_name' => 'Manuel',     'last_name' => 'Bautista',     'gender' => 'Male',   'year' => 2, 'section' => 'A', 'course' => $bsitVic],
            ['first_name' => 'Lourdes',    'middle_name' => 'Faith',      'last_name' => 'Navarro',      'gender' => 'Female', 'year' => 2, 'section' => 'A', 'course' => $bshmVic],
            ['first_name' => 'Renato',     'middle_name' => 'Dario',      'last_name' => 'Morales',      'gender' => 'Male',   'year' => 2, 'section' => 'B', 'course' => $bshmVic],
            ['first_name' => 'Teresita',   'middle_name' => 'Ann',        'last_name' => 'Pascual',      'gender' => 'Female', 'year' => 3, 'section' => 'A', 'course' => $beedVic],
            ['first_name' => 'Alfredo',    'middle_name' => 'Luis',       'last_name' => 'Soriano',      'gender' => 'Male',   'year' => 3, 'section' => 'B', 'course' => $bsitVic],
            ['first_name' => 'Natividad',  'middle_name' => 'Rose',       'last_name' => 'Domingo',      'gender' => 'Female', 'year' => 3, 'section' => 'C', 'course' => $bshmVic],
            ['first_name' => 'Bernardo',   'middle_name' => 'King',       'last_name' => 'Aguilar',      'gender' => 'Male',   'year' => 4, 'section' => 'A', 'course' => $bsitVic],
            ['first_name' => 'Corazon',    'middle_name' => 'Hope',       'last_name' => 'Salazar',      'gender' => 'Female', 'year' => 4, 'section' => 'A', 'course' => $beedVic],
        ];

        foreach ($victoriasStudents as $i => $s) {
            $num = str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $slug = strtolower(str_replace(' ', '.', $s['last_name']));
            Student::updateOrCreate(
                ['student_id' => "2024-VIC-{$num}"],
                [
                    'first_name'      => $s['first_name'],
                    'middle_name'     => $s['middle_name'],
                    'last_name'       => $s['last_name'],
                    'email'           => "{$slug}.vic{$num}@cpsu.edu.ph",
                    'password'        => Hash::make('student123'),
                    'gender'          => $s['gender'],
                    'year'            => $s['year'],
                    'year_level'      => $s['year'],
                    'section'         => $s['section'],
                    'course_id'       => $s['course']?->id,
                    'department'      => $s['course']?->program_code,
                    'status'          => 'Active',
                    'campus'          => 'CPSU Victorias Campus',
                    'school_id'       => $victoriasSchool?->id,
                    'enrollment_date' => '2024-08-01',
                    'academic_year'   => '2024-2025',
                ]
            );
        }

        // ── SIPALAY CAMPUS STUDENTS ───────────────────────────────────────────
        $sipalayStudents = [
            ['first_name' => 'Ernesto',    'middle_name' => 'Delos',      'last_name' => 'Santos',       'gender' => 'Male',   'year' => 1, 'section' => 'A', 'course' => $bsafeSip],
            ['first_name' => 'Florencia',  'middle_name' => 'Mae',        'last_name' => 'Reyes',        'gender' => 'Female', 'year' => 1, 'section' => 'A', 'course' => $bsafeSip],
            ['first_name' => 'Gregorio',   'middle_name' => 'Paul',       'last_name' => 'Cruz',         'gender' => 'Male',   'year' => 2, 'section' => 'A', 'course' => $bsceSip],
            ['first_name' => 'Herminia',   'middle_name' => 'Joy',        'last_name' => 'Villanueva',   'gender' => 'Female', 'year' => 2, 'section' => 'A', 'course' => $bsceSip],
            ['first_name' => 'Isidro',     'middle_name' => 'Mark',       'last_name' => 'Dela Rosa',    'gender' => 'Male',   'year' => 3, 'section' => 'A', 'course' => $bsafeSip],
            ['first_name' => 'Josefina',   'middle_name' => 'Claire',     'last_name' => 'Macaraeg',     'gender' => 'Female', 'year' => 3, 'section' => 'A', 'course' => $bsceSip],
        ];

        foreach ($sipalayStudents as $i => $s) {
            $num = str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $slug = strtolower(str_replace(' ', '.', $s['last_name']));
            Student::updateOrCreate(
                ['student_id' => "2024-SIP-{$num}"],
                [
                    'first_name'      => $s['first_name'],
                    'middle_name'     => $s['middle_name'],
                    'last_name'       => $s['last_name'],
                    'email'           => "{$slug}.sip{$num}@cpsu.edu.ph",
                    'password'        => Hash::make('student123'),
                    'gender'          => $s['gender'],
                    'year'            => $s['year'],
                    'year_level'      => $s['year'],
                    'section'         => $s['section'],
                    'course_id'       => $s['course']?->id,
                    'department'      => $s['course']?->program_code,
                    'status'          => 'Active',
                    'campus'          => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                    'school_id'       => $sipalaySchool?->id,
                    'enrollment_date' => '2024-08-01',
                    'academic_year'   => '2024-2025',
                ]
            );
        }

        // ── INDEPENDENT STUDENTS ──────────────────────────────────────────────
        $independentStudents = [
            ['first_name' => 'Kevin',      'middle_name' => 'James',      'last_name' => 'Tan',          'gender' => 'Male',   'year' => 1, 'section' => 'A'],
            ['first_name' => 'Patricia',   'middle_name' => 'Anne',       'last_name' => 'Lim',          'gender' => 'Female', 'year' => 1, 'section' => 'A'],
            ['first_name' => 'Michael',    'middle_name' => 'John',       'last_name' => 'Go',           'gender' => 'Male',   'year' => 2, 'section' => 'A'],
        ];

        foreach ($independentStudents as $i => $s) {
            $num = str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $slug = strtolower($s['last_name']);
            Student::updateOrCreate(
                ['student_id' => "2024-IND-{$num}"],
                [
                    'first_name'      => $s['first_name'],
                    'middle_name'     => $s['middle_name'],
                    'last_name'       => $s['last_name'],
                    'email'           => "{$slug}.ind{$num}@gmail.com",
                    'password'        => Hash::make('student123'),
                    'gender'          => $s['gender'],
                    'year'            => $s['year'],
                    'year_level'      => $s['year'],
                    'section'         => $s['section'],
                    'status'          => 'Active',
                    'campus'          => null,
                    'school_id'       => null,
                    'enrollment_date' => '2024-08-01',
                    'academic_year'   => '2024-2025',
                ]
            );
        }
    }
    private function createClasses(): void
    {
        $this->command->info('🏫 Creating CPSU classes...');

        $mainTeacher      = User::where('email', 'maria.santos@cpsu.edu.ph')->first();
        $victoriasTeacher = User::where('email', 'roberto.garcia@cpsu.edu.ph')->first();
        $sipalayTeacher   = User::where('email', 'carlos.mendoza@cpsu.edu.ph')->first();
        $indTeacher       = User::where('email', 'john.smith@gmail.com')->first();

        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bsitVic  = Course::where('program_code', 'BSIT-VIC')->first();
        $beedVic  = Course::where('program_code', 'BEED-VIC')->first();
        $bsafeSip = Course::where('program_code', 'BSAFE-SIP')->first();
        $wdbInd   = Course::where('program_code', 'WDB-IND')->first();

        // Subjects for Victorias
        $it101Vic  = Subject::where('subject_code', 'IT101-VIC')->first();
        $educ101   = Subject::where('subject_code', 'EDUC101-VIC')->first();
        $it101Main = Subject::where('subject_code', 'IT101-MAIN')->first();
        $agri101   = Subject::where('subject_code', 'AGRI101-SIP')->first();

        $classes = [
            [
                'class_name'    => 'BSIT 1-A Main Campus',
                'teacher_id'    => $mainTeacher?->id,
                'course_id'     => $bsitMain?->id,
                'subject_id'    => $it101Main?->id,
                'class_level'   => 1,
                'section'       => 'A',
                'total_students'=> 25,
                'academic_year' => '2024-2025',
                'semester'      => 'First',
                'status'        => 'Active',
                'campus'        => 'CPSU Main Campus - Kabankalan City',
            ],
            [
                'class_name'    => 'BSIT 1-A Victorias',
                'teacher_id'    => $victoriasTeacher?->id,
                'course_id'     => $bsitVic?->id,
                'subject_id'    => $it101Vic?->id,
                'class_level'   => 1,
                'section'       => 'A',
                'total_students'=> 30,
                'academic_year' => '2024-2025',
                'semester'      => 'First',
                'status'        => 'Active',
                'campus'        => 'CPSU Victorias Campus',
            ],
            [
                'class_name'    => 'BEED 1-A Victorias',
                'teacher_id'    => $victoriasTeacher?->id,
                'course_id'     => $beedVic?->id,
                'subject_id'    => $educ101?->id,
                'class_level'   => 1,
                'section'       => 'A',
                'total_students'=> 28,
                'academic_year' => '2024-2025',
                'semester'      => 'First',
                'status'        => 'Active',
                'campus'        => 'CPSU Victorias Campus',
            ],
            [
                'class_name'    => 'BSAFE 1-A Sipalay',
                'teacher_id'    => $sipalayTeacher?->id,
                'course_id'     => $bsafeSip?->id,
                'subject_id'    => $agri101?->id,
                'class_level'   => 1,
                'section'       => 'A',
                'total_students'=> 20,
                'academic_year' => '2024-2025',
                'semester'      => 'First',
                'status'        => 'Active',
                'campus'        => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
            ],
            [
                'class_name'    => 'Web Dev Bootcamp Batch 1',
                'teacher_id'    => $indTeacher?->id,
                'course_id'     => $wdbInd?->id,
                'subject_id'    => null,
                'class_level'   => 1,
                'section'       => 'A',
                'total_students'=> 15,
                'academic_year' => '2024-2025',
                'semester'      => 'First',
                'status'        => 'Active',
                'campus'        => null,
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

        $mainTeacher      = User::where('email', 'maria.santos@cpsu.edu.ph')->first();
        $victoriasTeacher = User::where('email', 'roberto.garcia@cpsu.edu.ph')->first();
        $pendingTeacher   = User::where('email', 'lisa.fernandez@cpsu.edu.ph')->first();
        $mainAdmin        = User::where('email', 'admin.main@cpsu.edu.ph')->first();
        $victoriasAdmin   = User::where('email', 'admin.victorias@cpsu.edu.ph')->first();

        $bsitMain = Course::where('program_code', 'BSIT-MAIN')->first();
        $bsitVic  = Course::where('program_code', 'BSIT-VIC')->first();
        $beedVic  = Course::where('program_code', 'BEED-VIC')->first();

        $requests = [
            [
                'teacher_id'  => $mainTeacher?->id,
                'course_id'   => $bsitMain?->id,
                'status'      => 'approved',
                'reason'      => 'I am qualified to teach BSIT courses at Main Campus',
                'approved_by' => $mainAdmin?->id,
                'approved_at' => now(),
            ],
            [
                'teacher_id'  => $victoriasTeacher?->id,
                'course_id'   => $bsitVic?->id,
                'status'      => 'approved',
                'reason'      => 'I have experience teaching IT subjects at Victorias Campus',
                'approved_by' => $victoriasAdmin?->id,
                'approved_at' => now(),
            ],
            [
                'teacher_id' => $pendingTeacher?->id,
                'course_id'  => $beedVic?->id,
                'status'     => 'pending',
                'reason'     => 'I would like to teach Education courses at Victorias Campus',
            ],
        ];

        foreach ($requests as $request) {
            if ($request['teacher_id'] && $request['course_id']) {
                CourseAccessRequest::updateOrCreate(
                    ['teacher_id' => $request['teacher_id'], 'course_id' => $request['course_id']],
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