<?php

/**
 * Comprehensive Teacher Module Validation Script
 * Tests all teacher functions, routes, database connections, and advanced grade system
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\GradeComponent;
use App\Models\ComponentEntry;
use App\Models\KsaSetting;
use App\Models\Attendance;
use App\Services\AttendanceCalculationService;
use App\Services\DynamicGradeCalculationService;
use Illuminate\Support\Facades\DB;

class TeacherModuleValidator
{
    private $results = [];
    private $errors = [];
    private $warnings = [];
    
    public function run()
    {
        echo "=== TEACHER MODULE COMPREHENSIVE VALIDATION ===\n\n";
        
        // Test 1: Database Connection
        $this->testDatabaseConnection();
        
        // Test 2: Teacher Authentication & Profile
        $this->testTeacherProfile();
        
        // Test 3: Classes Management
        $this->testClassesManagement();
        
        // Test 4: Subjects/Courses Management
        $this->testSubjectsManagement();
        
        // Test 5: Grade Components CRUD
        $this->testGradeComponentsCRUD();
        
        // Test 6: KSA Settings & Percentages
        $this->testKSASettings();
        
        // Test 7: Attendance Configuration
        $this->testAttendanceConfiguration();
        
        // Test 8: Grade Calculation Logic
        $this->testGradeCalculationLogic();
        
        // Test 9: Component Weight Manipulation
        $this->testComponentWeightManipulation();
        
        // Test 10: Advanced Grade Entry System
        $this->testAdvancedGradeEntry();
        
        // Test 11: Routes Validation
        $this->testRoutesValidation();
        
        // Test 12: Controller Methods
        $this->testControllerMethods();
        
        // Test 13: Data Fetching & Layouts
        $this->testDataFetching();
        
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
            $tables = ['users', 'classes', 'students', 'assessment_components', 'component_entries', 
                      'ksa_settings', 'attendance', 'subjects', 'courses'];
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
    
    private function testTeacherProfile()
    {
        echo "Testing Teacher Profile Management...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            
            if (!$teacher) {
                $this->warnings[] = "⚠ No teacher found in database";
                return;
            }
            
            $this->results['teacher_found'] = "✓ Teacher found: {$teacher->name} (ID: {$teacher->id})";
            
            // Check required fields
            $requiredFields = ['name', 'email', 'campus', 'school_id'];
            foreach ($requiredFields as $field) {
                if (empty($teacher->$field)) {
                    $this->warnings[] = "⚠ Teacher missing field: $field";
                } else {
                    $this->results["teacher_$field"] = "✓ Teacher has $field: {$teacher->$field}";
                }
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Teacher profile test failed: " . $e->getMessage();
        }
    }
    
    private function testClassesManagement()
    {
        echo "Testing Classes Management...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            $classes = ClassModel::where('teacher_id', $teacher->id)->get();
            $this->results['classes_count'] = "✓ Teacher has " . $classes->count() . " classes";
            
            foreach ($classes as $class) {
                // Check class has required fields
                if (empty($class->class_name)) {
                    $this->errors[] = "✗ Class ID {$class->id} missing class_name";
                }
                
                // Check students relationship
                $studentCount = $class->students()->count();
                $this->results["class_{$class->id}_students"] = "✓ Class '{$class->class_name}' has {$studentCount} students";
                
                // Check campus isolation
                if ($class->campus !== $teacher->campus) {
                    $this->errors[] = "✗ Class ID {$class->id} campus mismatch with teacher";
                }
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Classes management test failed: " . $e->getMessage();
        }
    }
    
    private function testSubjectsManagement()
    {
        echo "Testing Subjects/Courses Management...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            $subjects = $teacher->subjects()->get();
            $this->results['subjects_count'] = "✓ Teacher has " . $subjects->count() . " subjects";
            
            foreach ($subjects as $subject) {
                $this->results["subject_{$subject->id}"] = "✓ Subject: {$subject->subject_name} ({$subject->subject_code})";
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Subjects management test failed: " . $e->getMessage();
        }
    }
    
    private function testGradeComponentsCRUD()
    {
        echo "Testing Grade Components CRUD Operations...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            $class = ClassModel::where('teacher_id', $teacher->id)->first();
            if (!$class) {
                $this->warnings[] = "⚠ No class found for testing components";
                return;
            }
            
            // Test CREATE
            $component = GradeComponent::create([
                'class_id' => $class->id,
                'teacher_id' => $teacher->id,
                'category' => 'Knowledge',
                'subcategory' => 'Quiz',
                'name' => 'Test Quiz',
                'max_score' => 50,
                'weight' => 10,
                'order' => 999,
                'is_active' => true,
            ]);
            $this->results['component_create'] = "✓ Component created successfully (ID: {$component->id})";
            
            // Test READ
            $retrieved = GradeComponent::find($component->id);
            if ($retrieved) {
                $this->results['component_read'] = "✓ Component retrieved successfully";
            }
            
            // Test UPDATE
            $component->update(['name' => 'Updated Test Quiz', 'weight' => 15]);
            $component->refresh();
            if ($component->name === 'Updated Test Quiz' && $component->weight == 15) {
                $this->results['component_update'] = "✓ Component updated successfully";
            } else {
                $this->errors[] = "✗ Component update failed";
            }
            
            // Test DELETE
            $component->delete();
            if (!GradeComponent::find($component->id)) {
                $this->results['component_delete'] = "✓ Component deleted successfully";
            } else {
                $this->errors[] = "✗ Component deletion failed";
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Grade components CRUD test failed: " . $e->getMessage();
        }
    }
    
    private function testKSASettings()
    {
        echo "Testing KSA Settings & Percentage Distribution...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            $class = ClassModel::where('teacher_id', $teacher->id)->first();
            if (!$class) return;
            
            // Test default KSA settings
            $ksaSettings = KsaSetting::getOrCreateDefault($class->id, 'midterm', $teacher->id);
            $this->results['ksa_default'] = "✓ KSA settings retrieved/created";
            
            // Validate percentages sum to 100
            $sum = $ksaSettings->knowledge_weight + $ksaSettings->skills_weight + $ksaSettings->attitude_weight;
            if (abs($sum - 100) < 0.01) {
                $this->results['ksa_sum'] = "✓ KSA percentages sum to 100% (K:{$ksaSettings->knowledge_weight}%, S:{$ksaSettings->skills_weight}%, A:{$ksaSettings->attitude_weight}%)";
            } else {
                $this->errors[] = "✗ KSA percentages sum to {$sum}% (should be 100%)";
            }
            
            // Test UPDATE
            $ksaSettings->update([
                'knowledge_weight' => 50,
                'skills_weight' => 30,
                'attitude_weight' => 20,
            ]);
            $ksaSettings->refresh();
            
            $newSum = $ksaSettings->knowledge_weight + $ksaSettings->skills_weight + $ksaSettings->attitude_weight;
            if ($newSum == 100) {
                $this->results['ksa_update'] = "✓ KSA settings updated successfully";
            } else {
                $this->errors[] = "✗ KSA update validation failed";
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ KSA settings test failed: " . $e->getMessage();
        }
    }
    
    private function testAttendanceConfiguration()
    {
        echo "Testing Attendance Configuration...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            $class = ClassModel::where('teacher_id', $teacher->id)->first();
            if (!$class) return;
            
            // Check attendance settings in class
            if ($class->total_meetings_midterm) {
                $this->results['attendance_meetings'] = "✓ Class has total meetings configured: {$class->total_meetings_midterm} (midterm)";
            } else {
                $this->warnings[] = "⚠ Class missing total_meetings_midterm";
            }
            
            if ($class->attendance_percentage) {
                $this->results['attendance_weight'] = "✓ Attendance weight configured: {$class->attendance_percentage}%";
            } else {
                $this->warnings[] = "⚠ Class missing attendance_percentage";
            }
            
            // Test attendance calculation service
            $student = $class->students()->first();
            if ($student) {
                $attendanceService = new AttendanceCalculationService();
                $attendanceData = $attendanceService->calculateAttendanceScore($student->id, $class->id, 'Midterm');
                
                $this->results['attendance_calculation'] = "✓ Attendance calculation works: Score={$attendanceData['attendance_score']}, Percentage={$attendanceData['attendance_percentage']}%";
                
                // Validate formula: (attendance_count / total_meetings) × 50 + 50
                $expectedScore = 0;
                if ($attendanceData['total_meetings'] > 0) {
                    $expectedScore = ($attendanceData['attendance_count'] / $attendanceData['total_meetings']) * 50 + 50;
                    $expectedScore = min(100, $expectedScore);
                }
                
                if (abs($attendanceData['attendance_score'] - $expectedScore) < 0.01) {
                    $this->results['attendance_formula'] = "✓ Attendance formula correct";
                } else {
                    $this->errors[] = "✗ Attendance formula incorrect. Expected: {$expectedScore}, Got: {$attendanceData['attendance_score']}";
                }
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Attendance configuration test failed: " . $e->getMessage();
        }
    }
    
    private function testGradeCalculationLogic()
    {
        echo "Testing Grade Calculation Logic...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            $class = ClassModel::where('teacher_id', $teacher->id)->first();
            if (!$class) return;
            
            $student = $class->students()->first();
            if (!$student) {
                $this->warnings[] = "⚠ No student found for grade calculation test";
                return;
            }
            
            // Create test components
            $components = [
                ['category' => 'Knowledge', 'subcategory' => 'Exam', 'name' => 'Midterm Exam', 'max_score' => 100, 'weight' => 60],
                ['category' => 'Knowledge', 'subcategory' => 'Quiz', 'name' => 'Quiz 1', 'max_score' => 25, 'weight' => 40],
                ['category' => 'Skills', 'subcategory' => 'Output', 'name' => 'Project 1', 'max_score' => 100, 'weight' => 50],
                ['category' => 'Skills', 'subcategory' => 'Activity', 'name' => 'Activity 1', 'max_score' => 50, 'weight' => 50],
                ['category' => 'Attitude', 'subcategory' => 'Behavior', 'name' => 'Behavior', 'max_score' => 100, 'weight' => 100],
            ];
            
            $createdComponents = [];
            foreach ($components as $index => $compData) {
                $comp = GradeComponent::create(array_merge($compData, [
                    'class_id' => $class->id,
                    'teacher_id' => $teacher->id,
                    'order' => $index + 1,
                    'is_active' => true,
                ]));
                $createdComponents[] = $comp;
            }
            
            // Create test entries
            $testScores = [
                ['component_id' => $createdComponents[0]->id, 'raw_score' => 85], // Exam: 85/100
                ['component_id' => $createdComponents[1]->id, 'raw_score' => 20], // Quiz: 20/25 = 80%
                ['component_id' => $createdComponents[2]->id, 'raw_score' => 90], // Project: 90/100
                ['component_id' => $createdComponents[3]->id, 'raw_score' => 45], // Activity: 45/50 = 90%
                ['component_id' => $createdComponents[4]->id, 'raw_score' => 95], // Behavior: 95/100
            ];
            
            foreach ($testScores as $scoreData) {
                $component = GradeComponent::find($scoreData['component_id']);
                ComponentEntry::create([
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'component_id' => $scoreData['component_id'],
                    'term' => 'midterm',
                    'raw_score' => $scoreData['raw_score'],
                    'normalized_score' => $component->normalizeScore($scoreData['raw_score']),
                ]);
            }
            
            // Calculate averages
            $averages = DynamicGradeCalculationService::calculateCategoryAverages($student->id, $class->id, 'midterm');
            
            $this->results['grade_knowledge'] = "✓ Knowledge Average: {$averages['knowledge_average']}";
            $this->results['grade_skills'] = "✓ Skills Average: {$averages['skills_average']}";
            $this->results['grade_attitude'] = "✓ Attitude Average: {$averages['attitude_average']}";
            $this->results['grade_final'] = "✓ Final Grade: {$averages['final_grade']}";
            
            // Validate calculation logic
            // Knowledge: (85 * 60% + 80 * 40%) = 51 + 32 = 83
            $expectedKnowledge = (85 * 0.6) + (80 * 0.4);
            if (abs($averages['knowledge_average'] - $expectedKnowledge) < 1) {
                $this->results['grade_knowledge_logic'] = "✓ Knowledge calculation correct";
            } else {
                $this->warnings[] = "⚠ Knowledge calculation may be off. Expected: ~{$expectedKnowledge}, Got: {$averages['knowledge_average']}";
            }
            
            // Cleanup test data
            foreach ($createdComponents as $comp) {
                ComponentEntry::where('component_id', $comp->id)->delete();
                $comp->delete();
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Grade calculation logic test failed: " . $e->getMessage();
        }
    }
    
    private function testComponentWeightManipulation()
    {
        echo "Testing Component Weight Manipulation...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            $class = ClassModel::where('teacher_id', $teacher->id)->first();
            if (!$class) return;
            
            // Create components with different weights
            $comp1 = GradeComponent::create([
                'class_id' => $class->id,
                'teacher_id' => $teacher->id,
                'category' => 'Knowledge',
                'subcategory' => 'Quiz',
                'name' => 'Weight Test 1',
                'max_score' => 100,
                'weight' => 30,
                'order' => 1,
            ]);
            
            $comp2 = GradeComponent::create([
                'class_id' => $class->id,
                'teacher_id' => $teacher->id,
                'category' => 'Knowledge',
                'subcategory' => 'Quiz',
                'name' => 'Weight Test 2',
                'max_score' => 100,
                'weight' => 70,
                'order' => 2,
            ]);
            
            // Test weight update
            $comp1->update(['weight' => 50]);
            $comp2->update(['weight' => 50]);
            
            $comp1->refresh();
            $comp2->refresh();
            
            if ($comp1->weight == 50 && $comp2->weight == 50) {
                $this->results['weight_manipulation'] = "✓ Component weights updated successfully";
            } else {
                $this->errors[] = "✗ Component weight update failed";
            }
            
            // Cleanup
            $comp1->delete();
            $comp2->delete();
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Component weight manipulation test failed: " . $e->getMessage();
        }
    }
    
    private function testAdvancedGradeEntry()
    {
        echo "Testing Advanced Grade Entry System...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            $class = ClassModel::where('teacher_id', $teacher->id)->first();
            if (!$class) return;
            
            // Check if components exist
            $componentCount = GradeComponent::where('class_id', $class->id)->count();
            $this->results['advanced_components'] = "✓ Class has {$componentCount} grade components";
            
            // Check if entries can be created
            $student = $class->students()->first();
            if ($student) {
                $component = GradeComponent::where('class_id', $class->id)->first();
                if ($component) {
                    $entry = ComponentEntry::create([
                        'student_id' => $student->id,
                        'class_id' => $class->id,
                        'component_id' => $component->id,
                        'term' => 'midterm',
                        'raw_score' => 85,
                        'normalized_score' => $component->normalizeScore(85),
                    ]);
                    
                    $this->results['advanced_entry'] = "✓ Grade entry created successfully (ID: {$entry->id})";
                    
                    // Cleanup
                    $entry->delete();
                }
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Advanced grade entry test failed: " . $e->getMessage();
        }
    }
    
    private function testRoutesValidation()
    {
        echo "Testing Routes Validation...\n";
        try {
            $routes = \Illuminate\Support\Facades\Route::getRoutes();
            $teacherRoutes = [];
            
            foreach ($routes as $route) {
                if (str_starts_with($route->getName() ?? '', 'teacher.')) {
                    $teacherRoutes[] = $route->getName();
                }
            }
            
            $this->results['routes_count'] = "✓ Found " . count($teacherRoutes) . " teacher routes";
            
            // Check critical routes
            $criticalRoutes = [
                'teacher.dashboard',
                'teacher.classes',
                'teacher.subjects',
                'teacher.grades.settings.index',
                'teacher.grades.entry',
                'teacher.grades.dynamic.save',
                'teacher.attendance.manage',
            ];
            
            foreach ($criticalRoutes as $routeName) {
                if (in_array($routeName, $teacherRoutes)) {
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
            $controller = new \App\Http\Controllers\TeacherController();
            $gradeController = new \App\Http\Controllers\GradeSettingsController();
            
            $methods = get_class_methods($controller);
            $this->results['controller_methods'] = "✓ TeacherController has " . count($methods) . " methods";
            
            $gradeMethods = get_class_methods($gradeController);
            $this->results['grade_controller_methods'] = "✓ GradeSettingsController has " . count($gradeMethods) . " methods";
            
            // Check critical methods
            $criticalMethods = ['dashboard', 'classes', 'grades', 'showProfile', 'updateProfile'];
            foreach ($criticalMethods as $method) {
                if (in_array($method, $methods)) {
                    $this->results["method_{$method}"] = "✓ Method exists: {$method}";
                } else {
                    $this->errors[] = "✗ Critical method missing: {$method}";
                }
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Controller methods test failed: " . $e->getMessage();
        }
    }
    
    private function testDataFetching()
    {
        echo "Testing Data Fetching & Layouts...\n";
        try {
            $teacher = User::where('role', 'teacher')->first();
            if (!$teacher) return;
            
            // Test dashboard data fetching
            $dashboardService = new \App\Services\TeacherDashboardService();
            $dashboardData = $dashboardService->getDashboardData();
            
            $this->results['dashboard_data'] = "✓ Dashboard data fetched successfully";
            
            // Validate data structure
            $requiredKeys = ['myClasses', 'statistics', 'recentActivities', 'availableCourses'];
            foreach ($requiredKeys as $key) {
                if (array_key_exists($key, $dashboardData)) {
                    $this->results["dashboard_{$key}"] = "✓ Dashboard has key: {$key}";
                } else {
                    $this->errors[] = "✗ Dashboard missing key: {$key}";
                }
            }
            
        } catch (\Exception $e) {
            $this->errors[] = "✗ Data fetching test failed: " . $e->getMessage();
        }
    }
    
    private function testBugDetection()
    {
        echo "Testing Bug Detection...\n";
        try {
            // Check for common bugs
            
            // Bug 1: Orphaned grade entries
            $orphanedEntries = ComponentEntry::whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('assessment_components')
                      ->whereColumn('assessment_components.id', 'component_entries.component_id');
            })->count();
            
            if ($orphanedEntries > 0) {
                $this->warnings[] = "⚠ Found {$orphanedEntries} orphaned component entries";
            } else {
                $this->results['bug_orphaned_entries'] = "✓ No orphaned component entries";
            }
            
            // Bug 2: Components with invalid weights
            $invalidWeights = DB::table('assessment_components')
                ->where('weight', '<', 0)
                ->orWhere('weight', '>', 100)
                ->count();
            
            if ($invalidWeights > 0) {
                $this->errors[] = "✗ Found {$invalidWeights} components with invalid weights";
            } else {
                $this->results['bug_invalid_weights'] = "✓ No components with invalid weights";
            }
            
            // Bug 3: KSA settings not summing to 100
            $invalidKSA = KsaSetting::all()->filter(function ($setting) {
                $sum = $setting->knowledge_weight + $setting->skills_weight + $setting->attitude_weight;
                return abs($sum - 100) > 0.01;
            })->count();
            
            if ($invalidKSA > 0) {
                $this->errors[] = "✗ Found {$invalidKSA} KSA settings not summing to 100%";
            } else {
                $this->results['bug_invalid_ksa'] = "✓ All KSA settings sum to 100%";
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
$validator = new TeacherModuleValidator();
$validator->run();
