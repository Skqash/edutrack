<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeComponent;
use App\Models\KsaSetting;
use App\Models\ClassModel;

class GradeComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all classes
        $classes = ClassModel::all();

        foreach ($classes as $class) {
            // Skip if components already exist
            if (GradeComponent::where('class_id', $class->id)->exists()) {
                $this->command->info("Components already exist for class: {$class->class_name}");
                continue;
            }

            $this->command->info("Creating components for class: {$class->class_name}");

            // Create KSA settings for both terms
            foreach (['midterm', 'final'] as $term) {
                KsaSetting::firstOrCreate(
                    ['class_id' => $class->id, 'term' => $term],
                    [
                        'teacher_id' => $class->teacher_id,
                        'knowledge_percentage' => 40.00,
                        'skills_percentage' => 50.00,
                        'attitude_percentage' => 10.00,
                        'is_locked' => false,
                    ]
                );
            }

            // Knowledge components
            $knowledgeComponents = [
                ['type' => 'Exam', 'name' => 'Midterm Exam', 'max' => 100, 'weight' => 60],
                ['type' => 'Quiz', 'name' => 'Quiz 1', 'max' => 25, 'weight' => 8],
                ['type' => 'Quiz', 'name' => 'Quiz 2', 'max' => 25, 'weight' => 8],
                ['type' => 'Quiz', 'name' => 'Quiz 3', 'max' => 25, 'weight' => 8],
                ['type' => 'Quiz', 'name' => 'Quiz 4', 'max' => 25, 'weight' => 8],
                ['type' => 'Quiz', 'name' => 'Quiz 5', 'max' => 25, 'weight' => 8],
            ];

            foreach ($knowledgeComponents as $index => $comp) {
                GradeComponent::create([
                    'class_id' => $class->id,
                    'teacher_id' => $class->teacher_id,
                    'category' => 'Knowledge',
                    'subcategory' => $comp['type'],
                    'name' => $comp['name'],
                    'max_score' => $comp['max'],
                    'weight' => $comp['weight'],
                    'order' => $index + 1,
                    'is_active' => true,
                ]);
            }

            // Skills components
            $skillsComponents = [
                ['type' => 'Output', 'name' => 'Output 1', 'max' => 100, 'weight' => 13.33],
                ['type' => 'Output', 'name' => 'Output 2', 'max' => 100, 'weight' => 13.33],
                ['type' => 'Output', 'name' => 'Output 3', 'max' => 100, 'weight' => 13.34],
                ['type' => 'Participation', 'name' => 'Class Participation 1', 'max' => 100, 'weight' => 10],
                ['type' => 'Participation', 'name' => 'Class Participation 2', 'max' => 100, 'weight' => 10],
                ['type' => 'Participation', 'name' => 'Class Participation 3', 'max' => 100, 'weight' => 10],
                ['type' => 'Activity', 'name' => 'Activity 1', 'max' => 100, 'weight' => 5],
                ['type' => 'Activity', 'name' => 'Activity 2', 'max' => 100, 'weight' => 5],
                ['type' => 'Activity', 'name' => 'Activity 3', 'max' => 100, 'weight' => 5],
                ['type' => 'Assignment', 'name' => 'Assignment 1', 'max' => 100, 'weight' => 5],
                ['type' => 'Assignment', 'name' => 'Assignment 2', 'max' => 100, 'weight' => 5],
                ['type' => 'Assignment', 'name' => 'Assignment 3', 'max' => 100, 'weight' => 5],
            ];

            foreach ($skillsComponents as $index => $comp) {
                GradeComponent::create([
                    'class_id' => $class->id,
                    'teacher_id' => $class->teacher_id,
                    'category' => 'Skills',
                    'subcategory' => $comp['type'],
                    'name' => $comp['name'],
                    'max_score' => $comp['max'],
                    'weight' => $comp['weight'],
                    'order' => $index + 1,
                    'is_active' => true,
                ]);
            }

            // Attitude components
            $attitudeComponents = [
                ['type' => 'Behavior', 'name' => 'Behavior 1', 'max' => 100, 'weight' => 16.67],
                ['type' => 'Behavior', 'name' => 'Behavior 2', 'max' => 100, 'weight' => 16.67],
                ['type' => 'Behavior', 'name' => 'Behavior 3', 'max' => 100, 'weight' => 16.66],
                ['type' => 'Attendance', 'name' => 'Attendance 1', 'max' => 100, 'weight' => 10],
                ['type' => 'Attendance', 'name' => 'Attendance 2', 'max' => 100, 'weight' => 10],
                ['type' => 'Attendance', 'name' => 'Attendance 3', 'max' => 100, 'weight' => 10],
                ['type' => 'Awareness', 'name' => 'Awareness 1', 'max' => 100, 'weight' => 10],
                ['type' => 'Awareness', 'name' => 'Awareness 2', 'max' => 100, 'weight' => 10],
                ['type' => 'Awareness', 'name' => 'Awareness 3', 'max' => 100, 'weight' => 10],
            ];

            foreach ($attitudeComponents as $index => $comp) {
                GradeComponent::create([
                    'class_id' => $class->id,
                    'teacher_id' => $class->teacher_id,
                    'category' => 'Attitude',
                    'subcategory' => $comp['type'],
                    'name' => $comp['name'],
                    'max_score' => $comp['max'],
                    'weight' => $comp['weight'],
                    'order' => $index + 1,
                    'is_active' => true,
                ]);
            }

            $this->command->info("✅ Created components for class: {$class->class_name}");
        }

        $this->command->info("🎉 Grade components seeding complete!");
    }
}
