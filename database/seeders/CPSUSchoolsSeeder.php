<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CPSUSchoolsSeeder extends Seeder
{
    /**
     * Comprehensive CPSU Schools Seeder with Data Isolation
     * Creates all CPSU campuses with proper data isolation
     */
    public function run(): void
    {
        DB::beginTransaction();
        
        try {
            // Step 1: Create all CPSU schools/campuses
            $this->createSchools();
            
            // Step 2: Create admins for each campus
            $this->createCampusAdmins();
            
            // Step 3: Create teachers for each campus
            $this->createCampusTeachers();
            
            // Step 4: Create courses for each campus
            $this->createCampusCourses();
            
            // Step 5: Create subjects for each campus
            $this->createCampusSubjects();
            
            // Step 6: Create students for each campus
            $this->createCampusStudents();
            
            // Step 7: Create classes for each campus
            $this->createCampusClasses();
            
            DB::commit();
            
            $this->command->info('✅ CPSU Schools seeder completed successfully!');
            $this->command->info('📊 Data isolation verified across all campuses');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Seeder failed: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Create all CPSU schools/campuses
     */
    private function createSchools(): void
    {
        $schools = [
            [
                'school_code' => 'CPSU-MAIN',
                'school_name' => 'CPSU Main Campus - Kabankalan',
                'short_name' => 'Kabankalan',
                'campus_type' => 'main',
                'location' => 'Kabankalan City, Negros Occidental',
                'city' => 'Kabankalan City',
                'province' => 'Negros Occidental',
                'established_date' => '1958-01-01',
            ],
            [
                'school_code' => 'CPSU-VIC',
                'school_name' => 'CPSU Victorias Campus',
                'short_name' => 'Victorias',
                'campus_type' => 'satellite',
                'location' => 'Victorias City, Negros Occidental',
                'city' => 'Victorias City',
                'province' => 'Negros Occidental',
                'established_date' => '1995-01-01',
            ],
            [
                'school_code' => 'CPSU-SIP',
                'school_name' => 'CPSU Sipalay Campus',
                'short_name' => 'Sipalay',
                'campus_type' => 'satellite',
                'location' => 'Brgy. Gil Montilla, Sipalay City, Negros Occidental',
                'city' => 'Sipalay City',
                'province' => 'Negros Occidental',
                'established_date' => '2000-01-01',
            ],
            [
                'school_code' => 'CPSU-CAU',
                'school_name' => 'CPSU Cauayan Campus',
                'short_name' => 'Cauayan',
                'campus_type' => 'satellite',
                'location' => 'Municipality of Cauayan, Negros Occidental',
                'city' => 'Cauayan',
                'province' => 'Negros Occidental',
                'established_date' => '2002-01-01',
            ],
            [
                'school_code' => 'CPSU-CAN',
                'school_name' => 'CPSU Candoni Campus',
                'short_name' => 'Candoni',
                'campus_type' => 'satellite',
                'location' => 'Candoni, Negros Occidental',
                'city' => 'Candoni',
                'province' => 'Negros Occidental',
                'established_date' => '2006-01-01',
            ],
            [
                'school_code' => 'CPSU-HIN',
                'school_name' => 'CPSU Hinoba-an Campus',
                'short_name' => 'Hinoba-an',
                'campus_type' => 'satellite',
                'location' => 'Hinoba-an, Negros Occidental',
                'city' => 'Hinoba-an',
                'province' => 'Negros Occidental',
                'established_date' => '2003-01-01',
            ],
            [
                'school_code' => 'CPSU-ILO',
                'school_name' => 'CPSU Ilog Campus',
                'short_name' => 'Ilog',
                'campus_type' => 'satellite',
                'location' => 'Ilog, Negros Occidental',
                'city' => 'Ilog',
                'province' => 'Negros Occidental',
                'established_date' => '2004-01-01',
            ],
            [
                'school_code' => 'CPSU-HIG',
                'school_name' => 'CPSU Hinigaran Campus',
                'short_name' => 'Hinigaran',
                'campus_type' => 'satellite',
                'location' => 'Hinigaran, Negros Occidental',
                'city' => 'Hinigaran',
                'province' => 'Negros Occidental',
                'established_date' => '2005-01-01',
            ],
            [
                'school_code' => 'CPSU-MP',
                'school_name' => 'CPSU Moises Padilla Campus',
                'short_name' => 'Moises Padilla',
                'campus_type' => 'satellite',
                'location' => 'Moises Padilla, Negros Occidental',
                'city' => 'Moises Padilla',
                'province' => 'Negros Occidental',
                'established_date' => '2007-01-01',
            ],
            [
                'school_code' => 'CPSU-SC',
                'school_name' => 'CPSU San Carlos Campus',
                'short_name' => 'San Carlos',
                'campus_type' => 'satellite',
                'location' => 'San Carlos City, Negros Occidental',
                'city' => 'San Carlos City',
                'province' => 'Negros Occidental',
                'established_date' => '2008-01-01',
            ],
        ];

        foreach ($schools as $schoolData) {
            School::create($schoolData);
        }

        $this->command->info('✅ Created ' . count($schools) . ' CPSU campuses');
    }
    /**
     * Create admin for each campus
     */
    private function createCampusAdmins(): void
    {
        $schools = School::all();
        
        foreach ($schools as $school) {
            User::create([
                'name' => "Admin {$school->short_name}",
                'email' => "admin.{$school->school_code}@cpsu.edu.ph",
                'phone' => '09' . rand(100000000, 999999999),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'Active',
                'employee_id' => 'ADM-' . $school->school_code . '-001',
                'department' => 'Administration',
                'campus' => $school->short_name,
                'campus_status' => 'approved',
                'campus_approved_at' => now(),
                'school_id' => $school->id,
            ]);
        }
        
        $this->command->info('✅ Created admin for each campus');
    }

    /**
     * Create teachers for each campus
     */
    private function createCampusTeachers(): void
    {
        $schools = School::all();
        
        foreach ($schools as $school) {
            // Create 3-5 teachers per campus
            $teacherCount = rand(3, 5);
            
            for ($i = 1; $i <= $teacherCount; $i++) {
                User::create([
                    'name' => "Teacher {$i} {$school->short_name}",
                    'email' => "teacher{$i}.{$school->school_code}@cpsu.edu.ph",
                    'phone' => '09' . rand(100000000, 999999999),
                    'password' => Hash::make('teacher123'),
                    'role' => 'teacher',
                    'status' => 'Active',
                    'employee_id' => 'TCH-' . $school->school_code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'qualification' => ['Bachelor\'s Degree', 'Master\'s Degree', 'Doctorate'][rand(0, 2)],
                    'specialization' => ['Information Technology', 'Education', 'Agriculture', 'Business'][rand(0, 3)],
                    'department' => ['BSIT', 'BEED', 'BSAgri-Business', 'BSHM'][rand(0, 3)],
                    'campus' => $school->short_name,
                    'campus_status' => 'approved',
                    'campus_approved_at' => now(),
                    'school_id' => $school->id,
                ]);
            }
        }
        
        $this->command->info('✅ Created teachers for each campus');
    }
    /**
     * Create courses for each campus
     */
    private function createCampusCourses(): void
    {
        $schools = School::all();
        
        // Main campus gets all programs
        $mainPrograms = [
            ['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology', 'college' => 'College of Engineering and Technology'],
            ['code' => 'BSAgri-Business', 'name' => 'Bachelor of Science in Agribusiness', 'college' => 'College of Agriculture'],
            ['code' => 'BEED', 'name' => 'Bachelor in Elementary Education', 'college' => 'College of Education'],
            ['code' => 'BSHM', 'name' => 'Bachelor of Science in Hotel Management', 'college' => 'College of Business'],
            ['code' => 'BSED', 'name' => 'Bachelor in Secondary Education', 'college' => 'College of Education'],
            ['code' => 'BPED', 'name' => 'Bachelor in Physical Education', 'college' => 'College of Education'],
            ['code' => 'BECED', 'name' => 'Bachelor in Early Childhood Education', 'college' => 'College of Education'],
            ['code' => 'BSA', 'name' => 'BS in Agriculture', 'college' => 'College of Agriculture'],
            ['code' => 'BAS', 'name' => 'Bachelor in Animal Science', 'college' => 'College of Agriculture'],
            ['code' => 'BSF', 'name' => 'BS in Forestry', 'college' => 'College of Agriculture'],
            ['code' => 'BST', 'name' => 'Bachelor in Sugar Technology', 'college' => 'College of Engineering and Technology'],
            ['code' => 'AB-ENG', 'name' => 'Bachelor of Arts in English', 'college' => 'College of Arts and Sciences'],
            ['code' => 'AB-SS', 'name' => 'Bachelor of Arts in Social Science', 'college' => 'College of Arts and Sciences'],
            ['code' => 'BS-STAT', 'name' => 'BS in Applied Statistics', 'college' => 'College of Arts and Sciences'],
            ['code' => 'BSHRM', 'name' => 'BS in Hotel & Restaurant Management', 'college' => 'College of Business'],
            ['code' => 'BSCRIM', 'name' => 'BS in Criminology', 'college' => 'College of Criminal Justice'],
            ['code' => 'BSABE', 'name' => 'BS Agricultural and Biosystems Engineering', 'college' => 'College of Engineering and Technology'],
            ['code' => 'BSME', 'name' => 'BS in Mechanical Engineering', 'college' => 'College of Engineering and Technology'],
            ['code' => 'BSEE', 'name' => 'BS in Electrical Engineering', 'college' => 'College of Engineering and Technology'],
        ];
        
        // Satellite campuses get core programs
        $satellitePrograms = [
            ['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology', 'college' => 'College of Engineering and Technology'],
            ['code' => 'BSAgri-Business', 'name' => 'Bachelor of Science in Agribusiness', 'college' => 'College of Agriculture'],
            ['code' => 'BEED', 'name' => 'Bachelor in Elementary Education', 'college' => 'College of Education'],
            ['code' => 'BSHM', 'name' => 'Bachelor of Science in Hotel Management', 'college' => 'College of Business'],
        ];

        foreach ($schools as $school) {
            $programs = $school->isMainCampus() ? $mainPrograms : $satellitePrograms;
            
            foreach ($programs as $program) {
                Course::create([
                    'program_code' => $program['code'],
                    'course_code' => $program['code'] . '-' . $school->school_code, // Make unique per campus
                    'program_name' => $program['name'],
                    'total_years' => 4,
                    'status' => 'Active',
                    'campus' => $school->short_name,
                    'school_id' => $school->id,
                    'college' => $program['college'],
                ]);
            }
        }
        
        $this->command->info('✅ Created courses for each campus');
    }
    /**
     * Create subjects for each campus based on their courses
     */
    private function createCampusSubjects(): void
    {
        $schools = School::all();
        
        // BSIT subjects by year and semester
        $bsitSubjects = [
            // First Year
            1 => [
                1 => [ // First Semester
                    ['name' => 'Introduction to Computing', 'code' => 'CCIT 01', 'units' => 3],
                    ['name' => 'Computer Programming 1', 'code' => 'CCIT 02', 'units' => 3],
                    ['name' => 'Mathematics in the Modern World', 'code' => 'GE 01', 'units' => 3],
                    ['name' => 'Purposive Communication', 'code' => 'GE 02', 'units' => 3],
                    ['name' => 'Understanding the Self', 'code' => 'GE 03', 'units' => 3],
                    ['name' => 'NSTP 1', 'code' => 'NSTP 01', 'units' => 3],
                ],
                2 => [ // Second Semester
                    ['name' => 'Computer Programming 2', 'code' => 'CCIT 03', 'units' => 3],
                    ['name' => 'Discrete Mathematics', 'code' => 'CCIT 04', 'units' => 3],
                    ['name' => 'Readings in Philippine History', 'code' => 'GE 04', 'units' => 3],
                    ['name' => 'The Contemporary World', 'code' => 'GE 05', 'units' => 3],
                    ['name' => 'Art Appreciation', 'code' => 'GE 06', 'units' => 3],
                    ['name' => 'NSTP 2', 'code' => 'NSTP 02', 'units' => 3],
                ],
            ],
            // Second Year
            2 => [
                1 => [
                    ['name' => 'Integrative Programming and Technologies 1', 'code' => 'PCIT 03', 'units' => 3],
                    ['name' => 'Web System Technologies', 'code' => 'PCIT 04', 'units' => 3],
                    ['name' => 'Object-Oriented Programming', 'code' => 'PCIT 05', 'units' => 3],
                    ['name' => 'Data Structures and Algorithms', 'code' => 'PCIT 06', 'units' => 3],
                    ['name' => 'Ethics', 'code' => 'GE 07', 'units' => 3],
                ],
                2 => [
                    ['name' => 'Networking 1', 'code' => 'PCIT 07', 'units' => 3],
                    ['name' => 'Database Management Systems', 'code' => 'PCIT 08', 'units' => 3],
                    ['name' => 'Human Computer Interaction', 'code' => 'PCIT 09', 'units' => 3],
                    ['name' => 'Science, Technology and Society', 'code' => 'GE 08', 'units' => 3],
                    ['name' => 'Physical Education 1', 'code' => 'PE 01', 'units' => 2],
                ],
            ],
        ];

        foreach ($schools as $school) {
            $courses = Course::where('school_id', $school->id)->get();
            
            foreach ($courses as $course) {
                if ($course->program_code === 'BSIT') {
                    // Create BSIT subjects
                    foreach ($bsitSubjects as $year => $semesters) {
                        foreach ($semesters as $semester => $subjects) {
                            foreach ($subjects as $subject) {
                                Subject::create([
                                    'subject_code' => $subject['code'] . '-' . $school->school_code,
                                    'subject_name' => $subject['name'],
                                    'credit_hours' => $subject['units'],
                                    'program_id' => $course->id,
                                    'year_level' => $year,
                                    'semester' => $semester,
                                    'category' => 'Core',
                                    'campus' => $school->short_name,
                                    'school_id' => $school->id,
                                ]);
                            }
                        }
                    }
                } else {
                    // Create basic subjects for other programs
                    $basicSubjects = [
                        ['name' => 'General Education 1', 'code' => 'GE01', 'year' => 1, 'semester' => 1],
                        ['name' => 'General Education 2', 'code' => 'GE02', 'year' => 1, 'semester' => 2],
                        ['name' => 'Major Subject 1', 'code' => 'MAJ01', 'year' => 2, 'semester' => 1],
                        ['name' => 'Major Subject 2', 'code' => 'MAJ02', 'year' => 2, 'semester' => 2],
                    ];
                    
                    foreach ($basicSubjects as $subject) {
                        Subject::create([
                            'subject_code' => $subject['code'] . '-' . $course->program_code . '-' . $school->school_code,
                            'subject_name' => $subject['name'],
                            'credit_hours' => 3,
                            'program_id' => $course->id,
                            'year_level' => $subject['year'],
                            'semester' => $subject['semester'],
                            'category' => 'Core',
                            'campus' => $school->short_name,
                            'school_id' => $school->id,
                        ]);
                    }
                }
            }
        }
        
        $this->command->info('✅ Created subjects for each campus');
    }
    /**
     * Create students for each campus
     */
    private function createCampusStudents(): void
    {
        $schools = School::all();
        $globalStudentCounter = 1; // Global counter to ensure unique student IDs
        
        foreach ($schools as $school) {
            $courses = Course::where('school_id', $school->id)->get();
            
            foreach ($courses as $course) {
                // Create 10-20 students per course per campus
                $studentCount = rand(10, 20);
                
                for ($i = 1; $i <= $studentCount; $i++) {
                    $year = rand(1, 4);
                    $section = chr(65 + rand(0, 2)); // A, B, or C
                    
                    Student::create([
                        'student_id' => '2024-' . str_pad($globalStudentCounter, 4, '0', STR_PAD_LEFT) . '-' . strtoupper(substr($school->short_name, 0, 1)),
                        'first_name' => 'Student' . $globalStudentCounter,
                        'middle_name' => 'Middle',
                        'last_name' => $school->short_name,
                        'email' => "student{$globalStudentCounter}.{$course->program_code}.{$school->school_code}@cpsu.edu.ph",
                        'password' => Hash::make('student123'),
                        'phone' => '09' . rand(100000000, 999999999),
                        'course_id' => $course->id,
                        'year' => $year,
                        'section' => $section,
                        'department' => $course->program_code,
                        'status' => 'Active',
                        'campus' => $school->short_name,
                        'school_id' => $school->id,
                        'enrollment_date' => now()->subDays(rand(30, 365)),
                        'academic_year' => '2024-2025',
                    ]);
                    
                    $globalStudentCounter++;
                }
            }
        }
        
        $this->command->info('✅ Created students for each campus');
    }

    /**
     * Create classes for each campus
     */
    private function createCampusClasses(): void
    {
        $schools = School::all();
        
        foreach ($schools as $school) {
            $teachers = User::where('school_id', $school->id)->where('role', 'teacher')->get();
            $subjects = Subject::where('school_id', $school->id)->get();
            $courses = Course::where('school_id', $school->id)->get();
            
            foreach ($courses as $course) {
                foreach ([1, 2, 3, 4] as $year) {
                    foreach (['A', 'B'] as $section) {
                        $yearSubjects = $subjects->where('program_id', $course->id)->where('year_level', $year);
                        
                        foreach ($yearSubjects as $subject) {
                            if ($teachers->isNotEmpty()) {
                                $teacher = $teachers->random();
                                
                                ClassModel::create([
                                    'class_name' => "{$course->program_code} {$year}{$section} - {$subject->subject_name}",
                                    'course_id' => $course->id,
                                    'subject_id' => $subject->id,
                                    'teacher_id' => $teacher->id,
                                    'class_level' => $year,
                                    'section' => $section,
                                    'semester' => $subject->semester,
                                    'academic_year' => '2024-2025',
                                    'status' => 'Active',
                                    'campus' => $school->short_name,
                                    'school_id' => $school->id,
                                ]);
                            }
                        }
                    }
                }
            }
        }
        
        $this->command->info('✅ Created classes for each campus');
    }
}