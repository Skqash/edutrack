<?php

namespace Database\Seeders;

use App\Models\AssessmentComponent;
use App\Models\ComponentEntry;
use App\Models\ComponentAverage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DynamicGradingExampleSeeder extends Seeder
{
    /**
     * Seed the database with example dynamic grading setup.
     * 
     * This demonstrates how to:
     * 1. Create flexible assessment components
     * 2. Add student grade entries
     * 3. Calculate and cache averages
     */
    public function run(): void
    {
        // Example: Set up dynamic grading for a class
        $classId = 1; // Replace with actual class ID
        $teacherId = 1; // Replace with actual teacher ID

        $this->command->info('Setting up dynamic grading example...');

        // ============================================
        // STEP 1: Create Knowledge Components (Exams + Quizzes)
        // ============================================
        $knowledgeComponents = [
            [
                'name' => 'Quiz 1',
                'subcategory' => 'Quiz',
                'max_score' => 25,
                'weight' => 10,
                'order' => 1,
            ],
            [
                'name' => 'Quiz 2',
                'subcategory' => 'Quiz',
                'max_score' => 25,
                'weight' => 10,
                'order' => 2,
            ],
            [
                'name' => 'Quiz 3',
                'subcategory' => 'Quiz',
                'max_score' => 25,
                'weight' => 10,
                'order' => 3,
            ],
            [
                'name' => 'Midterm Exam',
                'subcategory' => 'Exam',
                'max_score' => 100,
                'weight' => 70,
                'order' => 4,
            ],
        ];

        foreach ($knowledgeComponents as $comp) {
            AssessmentComponent::firstOrCreate([
                'class_id' => $classId,
                'teacher_id' => $teacherId,
                'category' => 'Knowledge',
            ], array_merge($comp, [
                'is_active' => true,
            ]));
        }

        $this->command->info('✓ Created Knowledge components');

        // ============================================
        // STEP 2: Create Skills Components
        // ============================================
        $skillsComponents = [
            [
                'name' => 'Output 1',
                'subcategory' => 'Output',
                'max_score' => 100,
                'weight' => 40,
                'order' => 1,
            ],
            [
                'name' => 'Class Participation',
                'subcategory' => 'Participation',
                'max_score' => 100,
                'weight' => 30,
                'order' => 2,
            ],
            [
                'name' => 'Activity 1',
                'subcategory' => 'Activity',
                'max_score' => 100,
                'weight' => 15,
                'order' => 3,
            ],
            [
                'name' => 'Assignment 1',
                'subcategory' => 'Assignment',
                'max_score' => 50,
                'weight' => 15,
                'order' => 4,
            ],
        ];

        foreach ($skillsComponents as $comp) {
            AssessmentComponent::firstOrCreate([
                'class_id' => $classId,
                'teacher_id' => $teacherId,
                'category' => 'Skills',
            ], array_merge($comp, [
                'is_active' => true,
            ]));
        }

        $this->command->info('✓ Created Skills components');

        // ============================================
        // STEP 3: Create Attitude Components
        // ============================================
        $attitudeComponents = [
            [
                'name' => 'Behavior',
                'subcategory' => 'Behavior',
                'max_score' => 100,
                'weight' => 50,
                'order' => 1,
            ],
            [
                'name' => 'Attendance',
                'subcategory' => 'Attendance',
                'max_score' => 30,
                'weight' => 50,
                'order' => 2,
            ],
        ];

        foreach ($attitudeComponents as $comp) {
            AssessmentComponent::firstOrCreate([
                'class_id' => $classId,
                'teacher_id' => $teacherId,
                'category' => 'Attitude',
            ], array_merge($comp, [
                'is_active' => true,
            ]));
        }

        $this->command->info('✓ Created Attitude components');

        // ============================================
        // STEP 4: Create Sample Student Entries
        // ============================================
        // Get all components to link entries
        $components = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->get();

        $studentIds = [1, 2, 3]; // Replace with actual student IDs
        $term = 'midterm';

        $sampleScores = [
            // Student 1 - High performer
            1 => [
                'Quiz 1' => 24,
                'Quiz 2' => 25,
                'Quiz 3' => 23,
                'Midterm Exam' => 92,
                'Output 1' => 95,
                'Class Participation' => 90,
                'Activity 1' => 88,
                'Assignment 1' => 48,
                'Behavior' => 95,
                'Attendance' => 28,
            ],
            // Student 2 - Medium performer
            2 => [
                'Quiz 1' => 20,
                'Quiz 2' => 22,
                'Quiz 3' => 19,
                'Midterm Exam' => 78,
                'Output 1' => 78,
                'Class Participation' => 75,
                'Activity 1' => 72,
                'Assignment 1' => 35,
                'Behavior' => 80,
                'Attendance' => 22,
            ],
            // Student 3 - Low performer
            3 => [
                'Quiz 1' => 15,
                'Quiz 2' => 16,
                'Quiz 3' => 14,
                'Midterm Exam' => 65,
                'Output 1' => 60,
                'Class Participation' => 58,
                'Activity 1' => 55,
                'Assignment 1' => 20,
                'Behavior' => 65,
                'Attendance' => 18,
            ],
        ];

        foreach ($studentIds as $studentId) {
            if (!isset($sampleScores[$studentId])) {
                continue;
            }

            $scores = $sampleScores[$studentId];

            foreach ($components as $component) {
                $rawScore = $scores[$component->name] ?? 0;

                if ($rawScore === 0) {
                    continue;
                }

                ComponentEntry::create([
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'component_id' => $component->id,
                    'term' => $term,
                    'raw_score' => $rawScore,
                    // normalized_score is auto-calculated on save
                ]);
            }

            // Calculate and cache averages
            ComponentAverage::calculateAndUpdate($studentId, $classId, $term);
        }

        $this->command->info('✓ Created sample student entries');

        // ============================================
        // STEP 5: Display Results
        // ============================================
        $averages = ComponentAverage::where('class_id', $classId)
            ->where('term', $term)
            ->with('student')
            ->get();

        $this->command->info("\nGrade Summary for {$term} term:");
        $this->command->line('');

        foreach ($averages as $avg) {
            $this->command->line(sprintf(
                "Student %d: K=%.2f S=%.2f A=%.2f Final=%.2f (%.2f)",
                $avg->student_id,
                $avg->knowledge_average,
                $avg->skills_average,
                $avg->attitude_average,
                $avg->final_grade,
                $avg->getDecimalGrade()
            ));
        }

        $this->command->info("\n✅ Dynamic grading setup complete!");
    }

    /**
     * Example: How to add a new component to an existing class
     */
    public function addNewComponent($classId, $teacherId)
    {
        $component = AssessmentComponent::create([
            'class_id' => $classId,
            'teacher_id' => $teacherId,
            'category' => 'Skills',
            'subcategory' => 'Output',
            'name' => 'Output 2',
            'max_score' => 100,
            'weight' => 40,
            'order' => 2,
            'is_active' => true,
        ]);

        return $component;
    }

    /**
     * Example: How to update existing component
     */
    public function updateComponent($componentId, $newWeight)
    {
        $component = AssessmentComponent::find($componentId);
        $component->update(['weight' => $newWeight]);
        return $component;
    }

    /**
     * Example: How to deactivate a component (soft delete)
     */
    public function deactivateComponent($componentId)
    {
        $component = AssessmentComponent::find($componentId);
        $component->update(['is_active' => false]);
        return $component;
    }

    /**
     * Example: How to delete a component & all its entries
     */
    public function deleteComponent($componentId)
    {
        $component = AssessmentComponent::find($componentId);
        
        // Delete all entries for this component
        ComponentEntry::where('component_id', $componentId)->delete();
        
        // Delete the component
        $component->delete();
        
        // Recalculate all affected student averages
        // (In production, use a job queue for this)
    }

    /**
     * Example: How to get student's detailed grade report
     */
    public function getStudentReport($studentId, $classId, $term)
    {
        $entries = ComponentEntry::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->with('component')
            ->get();

        $byCategory = $entries->groupBy('component.category');

        $report = [
            'student_id' => $studentId,
            'class_id' => $classId,
            'term' => $term,
            'components' => [],
            'averages' => ComponentAverage::where('student_id', $studentId)
                ->where('class_id', $classId)
                ->where('term', $term)
                ->first()?->only(['knowledge_average', 'skills_average', 'attitude_average', 'final_grade']),
        ];

        foreach ($byCategory as $category => $categoryEntries) {
            $report['components'][$category] = $categoryEntries->map(function ($entry) {
                return [
                    'component' => $entry->component->name,
                    'raw_score' => $entry->raw_score,
                    'max_score' => $entry->component->max_score,
                    'normalized' => $entry->normalized_score,
                    'weight' => $entry->component->weight,
                ];
            });
        }

        return $report;
    }
}
