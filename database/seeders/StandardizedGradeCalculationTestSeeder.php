<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\AssessmentComponent;
use App\Models\ComponentEntry;
use App\Models\Attendance;
use App\Models\GradingScaleSetting;
use App\Models\ComponentAverage;
use App\Services\AttendanceCalculationService;

class StandardizedGradeCalculationTestSeeder extends Seeder
{
    /**
     * Test the standardized (score/max) × 50 + 50 calculation for all components
     */
    public function run(): void
    {
        echo "\n╔══════════════════════════════════════════════════════════════╗\n";
        echo "║   STANDARDIZED GRADE CALCULATION TEST (×50+50 Formula)      ║\n";
        echo "╚══════════════════════════════════════════════════════════════╝\n\n";

        $class = ClassModel::first();
        
        if (!$class) {
            echo "❌ No classes found. Please run the main seeder first.\n";
            return;
        }

        echo "📚 Class: {$class->class_name} (ID: {$class->id})\n\n";

        // Configure settings
        $class->update([
            'total_meetings_midterm' => 34,
            'attendance_percentage' => 10.00,
        ]);

        GradingScaleSetting::updateOrCreate(
            ['class_id' => $class->id, 'term' => 'midterm'],
            [
                'teacher_id' => $class->teacher_id,
                'knowledge_percentage' => 40,
                'skills_percentage' => 50,
                'attitude_percentage' => 10,
            ]
        );

        // Clear existing components for this class
        AssessmentComponent::where('class_id', $class->id)->delete();

        // Create test components with subcategories
        $this->createTestComponents($class);

        $student = $class->students()->first();
        
        if (!$student) {
            echo "❌ No students found in class.\n";
            return;
        }

        echo "\n👤 Test Student: {$student->user->name} (ID: {$student->id})\n";
        echo str_repeat('=', 70) . "\n\n";

        // Seed test data
        $this->seedTestScores($student, $class);

        // Calculate and display results
        $this->displayDetailedCalculation($student, $class);

        echo "\n✅ Test completed successfully!\n\n";
    }

