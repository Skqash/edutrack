<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\User;
use App\Models\AssessmentComponent;
use App\Models\ComponentEntry;
use App\Models\Attendance;
use App\Models\GradingScaleSetting;
use App\Services\AttendanceCalculationService;
use Illuminate\Support\Facades\DB;

class AttendanceGradeTestSeeder extends Seeder
{
    /**
     * Seed test data for attendance and grade calculation verification
     */
    public function run(): void
    {
        echo "\n=== Attendance & Grade Calculation Test Seeder ===\n\n";

        // Find or create a test class
        $class = ClassModel::first();
        
        if (!$class) {
            echo "❌ No classes found. Please run the main seeder first.\n";
            return;
        }

        echo "📚 Using Class: {$class->class_name}\n";
        echo "   Class ID: {$class->id}\n\n";

        // Configure attendance settings
        $class->update([
            'total_meetings_midterm' => 34,
            'total_meetings_final' => 34,
            'attendance_percentage' => 10.00,
            'current_term' => 'Midterm',
        ]);

        echo "⚙️  Attendance Settings:\n";
        echo "   - Total Meetings (Midterm): 34\n";
        echo "   - Total Meetings (Final): 34\n";
        echo "   - Attendance Weight: 10%\n\n";

        // Configure KSA percentages
        GradingScaleSetting::updateOrCreate(
            [
                'class_id' => $class->id,
                'term' => 'midterm',
            ],
            [
                'teacher_id' => $class->teacher_id,
                'knowledge_percentage' => 40,
                'skills_percentage' => 50,
                'attitude_percentage' => 10,
            ]
        );

        echo "📊 KSA Settings (Midterm):\n";
        echo "   - Knowledge: 40%\n";
        echo "   - Skills: 50%\n";
        echo "   - Attitude: 10%\n";
        echo "   - Attendance: 10% (separate)\n\n";

        // Get or create test students
        $students = $class->students()->take(3)->get();

        if ($students->count() < 3) {
            echo "❌ Not enough students in class. Need at least 3 students.\n";
            return;
        }

        echo "👥 Test Students:\n";
        foreach ($students as $index => $student) {
            echo "   " . ($index + 1) . ". {$student->user->name} (ID: {$student->id})\n";
        }
        echo "\n";

        // Create assessment components if they don't exist
        $this->createAssessmentComponents($class->id);

        // Test Scenario 1: Perfect Student
        echo "=== Test Scenario 1: Perfect Student ===\n";
        $student1 = $students[0];
        $this->seedPerfectStudent($student1, $class->id);
        \App\Models\ComponentAverage::calculateAndUpdate($student1->id, $class->id, 'midterm');

        // Test Scenario 2: Good Student
        echo "\n=== Test Scenario 2: Good Student ===\n";
        $student2 = $students[1];
        $this->seedGoodStudent($student2, $class->id);
        \App\Models\ComponentAverage::calculateAndUpdate($student2->id, $class->id, 'midterm');

        // Test Scenario 3: Average Student
        echo "\n=== Test Scenario 3: Average Student ===\n";
        $student3 = $students[2];
        $this->seedAverageStudent($student3, $class->id);
        \App\Models\ComponentAverage::calculateAndUpdate($student3->id, $class->id, 'midterm');

        // Calculate and display results
        echo "\n=== CALCULATION RESULTS ===\n\n";
        $this->displayCalculationResults($student1, $class->id, 'Perfect Student');
        $this->displayCalculationResults($student2, $class->id, 'Good Student');
        $this->displayCalculationResults($student3, $class->id, 'Average Student');

        echo "\n✅ Test data seeded successfully!\n";
        echo "📝 Check the output above to verify calculation accuracy.\n\n";
    }

