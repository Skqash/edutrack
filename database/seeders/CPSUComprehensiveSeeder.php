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

class CPSUComprehensiveSeeder extends Seeder
{
    /**
     * Comprehensive CPSU seeder with all programs and subjects
     */
    public function run(): void
    {
        $this->command->info('🏫 Starting CPSU Comprehensive Seeder...');

        $this->createCollegesAndDepartments();
        $this->createAdminUsers();
        $this->createTeachers();
        $this->createCourses();
        $this->createSubjects();
        $this->createStudents();
        $this->createClasses();
        $this->createCourseAccessRequests();
        
        $this->command->info('✅ CPSU Comprehensive Seeder completed successfully!');
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
            ['name' => 'Physical Education', 'college_index' => 1],
            ['name' => 'Early Childhood Education', 'college_index' => 1],
            ['name' => 'Agricultural Engineering', 'college_index' => 2],
            ['name' => 'Food Engineering', 'college_index' => 2],
            ['name' => 'Agriculture', 'college_index' => 2],
            ['name' => 'Animal Science', 'college_index' => 2],
            ['name' => 'Agribusiness', 'college_index' => 2],
            ['name' => 'Forestry', 'college_index' => 2],
            ['name' => 'Sugar Technology', 'college_index' => 2],
            ['name' => 'Business Administration', 'college_index' => 3],
            ['name' => 'Hospitality Management', 'college_index' => 3],
            ['name' => 'Civil Engineering', 'college_index' => 4],
            ['name' => 'Mechanical Engineering', 'college_index' => 4],
            ['name' => 'Electrical Engineering', 'college_index' => 4],
            ['name' => 'Agricultural and Biosystems Engineering', 'college_index' => 4],
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
        $this->command->info('👨‍💼 Creating admin users...');

        // Super Admin
        User::updateOrCreate(
            ['email' => 'super@cpsu.edu.ph'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('super123'),
                'role' => 'super_admin',
                'status' => 'Active',
                'campus' => null,
                'campus_status' => 'approved',
            ]
        );

        // Campus Admins
        $campusAdmins = [
            ['email' => 'admin.kabankalan@cpsu.edu.ph', 'name' => 'CPSU Main Campus Admin (Kabankalan)', 'campus' => 'CPSU Main Campus - Kabankalan'],
            ['email' => 'admin.victorias@cpsu.edu.ph', 'name' => 'CPSU Victorias Campus Admin', 'campus' => 'CPSU Victorias Campus'],
            ['email' => 'admin.sipalay@cpsu.edu.ph', 'name' => 'CPSU Sipalay Campus Admin', 'campus' => 'CPSU Sipalay Campus'],
        ];

        foreach ($campusAdmins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                    'status' => 'Active',
                    'campus' => $admin['campus'],
                    'campus_status' => 'approved',
                ]
            );
        }
    }

    private function createTeachers(): void
    {
        $this->command->info('👨‍🏫 Creating teachers...');

        $teachers = [
            // CPSU Victorias Campus Teachers (Priority - where you study)
            ['name' => 'Maria Santos', 'email' => 'maria.santos@cpsu.edu.ph', 'campus' => 'CPSU Victorias Campus', 'status' => 'approved'],
            ['name' => 'Juan Dela Cruz', 'email' => 'juan.delacruz@cpsu.edu.ph', 'campus' => 'CPSU Victorias Campus', 'status' => 'approved'],
            ['name' => 'Ana Reyes', 'email' => 'ana.reyes@cpsu.edu.ph', 'campus' => 'CPSU Victorias Campus', 'status' => 'pending'],
            ['name' => 'Carlos Mendoza', 'email' => 'carlos.mendoza@cpsu.edu.ph', 'campus' => 'CPSU Victorias Campus', 'status' => 'approved'],
            
            // CPSU Main Campus Teachers (Kabankalan)
            ['name' => 'Roberto Garcia', 'email' => 'roberto.garcia@cpsu.edu.ph', 'campus' => 'CPSU Main Campus - Kabankalan', 'status' => 'approved'],
            ['name' => 'Carmen Lopez', 'email' => 'carmen.lopez@cpsu.edu.ph', 'campus' => 'CPSU Main Campus - Kabankalan', 'status' => 'approved'],
            
            // Independent Teachers
            ['name' => 'John Smith', 'email' => 'john.smith@gmail.com', 'campus' => null, 'status' => 'approved'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@yahoo.com', 'campus' => null, 'status' => 'approved'],
        ];

        foreach ($teachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'status' => 'Active',
                    'campus' => $teacher['campus'],
                    'campus_status' => $teacher['status'],
                ]
            );
        }
    }

    private function createCourses(): void
    {
        $this->command->info('📚 Creating CPSU courses/programs...');

        $courses = [
            // CPSU Victorias Campus Programs (Priority - where you study)
            ['code' => 'BSIT-VIC', 'name' => 'Bachelor of Science in Information Technology', 'campus' => 'CPSU Victorias Campus', 'college' => 'College of Computer Studies', 'department' => 'Information Technology'],
            ['code' => 'BSAGRI-BUS-VIC', 'name' => 'Bachelor of Science in Agribusiness', 'campus' => 'CPSU Victorias Campus', 'college' => 'College of Agriculture and Food Engineering', 'department' => 'Agribusiness'],
            ['code' => 'BEED-VIC', 'name' => 'Bachelor in Elementary Education', 'campus' => 'CPSU Victorias Campus', 'college' => 'College of Education', 'department' => 'Elementary Education'],
            ['code' => 'BSHM-VIC', 'name' => 'Bachelor of Science in Hotel & Restaurant Management', 'campus' => 'CPSU Victorias Campus', 'college' => 'College of Business and Management', 'department' => 'Hospitality Management'],
            ['code' => 'BSED-VIC', 'name' => 'Bachelor in Secondary Education', 'campus' => 'CPSU Victorias Campus', 'college' => 'College of Education', 'department' => 'Secondary Education'],
            ['code' => 'BPED-VIC', 'name' => 'Bachelor in Physical Education', 'campus' => 'CPSU Victorias Campus', 'college' => 'College of Education', 'department' => 'Physical Education'],
            ['code' => 'BECED-VIC', 'name' => 'Bachelor in Early Childhood Education', 'campus' => 'CPSU Victorias Campus', 'college' => 'College of Education', 'department' => 'Early Childhood Education'],
            ['code' => 'BSAGRI-VIC', 'name' => 'Bachelor of Science in Agriculture', 'campus' => 'CPSU Victorias Campus', 'college' => 'College of Agriculture and Food Engineering', 'department' => 'Agriculture'],
            
            // CPSU Main Campus Programs (Kabankalan)
            ['code' => 'BSIT-KAB', 'name' => 'Bachelor of Science in Information Technology', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Computer Studies', 'department' => 'Information Technology'],
            ['code' => 'BSAS-KAB', 'name' => 'Bachelor in Animal Science', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Agriculture and Food Engineering', 'department' => 'Animal Science'],
            ['code' => 'BSF-KAB', 'name' => 'Bachelor of Science in Forestry', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Agriculture and Food Engineering', 'department' => 'Forestry'],
            ['code' => 'BST-KAB', 'name' => 'Bachelor in Sugar Technology', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Agriculture and Food Engineering', 'department' => 'Sugar Technology'],
            ['code' => 'AB-ENG-KAB', 'name' => 'Bachelor of Arts in English', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Education', 'department' => 'Secondary Education'],
            ['code' => 'AB-SS-KAB', 'name' => 'Bachelor of Arts in Social Science', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Education', 'department' => 'Secondary Education'],
            ['code' => 'BSAS-STAT-KAB', 'name' => 'Bachelor of Science in Applied Statistics', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Computer Studies', 'department' => 'Computer Science'],
            ['code' => 'BSCRIM-KAB', 'name' => 'Bachelor of Science in Criminology', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Business and Management', 'department' => 'Business Administration'],
            ['code' => 'BSABE-KAB', 'name' => 'Bachelor of Science in Agricultural and Biosystems Engineering', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Engineering', 'department' => 'Agricultural and Biosystems Engineering'],
            ['code' => 'BSME-KAB', 'name' => 'Bachelor of Science in Mechanical Engineering', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Engineering', 'department' => 'Mechanical Engineering'],
            ['code' => 'BSEE-KAB', 'name' => 'Bachelor of Science in Electrical Engineering', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Engineering', 'department' => 'Electrical Engineering'],
            ['code' => 'BSED-ENG-KAB', 'name' => 'Bachelor in Secondary Education major in English', 'campus' => 'CPSU Main Campus - Kabankalan', 'college' => 'College of Education', 'department' => 'Secondary Education'],
            
            // Independent Programs
            ['code' => 'IND-PROG', 'name' => 'Independent Studies Program', 'campus' => null, 'college' => 'Independent', 'department' => 'Independent'],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['program_code' => $course['code']],
                [
                    'program_name' => $course['name'],
                    'course_code' => $course['code'], // Add this field
                    'description' => $course['name'] . ' - ' . ($course['campus'] ?? 'Independent'),
                    'total_years' => 4,
                    'status' => 'Active',
                    'campus' => $course['campus'],
                    'college' => $course['college'],
                    'department' => $course['department'],
                ]
            );
        }
    }
    private function createSubjects(): void
    {
        $this->command->info('📖 Creating subjects for all programs...');

        // Get all courses
        $courses = Course::all()->keyBy('program_code');

        // BSIT Subjects (4 years, 2 semesters each)
        $bsitSubjects = [
            // First Year
            ['code' => 'CCIT01', 'name' => 'Introduction to Computing', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'CCIT02', 'name' => 'Computer Programming 1', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'MATH01', 'name' => 'Mathematics in the Modern World', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'General Education'],
            ['code' => 'COMM01', 'name' => 'Purposive Communication', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'General Education'],
            ['code' => 'NSTP01', 'name' => 'National Service Training Program 1', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'NSTP'],
            
            ['code' => 'PCIT03', 'name' => 'Integrative Programming and Technologies 1', 'year' => 1, 'sem' => 'Second', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'CCIT04', 'name' => 'Computer Programming 2 / OOP', 'year' => 1, 'sem' => 'Second', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'PHIL01', 'name' => 'Understanding the Self', 'year' => 1, 'sem' => 'Second', 'credits' => 3, 'category' => 'General Education'],
            ['code' => 'NSTP02', 'name' => 'National Service Training Program 2', 'year' => 1, 'sem' => 'Second', 'credits' => 3, 'category' => 'NSTP'],
            
            // Second Year
            ['code' => 'WEBT01', 'name' => 'Web System Technologies', 'year' => 2, 'sem' => 'First', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'DSAL01', 'name' => 'Data Structures & Algorithms', 'year' => 2, 'sem' => 'First', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'NET01', 'name' => 'Networking 1', 'year' => 2, 'sem' => 'First', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'HIST01', 'name' => 'Readings in Philippine History', 'year' => 2, 'sem' => 'First', 'credits' => 3, 'category' => 'General Education'],
            
            ['code' => 'PCIT04', 'name' => 'Integrative Programming and Technologies 2', 'year' => 2, 'sem' => 'Second', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'DBMS01', 'name' => 'Database Management Systems', 'year' => 2, 'sem' => 'Second', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'RIZAL01', 'name' => 'Life and Works of Rizal', 'year' => 2, 'sem' => 'Second', 'credits' => 3, 'category' => 'General Education'],
            
            // Third Year
            ['code' => 'IAS01', 'name' => 'Information Assurance and Security 1', 'year' => 3, 'sem' => 'First', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'SIA01', 'name' => 'Systems Integration and Architecture', 'year' => 3, 'sem' => 'First', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'OS01', 'name' => 'Operating Systems', 'year' => 3, 'sem' => 'First', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'ITEL01', 'name' => 'IT Electives 1', 'year' => 3, 'sem' => 'First', 'credits' => 3, 'category' => 'Elective'],
            
            ['code' => 'IAS02', 'name' => 'Information Assurance and Security 2', 'year' => 3, 'sem' => 'Second', 'credits' => 3, 'category' => 'Major'],
            ['code' => 'ITEL02', 'name' => 'IT Electives 2', 'year' => 3, 'sem' => 'Second', 'credits' => 3, 'category' => 'Elective'],
            ['code' => 'ITEL03', 'name' => 'IT Electives 3', 'year' => 3, 'sem' => 'Second', 'credits' => 3, 'category' => 'Elective'],
            
            // Fourth Year
            ['code' => 'CAPS01', 'name' => 'Capstone Project 1', 'year' => 4, 'sem' => 'First', 'credits' => 3, 'category' => 'Capstone'],
            ['code' => 'ITEL04', 'name' => 'IT Professional Electives 1', 'year' => 4, 'sem' => 'First', 'credits' => 3, 'category' => 'Elective'],
            ['code' => 'ITEL05', 'name' => 'IT Professional Electives 2', 'year' => 4, 'sem' => 'First', 'credits' => 3, 'category' => 'Elective'],
            
            ['code' => 'CAPS02', 'name' => 'Capstone Project 2', 'year' => 4, 'sem' => 'Second', 'credits' => 3, 'category' => 'Capstone'],
            ['code' => 'OJT01', 'name' => 'Internship/On-the-Job Training', 'year' => 4, 'sem' => 'Second', 'credits' => 6, 'category' => 'Practicum'],
        ];

        // Create BSIT subjects for both campuses
        foreach (['BSIT', 'BSIT-BAY'] as $programCode) {
            if (isset($courses[$programCode])) {
                foreach ($bsitSubjects as $subject) {
                    Subject::updateOrCreate(
                        [
                            'subject_code' => $subject['code'] . '-' . $programCode,
                            'program_id' => $courses[$programCode]->id
                        ],
                        [
                            'subject_name' => $subject['name'],
                            'category' => $subject['category'],
                            'credit_hours' => $subject['credits'],
                            'year_level' => $subject['year'],
                            'semester' => $subject['sem'],
                            'campus' => $courses[$programCode]->campus,
                            'description' => $subject['name'] . ' for ' . $courses[$programCode]->program_name,
                        ]
                    );
                }
            }
        }

        // BEED Subjects
        $beedSubjects = [
            // First Year
            ['code' => 'EDUC01', 'name' => 'The Child and Adolescent Learners', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'EDUC02', 'name' => 'Childhood and Adolescent Development', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'MATH01', 'name' => 'Mathematics in the Modern World', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'General Education'],
            ['code' => 'COMM01', 'name' => 'Purposive Communication', 'year' => 1, 'sem' => 'First', 'credits' => 3, 'category' => 'General Education'],
            
            ['code' => 'EDUC03', 'name' => 'Principles of Teaching', 'year' => 1, 'sem' => 'Second', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'EDUC04', 'name' => 'Foundation of Special and Inclusive Education', 'year' => 1, 'sem' => 'Second', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'PHIL01', 'name' => 'Understanding the Self', 'year' => 1, 'sem' => 'Second', 'credits' => 3, 'category' => 'General Education'],
            
            // Second Year
            ['code' => 'EDUC05', 'name' => 'Facilitating Learner-Centered Teaching', 'year' => 2, 'sem' => 'First', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'EDUC06', 'name' => 'The Teacher and the School Curriculum', 'year' => 2, 'sem' => 'First', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'HIST01', 'name' => 'Readings in Philippine History', 'year' => 2, 'sem' => 'First', 'credits' => 3, 'category' => 'General Education'],
            
            ['code' => 'EDUC07', 'name' => 'Assessment in Learning 1', 'year' => 2, 'sem' => 'Second', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'EDUC08', 'name' => 'Technology for Teaching and Learning', 'year' => 2, 'sem' => 'Second', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'RIZAL01', 'name' => 'Life and Works of Rizal', 'year' => 2, 'sem' => 'Second', 'credits' => 3, 'category' => 'General Education'],
            
            // Third Year
            ['code' => 'EDUC09', 'name' => 'Assessment in Learning 2', 'year' => 3, 'sem' => 'First', 'credits' => 3, 'category' => 'Professional Education'],
            ['code' => 'EDUC10', 'name' => 'Teaching Mathematics in Elementary Grades', 'year' => 3, 'sem' => 'First', 'credits' => 3, 'category' => 'Content Courses'],
            ['code' => 'EDUC11', 'name' => 'Teaching Science in Elementary Grades', 'year' => 3, 'sem' => 'First', 'credits' => 3, 'category' => 'Content Courses'],
            
            ['code' => 'EDUC12', 'name' => 'Teaching Filipino in Elementary Grades', 'year' => 3, 'sem' => 'Second', 'credits' => 3, 'category' => 'Content Courses'],
            ['code' => 'EDUC13', 'name' => 'Teaching English in Elementary Grades', 'year' => 3, 'sem' => 'Second', 'credits' => 3, 'category' => 'Content Courses'],
            ['code' => 'EDUC14', 'name' => 'Teaching Social Studies in Elementary Grades', 'year' => 3, 'sem' => 'Second', 'credits' => 3, 'category' => 'Content Courses'],
            
            // Fourth Year
            ['code' => 'EDUC15', 'name' => 'Field Study 1', 'year' => 4, 'sem' => 'First', 'credits' => 3, 'category' => 'Field Study'],
            ['code' => 'EDUC16', 'name' => 'Field Study 2', 'year' => 4, 'sem' => 'First', 'credits' => 3, 'category' => 'Field Study'],
            
            ['code' => 'EDUC17', 'name' => 'Teaching Internship', 'year' => 4, 'sem' => 'Second', 'credits' => 6, 'category' => 'Practicum'],
        ];

        // Create BEED subjects for both campuses
        foreach (['BEED', 'BEED-BAY'] as $programCode) {
            if (isset($courses[$programCode])) {
                foreach ($beedSubjects as $subject) {
                    Subject::updateOrCreate(
                        [
                            'subject_code' => $subject['code'] . '-' . $programCode,
                            'program_id' => $courses[$programCode]->id
                        ],
                        [
                            'subject_name' => $subject['name'],
                            'category' => $subject['category'],
                            'credit_hours' => $subject['credits'],
                            'year_level' => $subject['year'],
                            'semester' => $subject['sem'],
                            'campus' => $courses[$programCode]->campus,
                            'description' => $subject['name'] . ' for ' . $courses[$programCode]->program_name,
                        ]
                    );
                }
            }
        }
    }
    private function createStudents(): void
    {
        $this->command->info('👨‍🎓 Creating students...');

        $courses = Course::all()->keyBy('program_code');
        
        // CPSU Main Campus Students
        $mainCampusStudents = [
            ['first_name' => 'Alice', 'middle_name' => 'Marie', 'last_name' => 'Johnson', 'email' => 'alice.johnson@student.cpsu.edu.ph', 'student_id' => '2024-001', 'program' => 'BSIT', 'year' => 1, 'phone' => '09123456789', 'gender' => 'Female'],
            ['first_name' => 'Bob', 'middle_name' => 'James', 'last_name' => 'Smith', 'email' => 'bob.smith@student.cpsu.edu.ph', 'student_id' => '2024-002', 'program' => 'BSIT', 'year' => 2, 'phone' => '09123456790', 'gender' => 'Male'],
            ['first_name' => 'Charlie', 'middle_name' => null, 'last_name' => 'Brown', 'email' => 'charlie.brown@student.cpsu.edu.ph', 'student_id' => '2024-003', 'program' => 'BEED', 'year' => 1, 'phone' => '09123456791', 'gender' => 'Male'],
            ['first_name' => 'Diana', 'middle_name' => 'Grace', 'last_name' => 'Prince', 'email' => 'diana.prince@student.cpsu.edu.ph', 'student_id' => '2024-004', 'program' => 'BEED', 'year' => 2, 'phone' => '09123456792', 'gender' => 'Female'],
            ['first_name' => 'Edward', 'middle_name' => 'Paul', 'last_name' => 'Norton', 'email' => 'edward.norton@student.cpsu.edu.ph', 'student_id' => '2024-005', 'program' => 'BSAGRI-BUS', 'year' => 1, 'phone' => '09123456793', 'gender' => 'Male'],
            ['first_name' => 'Fiona', 'middle_name' => 'Rose', 'last_name' => 'Apple', 'email' => 'fiona.apple@student.cpsu.edu.ph', 'student_id' => '2024-006', 'program' => 'BSHM', 'year' => 1, 'phone' => '09123456794', 'gender' => 'Female'],
            ['first_name' => 'George', 'middle_name' => 'Michael', 'last_name' => 'Lucas', 'email' => 'george.lucas@student.cpsu.edu.ph', 'student_id' => '2024-007', 'program' => 'BSME', 'year' => 3, 'phone' => '09123456795', 'gender' => 'Male'],
            ['first_name' => 'Helen', 'middle_name' => 'Victoria', 'last_name' => 'Troy', 'email' => 'helen.troy@student.cpsu.edu.ph', 'student_id' => '2024-008', 'program' => 'BSEE', 'year' => 2, 'phone' => '09123456796', 'gender' => 'Female'],
            ['first_name' => 'Ivan', 'middle_name' => 'Alexander', 'last_name' => 'Drago', 'email' => 'ivan.drago@student.cpsu.edu.ph', 'student_id' => '2024-009', 'program' => 'BSABE', 'year' => 4, 'phone' => '09123456797', 'gender' => 'Male'],
            ['first_name' => 'Jane', 'middle_name' => 'Elizabeth', 'last_name' => 'Doe', 'email' => 'jane.doe@student.cpsu.edu.ph', 'student_id' => '2024-010', 'program' => 'BSCRIM', 'year' => 1, 'phone' => '09123456798', 'gender' => 'Female'],
        ];

        foreach ($mainCampusStudents as $student) {
            if (isset($courses[$student['program']])) {
                Student::updateOrCreate(
                    ['student_id' => $student['student_id']],
                    [
                        'first_name' => $student['first_name'],
                        'middle_name' => $student['middle_name'],
                        'last_name' => $student['last_name'],
                        'email' => $student['email'],
                        'password' => Hash::make('student123'),
                        'phone' => $student['phone'],
                        'gender' => $student['gender'],
                        'course_id' => $courses[$student['program']]->id,
                        'year' => $student['year'],
                        'year_level' => $student['year'],
                        'section' => 'A',
                        'status' => 'Active',
                        'campus' => 'CPSU Victorias Campus',
                        'school' => 'CPSU Victorias Campus',
                        'department' => $courses[$student['program']]->department,
                        'enrollment_date' => now(),
                        'academic_year' => '2024-2025',
                        'birth_date' => now()->subYears(18 + $student['year'])->format('Y-m-d'),
                        'address' => 'Victorias City, Negros Occidental',
                    ]
                );
            }
        }

        // CPSU Bayambang Campus Students
        $bayambangStudents = [
            ['first_name' => 'Kevin', 'middle_name' => 'John', 'last_name' => 'Hart', 'email' => 'kevin.hart@student.cpsu.edu.ph', 'student_id' => '2024-101', 'program' => 'BSIT-BAY', 'year' => 1, 'phone' => '09123456801', 'gender' => 'Male'],
            ['first_name' => 'Lisa', 'middle_name' => 'Marie', 'last_name' => 'Simpson', 'email' => 'lisa.simpson@student.cpsu.edu.ph', 'student_id' => '2024-102', 'program' => 'BSIT-BAY', 'year' => 2, 'phone' => '09123456802', 'gender' => 'Female'],
            ['first_name' => 'Mike', 'middle_name' => 'Anthony', 'last_name' => 'Tyson', 'email' => 'mike.tyson@student.cpsu.edu.ph', 'student_id' => '2024-103', 'program' => 'BEED-BAY', 'year' => 1, 'phone' => '09123456803', 'gender' => 'Male'],
            ['first_name' => 'Nina', 'middle_name' => 'Sofia', 'last_name' => 'Garcia', 'email' => 'nina.garcia@student.cpsu.edu.ph', 'student_id' => '2024-104', 'program' => 'BEED-BAY', 'year' => 3, 'phone' => '09123456804', 'gender' => 'Female'],
            ['first_name' => 'Oscar', 'middle_name' => 'Fernando', 'last_name' => 'Wilde', 'email' => 'oscar.wilde@student.cpsu.edu.ph', 'student_id' => '2024-105', 'program' => 'BSIT-BAY', 'year' => 4, 'phone' => '09123456805', 'gender' => 'Male'],
        ];

        foreach ($bayambangStudents as $student) {
            if (isset($courses[$student['program']])) {
                Student::updateOrCreate(
                    ['student_id' => $student['student_id']],
                    [
                        'first_name' => $student['first_name'],
                        'middle_name' => $student['middle_name'],
                        'last_name' => $student['last_name'],
                        'email' => $student['email'],
                        'password' => Hash::make('student123'),
                        'phone' => $student['phone'],
                        'gender' => $student['gender'],
                        'course_id' => $courses[$student['program']]->id,
                        'year' => $student['year'],
                        'year_level' => $student['year'],
                        'section' => 'A',
                        'status' => 'Active',
                        'campus' => 'CPSU Bayambang Campus',
                        'school' => 'CPSU Bayambang Campus',
                        'department' => $courses[$student['program']]->department,
                        'enrollment_date' => now(),
                        'academic_year' => '2024-2025',
                        'birth_date' => now()->subYears(18 + $student['year'])->format('Y-m-d'),
                        'address' => 'Bayambang, Pangasinan',
                    ]
                );
            }
        }

        // Independent Students
        $independentStudents = [
            ['first_name' => 'Peter', 'middle_name' => 'Benjamin', 'last_name' => 'Parker', 'email' => 'peter.parker@gmail.com', 'student_id' => 'IND-001', 'program' => 'IND-PROG', 'year' => 2, 'phone' => '09123456901', 'gender' => 'Male'],
            ['first_name' => 'Mary', 'middle_name' => 'Jane', 'last_name' => 'Watson', 'email' => 'mary.jane@yahoo.com', 'student_id' => 'IND-002', 'program' => 'IND-PROG', 'year' => 1, 'phone' => '09123456902', 'gender' => 'Female'],
            ['first_name' => 'Tony', 'middle_name' => 'Edward', 'last_name' => 'Stark', 'email' => 'tony.stark@outlook.com', 'student_id' => 'IND-003', 'program' => 'IND-PROG', 'year' => 3, 'phone' => '09123456903', 'gender' => 'Male'],
        ];

        foreach ($independentStudents as $student) {
            if (isset($courses[$student['program']])) {
                Student::updateOrCreate(
                    ['student_id' => $student['student_id']],
                    [
                        'first_name' => $student['first_name'],
                        'middle_name' => $student['middle_name'],
                        'last_name' => $student['last_name'],
                        'email' => $student['email'],
                        'password' => Hash::make('student123'),
                        'phone' => $student['phone'],
                        'gender' => $student['gender'],
                        'course_id' => $courses[$student['program']]->id,
                        'year' => $student['year'],
                        'year_level' => $student['year'],
                        'section' => 'A',
                        'status' => 'Active',
                        'campus' => null,
                        'school' => 'Independent',
                        'department' => 'Independent',
                        'enrollment_date' => now(),
                        'academic_year' => '2024-2025',
                        'birth_date' => now()->subYears(18 + $student['year'])->format('Y-m-d'),
                        'address' => 'Various Locations',
                    ]
                );
            }
        }
    }

    private function createClasses(): void
    {
        $this->command->info('🏫 Creating classes...');

        $courses = Course::all()->keyBy('program_code');
        $teachers = User::where('role', 'teacher')->get()->keyBy('email');

        $classes = [
            // CPSU Main Campus Classes
            ['name' => 'BSIT 1A', 'course' => 'BSIT', 'teacher' => 'maria.santos@cpsu.edu.ph', 'year' => 1, 'section' => 'A'],
            ['name' => 'BSIT 2A', 'course' => 'BSIT', 'teacher' => 'juan.delacruz@cpsu.edu.ph', 'year' => 2, 'section' => 'A'],
            ['name' => 'BEED 1A', 'course' => 'BEED', 'teacher' => 'maria.santos@cpsu.edu.ph', 'year' => 1, 'section' => 'A'],
            ['name' => 'BSHM 1A', 'course' => 'BSHM', 'teacher' => 'juan.delacruz@cpsu.edu.ph', 'year' => 1, 'section' => 'A'],
            
            // CPSU Bayambang Campus Classes
            ['name' => 'BSIT 1A', 'course' => 'BSIT-BAY', 'teacher' => 'roberto.garcia@cpsu.edu.ph', 'year' => 1, 'section' => 'A'],
            ['name' => 'BEED 1A', 'course' => 'BEED-BAY', 'teacher' => 'carmen.lopez@cpsu.edu.ph', 'year' => 1, 'section' => 'A'],
            
            // Independent Classes
            ['name' => 'Independent Study Group', 'course' => 'IND-PROG', 'teacher' => 'john.smith@gmail.com', 'year' => 1, 'section' => 'A'],
        ];

        foreach ($classes as $class) {
            if (isset($courses[$class['course']]) && isset($teachers[$class['teacher']])) {
                // Convert year integer to enum format
                $yearMapping = [
                    1 => '1st',
                    2 => '2nd', 
                    3 => '3rd',
                    4 => '4th'
                ];
                
                ClassModel::updateOrCreate(
                    [
                        'class_name' => $class['name'],
                        'course_id' => $courses[$class['course']]->id,
                        'teacher_id' => $teachers[$class['teacher']]->id,
                    ],
                    [
                        'section' => $class['section'],
                        'class_level' => $class['year'], // Add class_level field
                        'year' => $yearMapping[$class['year']], // Use enum format
                        'academic_year' => '2024-2025',
                        'total_students' => 25,
                        'description' => $class['name'] . ' - Academic Year 2024-2025',
                        'units' => 3,
                    ]
                );
            }
        }
    }

    private function createCourseAccessRequests(): void
    {
        $this->command->info('📋 Creating course access requests...');

        $teachers = User::where('role', 'teacher')->get()->keyBy('email');
        $courses = Course::all()->keyBy('program_code');

        $requests = [
            ['teacher' => 'maria.santos@cpsu.edu.ph', 'course' => 'BSIT', 'status' => 'approved'],
            ['teacher' => 'maria.santos@cpsu.edu.ph', 'course' => 'BEED', 'status' => 'approved'],
            ['teacher' => 'juan.delacruz@cpsu.edu.ph', 'course' => 'BSIT', 'status' => 'approved'],
            ['teacher' => 'juan.delacruz@cpsu.edu.ph', 'course' => 'BSHM', 'status' => 'approved'],
            ['teacher' => 'ana.reyes@cpsu.edu.ph', 'course' => 'BEED', 'status' => 'pending'],
            ['teacher' => 'roberto.garcia@cpsu.edu.ph', 'course' => 'BSIT-BAY', 'status' => 'approved'],
            ['teacher' => 'carmen.lopez@cpsu.edu.ph', 'course' => 'BEED-BAY', 'status' => 'approved'],
        ];

        foreach ($requests as $request) {
            if (isset($teachers[$request['teacher']]) && isset($courses[$request['course']])) {
                CourseAccessRequest::updateOrCreate(
                    [
                        'teacher_id' => $teachers[$request['teacher']]->id,
                        'course_id' => $courses[$request['course']]->id,
                    ],
                    [
                        'status' => $request['status'],
                        'reason' => 'Seeded request for ' . $request['teacher'],
                        'admin_note' => $request['status'] === 'approved' ? 'Auto-approved during seeding' : 'Pending review',
                        'approved_at' => $request['status'] === 'approved' ? now() : null,
                    ]
                );
            }
        }
    }

    private function printSummary(): void
    {
        $this->command->info('📊 Seeding Summary:');
        $this->command->info('Colleges: ' . College::count());
        $this->command->info('Departments: ' . Department::count());
        $this->command->info('Courses: ' . Course::count());
        $this->command->info('Subjects: ' . Subject::count());
        $this->command->info('Users: ' . User::count());
        $this->command->info('Students: ' . Student::count());
        $this->command->info('Classes: ' . ClassModel::count());
        $this->command->info('Course Access Requests: ' . CourseAccessRequest::count());
    }
}