    private function createTestComponents($class)
    {
        echo "📝 Creating Assessment Components...\n\n";

        $components = [
            // KNOWLEDGE (40% of grade)
            ['category' => 'Knowledge', 'subcategory' => 'Quiz', 'name' => 'Quiz 1', 'max_score' => 40, 'weight' => 30],
            ['category' => 'Knowledge', 'subcategory' => 'Quiz', 'name' => 'Quiz 2', 'max_score' => 50, 'weight' => 30],
            ['category' => 'Knowledge', 'subcategory' => 'Quiz', 'name' => 'Quiz 3', 'max_score' => 45, 'weight' => 30],
            ['category' => 'Knowledge', 'subcategory' => 'Exam', 'name' => 'Midterm Exam', 'max_score' => 70, 'weight' => 70],
            
            // SKILLS (50% of grade)
            ['category' => 'Skills', 'subcategory' => 'Lab', 'name' => 'Lab Activity 1', 'max_score' => 50, 'weight' => 25],
            ['category' => 'Skills', 'subcategory' => 'Lab', 'name' => 'Lab Activity 2', 'max_score' => 50, 'weight' => 25],
            ['category' => 'Skills', 'subcategory' => 'Project', 'name' => 'Final Project', 'max_score' => 100, 'weight' => 50],
            
            // ATTITUDE (10% of grade)
            ['category' => 'Attitude', 'subcategory' => 'Participation', 'name' => 'Class Participation', 'max_score' => 100, 'weight' => 60],
            ['category' => 'Attitude', 'subcategory' => 'Behavior', 'name' => 'Behavior Rating', 'max_score' => 100, 'weight' => 40],
        ];

        foreach ($components as $index => $comp) {
            AssessmentComponent::create([
                'class_id' => $class->id,
                'teacher_id' => $class->teacher_id,
                'category' => $comp['category'],
                'subcategory' => $comp['subcategory'],
                'name' => $comp['name'],
                'max_score' => $comp['max_score'],
                'weight' => $comp['weight'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
            
            echo "   ✓ {$comp['category']} - {$comp['subcategory']}: {$comp['name']} ({$comp['max_score']} pts, {$comp['weight']}% weight)\n";
        }

        echo "\n";
    }

    private function seedTestScores($student, $class)
    {
        echo "📊 Entering Test Scores...\n\n";

        $scores = [
            // Knowledge - Quizzes
            'Quiz 1' => ['raw' => 36, 'max' => 40],
            'Quiz 2' => ['raw' => 45, 'max' => 50],
            'Quiz 3' => ['raw' => 40, 'max' => 45],
            // Knowledge - Exam
            'Midterm Exam' => ['raw' => 56, 'max' => 70],
            
            // Skills - Labs
            'Lab Activity 1' => ['raw' => 45, 'max' => 50],
            'Lab Activity 2' => ['raw' => 48, 'max' => 50],
            // Skills - Project
            'Final Project' => ['raw' => 85, 'max' => 100],
            
            // Attitude
            'Class Participation' => ['raw' => 90, 'max' => 100],
            'Behavior Rating' => ['raw' => 95, 'max' => 100],
        ];

        $components = AssessmentComponent::where('class_id', $class->id)->get()->keyBy('name');

        foreach ($scores as $name => $score) {
            $component = $components[$name];
            
            ComponentEntry::create([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'component_id' => $component->id,
                'term' => 'midterm',
                'raw_score' => $score['raw'],
            ]);
            
            $normalized = ($score['raw'] / $score['max']) * 50 + 50;
            echo "   {$name}: {$score['raw']}/{$score['max']} → " . round($normalized, 2) . "\n";
        }

        // Add attendance
        echo "\n📅 Attendance: 30/34 meetings\n";
        $this->createAttendance($student->id, $class->id, 30, 0, 'Midterm');

        echo "\n";
    }

    private function createAttendance($studentId, $classId, $presentCount, $lateCount, $term)
    {
        $totalRecords = $presentCount + $lateCount;
        $startDate = now()->subDays($totalRecords);

        for ($i = 0; $i < $presentCount; $i++) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'date' => $startDate->copy()->addDays($i)->format('Y-m-d'),
                    'term' => $term,
                ],
                ['status' => 'Present']
            );
        }

        for ($i = 0; $i < $lateCount; $i++) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'date' => $startDate->copy()->addDays($presentCount + $i)->format('Y-m-d'),
                    'term' => $term,
                ],
                ['status' => 'Late']
            );
        }

        $service = new AttendanceCalculationService();
        $service->updateStudentAttendanceRecord($studentId, $classId, $term);
    }

    private function displayDetailedCalculation($student, $class)
    {
        echo "╔══════════════════════════════════════════════════════════════╗\n";
        echo "║                  DETAILED CALCULATION                        ║\n";
        echo "╚══════════════════════════════════════════════════════════════╝\n\n";

        // Calculate component averages
        ComponentAverage::calculateAndUpdate($student->id, $class->id, 'midterm');

        $entries = ComponentEntry::where('student_id', $student->id)
            ->where('class_id', $class->id)
            ->where('term', 'midterm')
            ->with('component')
            ->get();

        // Group by category
        $categories = $entries->groupBy('component.category');

        // KNOWLEDGE CALCULATION
        echo "═══ KNOWLEDGE (K) - 40% of Final Grade ═══\n\n";
        $this->displayCategoryCalculation($categories->get('Knowledge', collect()), 'Knowledge');

        // SKILLS CALCULATION
        echo "\n═══ SKILLS (S) - 50% of Final Grade ═══\n\n";
        $this->displayCategoryCalculation($categories->get('Skills', collect()), 'Skills');

        // ATTITUDE CALCULATION
        echo "\n═══ ATTITUDE (A) - 10% of Final Grade ═══\n\n";
        $this->displayCategoryCalculation($categories->get('Attitude', collect()), 'Attitude');

        // ATTENDANCE CALCULATION
        echo "\n═══ ATTENDANCE - 10% of Final Grade ═══\n\n";
        $attendanceService = new AttendanceCalculationService();
        $attendanceData = $attendanceService->calculateAttendanceScore($student->id, $class->id, 'Midterm');
        
        echo "Formula: (Attended / Total) × 50 + 50\n";
        echo "Calculation: ({$attendanceData['attendance_count']}/34) × 50 + 50\n";
        echo "Attendance Score: {$attendanceData['attendance_score']}\n";
        echo "Weight: 10%\n";
        echo "Contribution: " . round($attendanceData['attendance_score'] * 0.10, 2) . " points\n";

        // FINAL GRADE
        echo "\n╔══════════════════════════════════════════════════════════════╗\n";
        echo "║                      FINAL GRADE                             ║\n";
        echo "╚══════════════════════════════════════════════════════════════╝\n\n";

        $average = ComponentAverage::where('student_id', $student->id)
            ->where('class_id', $class->id)
            ->where('term', 'midterm')
            ->first();

        if ($average) {
            $kContribution = $average->knowledge_average * 0.36; // 40% of 90%
            $sContribution = $average->skills_average * 0.45; // 50% of 90%
            $aContribution = $average->attitude_average * 0.09; // 10% of 90%
            $attContribution = $attendanceData['attendance_score'] * 0.10;

            echo "Knowledge (K):  {$average->knowledge_average} × 36% = " . round($kContribution, 2) . "\n";
            echo "Skills (S):     {$average->skills_average} × 45% = " . round($sContribution, 2) . "\n";
            echo "Attitude (A):   {$average->attitude_average} × 9%  = " . round($aContribution, 2) . "\n";
            echo "Attendance:     {$attendanceData['attendance_score']} × 10% = " . round($attContribution, 2) . "\n";
            echo str_repeat('-', 60) . "\n";
            echo "FINAL GRADE: {$average->final_grade}\n";
            echo "Letter Grade: " . $average->getLetterGrade() . "\n";
            echo "Decimal Grade: " . $average->getDecimalGrade() . "\n";
        }
    }

    private function displayCategoryCalculation($entries, $categoryName)
    {
        if ($entries->isEmpty()) {
            echo "No entries for {$categoryName}\n";
            return;
        }

        $subcategories = $entries->groupBy('component.subcategory');

        foreach ($subcategories as $subcategory => $subEntries) {
            echo "--- {$subcategory} ---\n";
            
            $scores = [];
            foreach ($subEntries as $entry) {
                $comp = $entry->component;
                echo "  {$comp->name}: {$entry->raw_score}/{$comp->max_score} → {$entry->normalized_score}\n";
                $scores[] = $entry->normalized_score;
            }
            
            if (count($scores) > 1) {
                $avg = array_sum($scores) / count($scores);
                echo "  Average: " . round($avg, 2) . "\n";
            }
            
            $weight = $subEntries->first()->component->weight;
            echo "  Weight in category: {$weight}%\n\n";
        }

        // Calculate category total
        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($subcategories as $subEntries) {
            $subcategoryAvg = $subEntries->avg('normalized_score');
            $weight = $subEntries->first()->component->weight;
            
            $weightedSum += $subcategoryAvg * $weight;
            $totalWeight += $weight;
        }

        $categoryAverage = $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : 0;
        echo "→ {$categoryName} Average: {$categoryAverage}\n";
    }
}
