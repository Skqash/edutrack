<?php

namespace App\Services;

use App\Models\GradingScaleSetting;
use App\Models\AssessmentComponent;
use App\Models\ComponentEntry;
use App\Models\ComponentAverage;
use App\Models\Student;
use Illuminate\Support\Collection;

class GradingCalculationService
{
    /**
     * Calculate grades based on grading mode
     */
    public function calculateGrades(GradingScaleSetting $settings, $classId, $term = 'midterm')
    {
        return match($settings->grading_mode) {
            'standard' => $this->calculateStandardMode($settings, $classId, $term),
            'manual' => $this->calculateManualMode($settings, $classId, $term),
            'automated' => $this->calculateAutomatedMode($settings, $classId, $term),
            'hybrid' => $this->calculateHybridMode($settings, $classId, $term),
            default => []
        };
    }

    /**
     * Standard Mode: Traditional KSA with manual component entry
     * Components are manually entered, then averaged by category
     * Final grade = (Knowledge% × K_avg) + (Skills% × S_avg) + (Attitude% × A_avg)
     */
    private function calculateStandardMode(GradingScaleSetting $settings, $classId, $term)
    {
        $students = Student::where('class_id', $classId)->get();
        $results = [];

        foreach ($students as $student) {
            $categoryAverages = $this->getCategoryAverages($student->id, $classId, $term);
            
            $finalGrade = 0;
            if ($settings->enable_auto_calculation) {
                $finalGrade = ($categoryAverages['knowledge'] * $settings->knowledge_percentage / 100) +
                            ($categoryAverages['skills'] * $settings->skills_percentage / 100) +
                            ($categoryAverages['attitude'] * $settings->attitude_percentage / 100);
            }

            $result = $this->createGradeResult($student, $categoryAverages, $finalGrade, $term);
            $this->saveComponentAverage($student->id, $classId, $term, $categoryAverages, $finalGrade);
            $results[$student->id] = $result;
        }

        return $results;
    }

    /**
     * Manual Mode: Teachers enter all grades without auto-calculation
     * No component score requirements
     * Teachers can manually enter final grades directly
     */
    private function calculateManualMode(GradingScaleSetting $settings, $classId, $term)
    {
        $students = Student::where('class_id', $classId)->get();
        $results = [];

        foreach ($students as $student) {
            // In manual mode, grades are entered directly by teacher
            // No automatic calculation happens
            $result = [
                'student_id' => $student->id,
                'student_name' => $student->full_name,
                'knowledge' => null,
                'skills' => null,
                'attitude' => null,
                'final_grade' => null,
                'decimal_grade' => null,
                'mode' => 'manual',
                'components' => []
            ];
            
            $results[$student->id] = $result;
        }

        return $results;
    }

    /**
     * Automated Mode: System auto-calculates all grades from components
     * Each component score is normalized and weighted
     * Category averages are auto-calculated
     * Final grade is auto-calculated from categories
     */
    private function calculateAutomatedMode(GradingScaleSetting $settings, $classId, $term)
    {
        $students = Student::where('class_id', $classId)->get();
        $components = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->get();

        $results = [];

        foreach ($students as $student) {
            $categoryAverages = $this->calculateCategoryAveragesAutomated(
                $student->id, $classId, $term, $components, $settings
            );

            $finalGrade = ($categoryAverages['knowledge'] * $settings->knowledge_percentage / 100) +
                        ($categoryAverages['skills'] * $settings->skills_percentage / 100) +
                        ($categoryAverages['attitude'] * $settings->attitude_percentage / 100);

            $result = $this->createGradeResult($student, $categoryAverages, $finalGrade, $term);
            $this->saveComponentAverage($student->id, $classId, $term, $categoryAverages, $finalGrade);
            $results[$student->id] = $result;
        }

        return $results;
    }

    /**
     * Hybrid Mode: Mix of manual and automated components per class
     * Each component has its own entry_mode setting
     * Manual components are entered manually
     * Automated components are auto-calculated
     */
    private function calculateHybridMode(GradingScaleSetting $settings, $classId, $term)
    {
        $students = Student::where('class_id', $classId)->get();
        $components = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->get();

        $hybridConfig = $settings->getHybridComponentConfig();
        $results = [];

        foreach ($students as $student) {
            $categoryAverages = [
                'knowledge' => 0,
                'skills' => 0,
                'attitude' => 0
            ];

            // Calculate for each category
            foreach (['Knowledge', 'Skills', 'Attitude'] as $category) {
                $categoryComponents = $components->where('category', $category);
                $scores = [];

                foreach ($categoryComponents as $component) {
                    $componentMode = $hybridConfig[$component->id] ?? $component->entry_mode;
                    
                    if ($componentMode === 'manual') {
                        // Get manually entered score
                        $entry = ComponentEntry::where('student_id', $student->id)
                            ->where('component_id', $component->id)
                            ->where('term', $term)
                            ->first();
                        
                        if ($entry && $entry->normalized_score !== null) {
                            $scores[] = [
                                'score' => $entry->normalized_score,
                                'weight' => $component->weight
                            ];
                        }
                    } else {
                        // Auto-calculate
                        $autoScore = $this->calculateComponentScoreAutomated($student->id, $component, $term);
                        if ($autoScore !== null) {
                            $scores[] = [
                                'score' => $autoScore,
                                'weight' => $component->weight
                            ];
                        }
                    }
                }

                // Calculate weighted average for category
                if (!empty($scores)) {
                    $totalWeight = array_sum(array_column($scores, 'weight'));
                    if ($totalWeight > 0) {
                        $weightedSum = array_sum(array_map(fn($s) => $s['score'] * $s['weight'], $scores));
                        $categoryAverages[strtolower($category)] = round($weightedSum / $totalWeight, 2);
                    }
                }
            }

            $finalGrade = ($categoryAverages['knowledge'] * $settings->knowledge_percentage / 100) +
                        ($categoryAverages['skills'] * $settings->skills_percentage / 100) +
                        ($categoryAverages['attitude'] * $settings->attitude_percentage / 100);

            $result = $this->createGradeResult($student, $categoryAverages, $finalGrade, $term);
            $this->saveComponentAverage($student->id, $classId, $term, $categoryAverages, $finalGrade);
            $results[$student->id] = $result;
        }

        return $results;
    }

