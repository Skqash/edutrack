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
    }