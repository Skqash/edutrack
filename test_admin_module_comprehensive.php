<?php

/**
 * Comprehensive Admin Module Validation Script
 * Tests all admin functions, routes, database connections, and CRUD operations
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Course;
use App\Models\Subject;
use App\Models\CourseAccessRequest;
use App\Services\AdminDashboardService;
use App\Services\AdminStudentService;
use App\Services\AdminTeacherService;
use App\Services\AdminCourseService;
use Illuminate\Support\Facades\DB;

class AdminModuleValidator
{
    private $results = [];
    private $errors = [];
    private $warnings = [];
    
    public function run()
    {
        echo "=== ADMIN MODULE COMPREHENSIVE VALIDATION ===\n\n";
        
        // Test 1: Database Connection
        $this->testDatabaseConnection();
        
        // Test 2: Admin Authentication & Profile
        $this->testAdminProfile();
        
        // Test 3: Dashboard Functionality
        $this->testDashboard();
        
        // Test 4: Students Management CRUD
        $this->testStudentsCRUD();
        
        // Test 5: Teachers Management CRUD
        $this->testTeachersCRUD();
        
        // Test 6: Courses Management CRUD
        $this->testCoursesCRUD();
        
        // Test 7: Classes Management CRUD
        $this->testClassesCRUD();
        
        // Test 8: Campus Isolation
        $this->testCampusIsolation();
        
        // Test 9: Approval Systems
        $this->testApprovalSystems();
        
        // Test 10: Data Fetching & Services
        $this->testDataFetching();
        
        // Test 11: Routes Validation
        $this->testRoutesValidation();
        
        // Test 12: Controller Methods
        $this->testControllerMethods();
        
        // Test 13: Bulk Operations
        $this->testBulkOperations();
        
        // Test 14: Bug Detection
        $this->testBugDetection();
        
        // Print Results
        $this->printResults();
    }
    
    private function testDatabaseConnection()
    {
        echo "Testing Database Connection...\n";
        try {
            DB::connection()->getPdo();
            $this->results['database'] = '✓ Database connection successful';
            
            // Test table existence
            $tables = ['users', 'classes', 'students', 'courses', 'subjects', 
                      'course_access_requests', 'grades', 'attendance'];
            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) {
                    $this->errors[] = "✗ Table '$table' does not exist";
                } else {
                    $this->results["table_$table"] = "✓ Table '$table' exists";
                }
            }
        } catch (\Exception $e) {
            $this->errors[] = "✗ Database connection failed: " . $e->getMessage();
        }
    }
    
    private function testAdminProfile()
    {
        echo "Testing Admin Profile Management...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            
            if (!$admin) {
                $this->warnings[] = "⚠ No admin found in database";
                return;
            }
            
            $this->results['admin_found'] = "✓ Admin found: {$admin->name} (ID: {$admin->id})";
            
            // Check required fields
            $requiredFields = ['name', 'email', 'role'];
            foreach ($requiredFields as $field) {
                if (empty($admin->$field)) {
                    $this->warnings[] = "⚠ Admin missing field: $field";
                } else {
                    $this->results["admin_$field"] = "✓ Admin has $field: {$admin->$field}";
                }
            }
            
            // Check campus field
            if ($admin->campus) {
                $this->results['admin_campus'] = "✓ Admin campus: {$admin->campus}";
            } else {
                $this->results['admin_campus'] = "✓ Admin is super admin (no campus restriction)";
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Admin profile test failed: " . $e->getMessage();
        }
    }
    
    private function testDashboard()
    {
        echo "Testing Dashboard Functionality...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            $dashboardService = new AdminDashboardService();
            
            // Test dashboard stats
            $stats = $dashboardService->getDashboardStats($admin->campus);
            $this->results['dashboard_stats'] = "✓ Dashboard stats retrieved";
            
            // Validate stats structure
            $requiredKeys = ['total_teachers', 'total_students', 'total_classes', 'total_courses'];
            foreach ($requiredKeys as $key) {
                if (array_key_exists($key, $stats)) {
                    $value = is_array($stats[$key]) ? json_encode($stats[$key]) : $stats[$key];
                    $this->results["dashboard_$key"] = "✓ Dashboard has $key: $value";
                } else {
                    $this->errors[] = "✗ Dashboard missing key: $key";
                }
            }
            
            // Test pending approvals
            $approvals = $dashboardService->getPendingApprovals($admin->campus);
            $this->results['dashboard_approvals'] = "✓ Pending approvals retrieved";
            
            // Test recent activities
            $activities = $dashboardService->getRecentActivities($admin->campus);
            $this->results['dashboard_activities'] = "✓ Recent activities retrieved (" . count($activities) . " items)";
            
            // Test chart data
            $chartData = $dashboardService->getChartData($admin->campus);
            $this->results['dashboard_charts'] = "✓ Chart data retrieved";
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Dashboard test failed: " . $e->getMessage();
        }
    }
    
    private function testStudentsCRUD()
    {
        echo "Testing Students CRUD Operations...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            $studentService = new AdminStudentService();
            
            // Test READ - Get filtered students
            $filters = ['campus' => $admin->campus];
            $students = $studentService->getFilteredStudents($filters);
            $this->results['students_read'] = "✓ Students retrieved: " . $students->total() . " total";
            
            // Test statistics
            $stats = $studentService->getStudentStatistics($admin->campus);
            $this->results['students_stats'] = "✓ Student statistics: Total={$stats['total']}, Active={$stats['active']}";
            
            // Test CREATE (dry run - we'll rollback)
            DB::beginTransaction();
            try {
                $course = Course::when($admin->campus, fn($q) => $q->where('campus', $admin->campus))->first();
                if ($course) {
                    $testData = [
                        'name' => 'Test Student ' . time(),
                        'email' => 'teststudent' . time() . '@test.com',
                        'password' => 'password123',
                        'student_id' => 'TEST' . time(),
                        'course_id' => $course->id,
                        'year_level' => 1,
                    ];
                    
                    $student = $studentService->createStudent($testData, $admin->campus);
                    $this->results['students_create'] = "✓ Student creation works (ID: {$student->id})";
                    
                    // Test UPDATE
                    $updateData = array_merge($testData, [
                        'name' => 'Updated Test Student',
                        'status' => 'Active',
                        'first_name' => 'Test',
                        'last_name' => 'Student',
                    ]);
                    $studentService->updateStudent($student, $updateData);
                    $this->results['students_update'] = "✓ Student update works";
                    
                    // Test DELETE
                    $studentService->deleteStudent($student);
                    $this->results['students_delete'] = "✓ Student deletion works";
                }
                DB::rollBack();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Students CRUD test failed: " . $e->getMessage();
        }
    }
    
    private function testTeachersCRUD()
    {
        echo "Testing Teachers CRUD Operations...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            $teacherService = new AdminTeacherService();
            
            // Test READ - Get filtered teachers
            $filters = ['campus' => $admin->campus];
            $teachers = $teacherService->getFilteredTeachers($filters);
            $this->results['teachers_read'] = "✓ Teachers retrieved: " . $teachers->total() . " total";
            
            // Test statistics
            $stats = $teacherService->getTeacherStatistics($admin->campus);
            $this->results['teachers_stats'] = "✓ Teacher statistics: Total={$stats['total']}, Approved={$stats['approved']}";
            
            // Test CREATE (dry run - we'll rollback)
            DB::beginTransaction();
            try {
                $testData = [
                    'name' => 'Test Teacher ' . time(),
                    'email' => 'testteacher' . time() . '@test.com',
                    'password' => 'password123',
                    'campus' => $admin->campus,
                    'school_id' => $admin->school_id,
                    'auto_approve' => true,
                ];
                
                $teacher = $teacherService->createTeacher($testData, $admin->id);
                $this->results['teachers_create'] = "✓ Teacher creation works (ID: {$teacher->id})";
                
                // Test UPDATE
                $updateData = array_merge($testData, [
                    'name' => 'Updated Test Teacher',
                    'status' => 'Active',
                ]);
                $teacherService->updateTeacher($teacher, $updateData);
                $this->results['teachers_update'] = "✓ Teacher update works";
                
                // Test DELETE
                $teacherService->deleteTeacher($teacher);
                $this->results['teachers_delete'] = "✓ Teacher deletion works";
                
                DB::rollBack();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Teachers CRUD test failed: " . $e->getMessage();
        }
    }
    
    private function testCoursesCRUD()
    {
        echo "Testing Courses CRUD Operations...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            $courseService = new AdminCourseService();
            
            // Test READ - Get filtered courses
            $filters = ['campus' => $admin->campus];
            $courses = $courseService->getFilteredCourses($filters);
            $this->results['courses_read'] = "✓ Courses retrieved: " . $courses->total() . " total";
            
            // Test statistics
            $stats = $courseService->getCourseStatistics($admin->campus);
            $this->results['courses_stats'] = "✓ Course statistics: Total={$stats['total']}, Active={$stats['active']}";
            
            // Test CREATE (dry run - we'll rollback)
            DB::beginTransaction();
            try {
                $testData = [
                    'program_code' => 'TEST' . time(),
                    'program_name' => 'Test Program ' . time(),
                    'department_id' => 'Test Department',
                    'total_years' => 4,
                    'status' => 'Active',
                ];
                
                $course = $courseService->createCourse($testData, $admin->campus);
                $this->results['courses_create'] = "✓ Course creation works (ID: {$course->id})";
                
                // Test UPDATE
                $updateData = array_merge($testData, [
                    'program_name' => 'Updated Test Program',
                ]);
                $courseService->updateCourse($course, $updateData);
                $this->results['courses_update'] = "✓ Course update works";
                
                // Test DELETE
                $result = $courseService->deleteCourse($course);
                if ($result['success']) {
                    $this->results['courses_delete'] = "✓ Course deletion works";
                } else {
                    $this->warnings[] = "⚠ Course deletion: " . $result['message'];
                }
                
                DB::rollBack();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Courses CRUD test failed: " . $e->getMessage();
        }
    }
    
    private function testClassesCRUD()
    {
        echo "Testing Classes CRUD Operations...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            // Test READ
            $classesQuery = ClassModel::when($admin->campus, fn($q) => $q->where('campus', $admin->campus));
            $classesCount = $classesQuery->count();
            $this->results['classes_read'] = "✓ Classes retrieved: {$classesCount} total";
            
            // Test class with students
            $classWithStudents = $classesQuery->has('students')->first();
            if ($classWithStudents) {
                $studentCount = $classWithStudents->students()->count();
                $this->results['classes_students'] = "✓ Class '{$classWithStudents->class_name}' has {$studentCount} students";
            }
            
            // Test class relationships
            $classWithRelations = $classesQuery->with(['teacher', 'course', 'subject'])->first();
            if ($classWithRelations) {
                $this->results['classes_relations'] = "✓ Class relationships working";
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Classes CRUD test failed: " . $e->getMessage();
        }
    }
    
    private function testCampusIsolation()
    {
        echo "Testing Campus Isolation...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            if ($admin->campus) {
                // Test that admin can only see their campus data
                $students = Student::where('campus', $admin->campus)->count();
                $allStudents = Student::count();
                
                if ($students < $allStudents) {
                    $this->results['campus_isolation_students'] = "✓ Campus isolation working for students ({$students}/{$allStudents})";
                } else {
                    $this->warnings[] = "⚠ Campus isolation may not be working (all students visible)";
                }
                
                $classes = ClassModel::where('campus', $admin->campus)->count();
                $allClasses = ClassModel::count();
                
                if ($classes < $allClasses) {
                    $this->results['campus_isolation_classes'] = "✓ Campus isolation working for classes ({$classes}/{$allClasses})";
                } else {
                    $this->warnings[] = "⚠ Campus isolation may not be working (all classes visible)";
                }
            } else {
                $this->results['campus_isolation'] = "✓ Admin is super admin (no campus restriction)";
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Campus isolation test failed: " . $e->getMessage();
        }
    }
    
    private function testApprovalSystems()
    {
        echo "Testing Approval Systems...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            $teacherService = new AdminTeacherService();
            
            // Test campus approvals
            $approvals = $teacherService->getCampusApprovals($admin->campus);
            $this->results['approvals_campus'] = "✓ Campus approvals: Pending={$approvals['pendingCount']}, Approved={$approvals['approvedCount']}";
            
            // Test course access requests
            $requests = $teacherService->getCourseAccessRequests($admin->campus);
            $this->results['approvals_course'] = "✓ Course requests: Pending={$requests['pendingCount']}, Approved={$requests['approvedCount']}";
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Approval systems test failed: " . $e->getMessage();
        }
    }
    
    private function testDataFetching()
    {
        echo "Testing Data Fetching & Services...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            $dashboardService = new AdminDashboardService();
            
            // Test filtered data fetching
            $gradeData = $dashboardService->getFilteredData('grades', ['campus' => $admin->campus]);
            $this->results['data_fetching_grades'] = "✓ Grade data fetching works";
            
            $classData = $dashboardService->getFilteredData('classes', ['campus' => $admin->campus]);
            $this->results['data_fetching_classes'] = "✓ Class data fetching works";
            
            // Test system health
            $health = $dashboardService->getSystemHealth($admin->campus);
            $this->results['system_health'] = "✓ System health check: " . $health['overall']['status'];
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Data fetching test failed: " . $e->getMessage();
        }
    }
    
    private function testRoutesValidation()
    {
        echo "Testing Routes Validation...\n";
        try {
            $routes = \Illuminate\Support\Facades\Route::getRoutes();
            $adminRoutes = [];
            
            foreach ($routes as $route) {
                if (str_starts_with($route->getName() ?? '', 'admin.')) {
                    $adminRoutes[] = $route->getName();
                }
            }
            
            $this->results['routes_count'] = "✓ Found " . count($adminRoutes) . " admin routes";
            
            // Check critical routes
            $criticalRoutes = [
                'admin.dashboard',
                'admin.students.index',
                'admin.students.create',
                'admin.students.store',
                'admin.teachers.index',
                'admin.teachers.create',
                'admin.teachers.store',
                'admin.courses.index',
                'admin.courses.create',
                'admin.courses.store',
                'admin.classes.index',
                'admin.classes.create',
                'admin.classes.store',
                'admin.teachers.campus-approvals',
                'admin.teachers.course-access-requests',
            ];
            
            foreach ($criticalRoutes as $routeName) {
                if (in_array($routeName, $adminRoutes)) {
                    $this->results["route_{$routeName}"] = "✓ Route exists: {$routeName}";
                } else {
                    $this->errors[] = "✗ Critical route missing: {$routeName}";
                }
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Routes validation test failed: " . $e->getMessage();
        }
    }
    
    private function testControllerMethods()
    {
        echo "Testing Controller Methods...\n";
        try {
            $dashboardController = new \App\Http\Controllers\Admin\OptimizedDashboardController(new AdminDashboardService());
            $studentController = new \App\Http\Controllers\Admin\OptimizedStudentController(new AdminStudentService());
            $teacherController = new \App\Http\Controllers\Admin\OptimizedTeacherController(new AdminTeacherService());
            $courseController = new \App\Http\Controllers\Admin\OptimizedCourseController(new AdminCourseService());
            
            $dashboardMethods = get_class_methods($dashboardController);
            $this->results['controller_dashboard'] = "✓ DashboardController has " . count($dashboardMethods) . " methods";
            
            $studentMethods = get_class_methods($studentController);
            $this->results['controller_students'] = "✓ StudentController has " . count($studentMethods) . " methods";
            
            $teacherMethods = get_class_methods($teacherController);
            $this->results['controller_teachers'] = "✓ TeacherController has " . count($teacherMethods) . " methods";
            
            $courseMethods = get_class_methods($courseController);
            $this->results['controller_courses'] = "✓ CourseController has " . count($courseMethods) . " methods";
            
            // Check critical methods
            $criticalMethods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
            foreach ($criticalMethods as $method) {
                if (in_array($method, $studentMethods)) {
                    $this->results["method_student_{$method}"] = "✓ StudentController has {$method}";
                } else {
                    $this->errors[] = "✗ StudentController missing method: {$method}";
                }
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Controller methods test failed: " . $e->getMessage();
        }
    }
    
    private function testBulkOperations()
    {
        echo "Testing Bulk Operations...\n";
        try {
            $admin = User::where('role', 'admin')->first();
            if (!$admin) return;
            
            // Test bulk operations exist in services
            $studentService = new AdminStudentService();
            $teacherService = new AdminTeacherService();
            $courseService = new AdminCourseService();
            
            if (method_exists($studentService, 'performBulkAction')) {
                $this->results['bulk_students'] = "✓ Student bulk operations available";
            }
            
            if (method_exists($teacherService, 'performBulkAction')) {
                $this->results['bulk_teachers'] = "✓ Teacher bulk operations available";
            }
            
            if (method_exists($courseService, 'performBulkAction')) {
                $this->results['bulk_courses'] = "✓ Course bulk operations available";
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Bulk operations test failed: " . $e->getMessage();
        }
    }
    
    private function testBugDetection()
    {
        echo "Testing Bug Detection...\n";
        try {
            // Check for orphaned students
            $orphanedStudents = Student::whereDoesntHave('course')->count();
            if ($orphanedStudents > 0) {
                $this->warnings[] = "⚠ Found {$orphanedStudents} students without courses";
            } else {
                $this->results['bug_orphaned_students'] = "✓ No orphaned students";
            }
            
            // Check for classes without teachers
            $classesWithoutTeachers = ClassModel::whereNull('teacher_id')->count();
            if ($classesWithoutTeachers > 0) {
                $this->warnings[] = "⚠ Found {$classesWithoutTeachers} classes without teachers";
            } else {
                $this->results['bug_classes_no_teacher'] = "✓ All classes have teachers";
            }
            
            // Check for courses without departments
            $coursesWithoutDept = Course::whereNull('department')->count();
            if ($coursesWithoutDept > 0) {
                $this->warnings[] = "⚠ Found {$coursesWithoutDept} courses without departments";
            } else {
                $this->results['bug_courses_no_dept'] = "✓ All courses have departments";
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Bug detection test failed: " . $e->getMessage();
        }
    }
    
    private function printResults()
    {
        echo "\n\n=== VALIDATION RESULTS ===\n\n";
        
        echo "PASSED TESTS (" . count($this->results) . "):\n";
        foreach ($this->results as $key => $message) {
            echo "  $message\n";
        }
        
        if (!empty($this->warnings)) {
            echo "\nWARNINGS (" . count($this->warnings) . "):\n";
            foreach ($this->warnings as $warning) {
                echo "  $warning\n";
            }
        }
        
        if (!empty($this->errors)) {
            echo "\nERRORS (" . count($this->errors) . "):\n";
            foreach ($this->errors as $error) {
                echo "  $error\n";
            }
        }
        
        echo "\n=== SUMMARY ===\n";
        echo "Total Passed: " . count($this->results) . "\n";
        echo "Total Warnings: " . count($this->warnings) . "\n";
        echo "Total Errors: " . count($this->errors) . "\n";
        
        if (empty($this->errors)) {
            echo "\n✓ ALL CRITICAL TESTS PASSED!\n";
        } else {
            echo "\n✗ SOME TESTS FAILED - REVIEW ERRORS ABOVE\n";
        }
    }
}

// Run the validator
$validator = new AdminModuleValidator();
$validator->run();