    /**
     * Get category averages (manual entry)
     */
    private function getCategoryAverages($studentId, $classId, $term): array
    {
        $components = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->get();

        $averages = [
            'knowledge' => 0,
            'skills' => 0,
            'attitude' => 0
        ];

        foreach (['Knowledge', 'Skills', 'Attitude'] as $category) {
            $categoryComponents = $components->where('category', $category);
            $scores = [];

            foreach ($categoryComponents as $component) {
                $entry = ComponentEntry::where('student_id', $studentId)
                    ->where('component_id', $component->id)
                    ->where('term', $term)
                    ->first();

                if ($entry && $entry->normalized_score !== null) {
                    $scores[] = [
                        'score' => $entry->normalized_score,
                        'weight' => $component->weight ?? (100 / $categoryComponents->count())
                    ];
                }
            }

            if (!empty($scores)) {
                $totalWeight = array_sum(array_column($scores, 'weight'));
                if ($totalWeight > 0) {
                    $weightedSum = array_sum(array_map(fn($s) => $s['score'] * $s['weight'], $scores));
                    $averages[strtolower($category)] = round($weightedSum / $totalWeight, 2);
                }
            }
        }

        return $averages;
    }

    /**
     * Calculate category averages (automated)
     */
    private function calculateCategoryAveragesAutomated($studentId, $classId, $term, Collection $components, GradingScaleSetting $settings): array
    {
        $averages = [
            'knowledge' => 0,
            'skills' => 0,
            'attitude' => 0
        ];

        foreach (['Knowledge', 'Skills', 'Attitude'] as $category) {
            $categoryComponents = $components->where('category', $category);
            $scores = [];

            foreach ($categoryComponents as $component) {
                $score = $this->calculateComponentScoreAutomated($studentId, $component, $term);
                if ($score !== null) {
                    $scores[] = [
                        'score' => $score,
                        'weight' => $component->weight ?? (100 / $categoryComponents->count())
                    ];
                }
            }

            if (!empty($scores)) {
                $totalWeight = array_sum(array_column($scores, 'weight'));
                if ($totalWeight > 0) {
                    $weightedSum = array_sum(array_map(fn($s) => $s['score'] * $s['weight'], $scores));
                    $averages[strtolower($category)] = round($weightedSum / $totalWeight, 2);
                }
            }
        }

        return $averages;
    }

    /**
     * Calculate single component score (automated)
     */
    private function calculateComponentScoreAutomated($studentId, AssessmentComponent $component, $term): ?float
    {
        if (!$component->calculation_formula) {
            return null;
        }

        $entries = ComponentEntry::where('student_id', $studentId)
            ->where('component_id', $component->id)
            ->where('term', $term)
            ->get();

        if ($entries->isEmpty()) {
            return null;
        }

        // Handle multiple attempts
        if ($component->use_best_attempt) {
            $score = $entries->max('normalized_score');
        } elseif ($component->use_average_attempt) {
            $score = $entries->avg('normalized_score');
        } else {
            // Use latest attempt
            $score = $entries->latest()->first()->normalized_score;
        }

        return $score ? round($score, 2) : null;
    }

    /**
     * Convert percentage grade to decimal grade
     * 98+ = 1.0, 95-97 = 1.25, ..., 70-74 = 3.50, below 70 = 5.0
     */
    private function getDecimalGrade(float $percentage): float
    {
        if ($percentage >= 98) return 1.0;
        if ($percentage >= 95) return 1.25;
        if ($percentage >= 92) return 1.50;
        if ($percentage >= 89) return 1.75;
        if ($percentage >= 86) return 2.0;
        if ($percentage >= 83) return 2.25;
        if ($percentage >= 80) return 2.50;
        if ($percentage >= 77) return 2.75;
        if ($percentage >= 74) return 3.0;
        if ($percentage >= 71) return 3.25;
        if ($percentage >= 70) return 3.50;
        return 5.0;
    }

    /**
     * Create grade result structure
     */
    private function createGradeResult(Student $student, array $categoryAverages, float $finalGrade, $term): array
    {
        return [
            'student_id' => $student->id,
            'student_name' => $student->full_name,
            'student_number' => $student->student_id,
            'knowledge' => round($categoryAverages['knowledge'], 2),
            'skills' => round($categoryAverages['skills'], 2),
            'attitude' => round($categoryAverages['attitude'], 2),
            'final_grade' => round($finalGrade, 2),
            'decimal_grade' => $this->getDecimalGrade($finalGrade),
            'is_passing' => $finalGrade >= 75,
            'term' => $term,
        ];
    }

    /**
     * Save to component_averages table for caching
     */
    private function saveComponentAverage($studentId, $classId, $term, array $averages, float $finalGrade): void
    {
        ComponentAverage::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_id' => $classId,
                'term' => $term,
            ],
            [
                'knowledge_average' => $averages['knowledge'],
                'skills_average' => $averages['skills'],
                'attitude_average' => $averages['attitude'],
                'final_grade' => $finalGrade,
            ]
        );
    }
}