    private function createAssessmentComponents($classId)
    {
        $class = ClassModel::find($classId);
        
        $components = [
            // Knowledge Components (40%)
            ['category' => 'Knowledge', 'name' => 'Quiz 1', 'max_score' => 50, 'weight' => 20],
            ['category' => 'Knowledge', 'name' => 'Quiz 2', 'max_score' => 50, 'weight' => 20],
            ['category' => 'Knowledge', 'name' => 'Midterm Exam', 'max_score' => 100, 'weight' => 60],
            
            // Skills Components (50%)
            ['category' => 'Skills', 'name' => 'Lab Activity 1', 'max_score' => 50, 'weight' => 25],
            ['category' => 'Skills', 'name' => 'Lab Activity 2', 'max_score' => 50, 'weight' => 25],
            ['category' => 'Skills', 'name' => 'Project', 'max_score' => 100, 'weight' => 50],
            
            // Attitude Components (10%)
            ['category' => 'Attitude', 'name' => 'Class Participation', 'max_score' => 100, 'weight' => 50],
            ['category' => 'Attitude', 'name' => 'Behavior', 'max_score' => 100, 'weight' => 50],
        ];

        foreach ($components as $index => $comp) {
            AssessmentComponent::updateOrCreate(
                [
                    'class_id' => $classId,
                    'name' => $comp['name'],
                ],
                [
                    'teacher_id' => $class->teacher_id,
                    'category' => $comp['category'],
                    'subcategory' => 'General',
                    'max_score' => $comp['max_score'],
                    'weight' => $comp['weight'],
                    'order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }

        echo "✅ Assessment components created\n";
    }

    private function seedPerfectStudent($student, $classId)
    {
        echo "Student: {$student->user->name}\n";
        
        // Perfect attendance: 34/34
        $this->createAttendance($student->id, $classId, 34, 0, 'Midterm');
        echo "   Attendance: 34/34 (100%)\n";
        
        // Perfect scores in all components
        $components = AssessmentComponent::where('class_id', $classId)->get();
        foreach ($components as $comp) {
            ComponentEntry::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $classId,
                    'component_id' => $comp->id,
                    'term' => 'midterm',
                ],
                [
                    'raw_score' => $comp->max_score,
                ]
            );
        }
        echo "   Component Scores: All 100%\n";
    }

    private function seedGoodStudent($student, $classId)
    {
        echo "Student: {$student->user->name}\n";
        
        // Good attendance: 30/34 (88.24%)
        $this->createAttendance($student->id, $classId, 28, 2, 'Midterm'); // 28 present, 2 late
        echo "   Attendance: 30/34 (88.24%)\n";
        
        // Good scores (85-95%)
        $components = AssessmentComponent::where('class_id', $classId)->get();
        foreach ($components as $comp) {
            $score = $comp->max_score * 0.90; // 90% of max
            ComponentEntry::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $classId,
                    'component_id' => $comp->id,
                    'term' => 'midterm',
                ],
                [
                    'raw_score' => $score,
                ]
            );
        }
        echo "   Component Scores: All 90%\n";
    }

    private function seedAverageStudent($student, $classId)
    {
        echo "Student: {$student->user->name}\n";
        
        // Average attendance: 25/34 (73.53%)
        $this->createAttendance($student->id, $classId, 24, 1, 'Midterm'); // 24 present, 1 late
        echo "   Attendance: 25/34 (73.53%)\n";
        
        // Average scores (75-80%)
        $components = AssessmentComponent::where('class_id', $classId)->get();
        foreach ($components as $comp) {
            $score = $comp->max_score * 0.78; // 78% of max
            ComponentEntry::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $classId,
                    'component_id' => $comp->id,
                    'term' => 'midterm',
                ],
                [
                    'raw_score' => $score,
                ]
            );
        }
        echo "   Component Scores: All 78%\n";
    }

    private function createAttendance($studentId, $classId, $presentCount, $lateCount, $term)
    {
        $totalRecords = $presentCount + $lateCount;
        $startDate = now()->subDays($totalRecords);

        // Create present records
        for ($i = 0; $i < $presentCount; $i++) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'date' => $startDate->copy()->addDays($i)->format('Y-m-d'),
                    'term' => $term,
                ],
                [
                    'status' => 'Present',
                ]
            );
        }

        // Create late records
        for ($i = 0; $i < $lateCount; $i++) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'date' => $startDate->copy()->addDays($presentCount + $i)->format('Y-m-d'),
                    'term' => $term,
                ],
                [
                    'status' => 'Late',
                ]
            );
        }

        // Update attendance summary
        $service = new AttendanceCalculationService();
        $service->updateStudentAttendanceRecord($studentId, $classId, $term);
    }

    private function displayCalculationResults($student, $classId, $label)
    {
        echo "--- {$label}: {$student->user->name} ---\n";
        
        // Get attendance data
        $attendanceService = new AttendanceCalculationService();
        $attendanceData = $attendanceService->calculateAttendanceScore($student->id, $classId, 'Midterm');
        
        echo "📋 Attendance:\n";
        echo "   Present: {$attendanceData['present_count']}\n";
        echo "   Late: {$attendanceData['late_count']}\n";
        echo "   Absent: {$attendanceData['absent_count']}\n";
        echo "   Total Attended: {$attendanceData['attendance_count']} / {$attendanceData['total_meetings']}\n";
        echo "   Attendance %: {$attendanceData['attendance_percentage']}%\n";
        echo "   Formula: ({$attendanceData['attendance_count']}/{$attendanceData['total_meetings']}) × 50 + 50\n";
        echo "   Attendance Score: {$attendanceData['attendance_score']}\n";
        
        $class = ClassModel::find($classId);
        $attendanceContribution = $attendanceData['attendance_score'] * ($class->attendance_percentage / 100);
        echo "   Contribution (10%): " . round($attendanceContribution, 2) . " points\n\n";
        
        // Get component averages
        $average = \App\Models\ComponentAverage::where('student_id', $student->id)
            ->where('class_id', $classId)
            ->where('term', 'midterm')
            ->first();
        
        if ($average) {
            echo "📊 Component Averages:\n";
            echo "   Knowledge (40%): {$average->knowledge_average}\n";
            echo "   Skills (50%): {$average->skills_average}\n";
            echo "   Attitude (10%): {$average->attitude_average}\n";
            echo "   Subtotal (without attendance): {$average->final_grade}\n\n";
            
            // Calculate final grade with attendance
            $settings = \App\Models\GradingScaleSetting::getOrCreateDefault($classId, null, 'midterm');
            $componentWeight = 100 - $class->attendance_percentage;
            $adjustedComponentGrade = $average->final_grade * ($componentWeight / 100);
            $finalGradeWithAttendance = $adjustedComponentGrade + $attendanceContribution;
            
            echo "🎯 Final Grade Calculation:\n";
            echo "   Components (90%): {$average->final_grade} × 0.90 = " . round($adjustedComponentGrade, 2) . "\n";
            echo "   Attendance (10%): {$attendanceData['attendance_score']} × 0.10 = " . round($attendanceContribution, 2) . "\n";
            echo "   FINAL GRADE: " . round($finalGradeWithAttendance, 2) . "\n";
            echo "   Letter Grade: " . $this->getLetterGrade($finalGradeWithAttendance) . "\n";
            echo "   Decimal Grade: " . $this->getDecimalGrade($finalGradeWithAttendance) . "\n";
        } else {
            echo "⚠️  No component averages found. Run ComponentAverage::calculateAndUpdate()\n";
        }
        
        echo "\n";
    }

    private function getLetterGrade($grade)
    {
        if ($grade >= 98) return 'A+';
        elseif ($grade >= 95) return 'A';
        elseif ($grade >= 92) return 'A-';
        elseif ($grade >= 89) return 'B+';
        elseif ($grade >= 86) return 'B';
        elseif ($grade >= 83) return 'B-';
        elseif ($grade >= 80) return 'C+';
        elseif ($grade >= 77) return 'C';
        elseif ($grade >= 74) return 'C-';
        elseif ($grade >= 71) return 'D+';
        elseif ($grade >= 70) return 'D';
        else return 'F';
    }

    private function getDecimalGrade($grade)
    {
        if ($grade >= 98) return 1.0;
        elseif ($grade >= 95) return 1.25;
        elseif ($grade >= 92) return 1.50;
        elseif ($grade >= 89) return 1.75;
        elseif ($grade >= 86) return 2.00;
        elseif ($grade >= 83) return 2.25;
        elseif ($grade >= 80) return 2.50;
        elseif ($grade >= 77) return 2.75;
        elseif ($grade >= 74) return 3.00;
        elseif ($grade >= 71) return 3.25;
        elseif ($grade >= 70) return 3.50;
        else return 5.0;
    }
}
