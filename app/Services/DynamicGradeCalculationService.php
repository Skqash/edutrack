<?php

namespace App\Services;

use App\Models\ComponentEntry;
use App\Models\AssessmentComponent;
use App\Models\GradingScaleSetting;

class DynamicGradeCalculationService
{
    /**
     * Calculate all category averages for a student using flexible KSA percentages
     */
    public static function calculateCategoryAverages($studentId, $classId, $term)
    {
        $entries = ComponentEntry::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->with('component')
            ->get();

        if ($entries->isEmpty()) {
            return [
                'knowledge_average' => 0,
                'skills_average' => 0,
                'attitude_average' => 0,
                'final_grade' => 0,
            ];
        }

        // Group entries by category
        $byCategory = [];
        foreach ($entries as $entry) {
            $category = $entry->component->category;
            if (!isset($byCategory[$category])) {
                $byCategory[$category] = [];
            }
            $byCategory[$category][] = $entry;
        }

        // Calculate weighted average for each category
        $knowledgeAvg = self::calculateWeightedCategoryAverage($byCategory['Knowledge'] ?? []);
        $skillsAvg = self::calculateWeightedCategoryAverage($byCategory['Skills'] ?? []);
        $attitudeAvg = self::calculateWeightedCategoryAverage($byCategory['Attitude'] ?? []);

        // Get flexible KSA percentages from settings
        $settings = GradingScaleSetting::getOrCreateDefault($classId, null, $term);
        $kPercent = $settings->knowledge_percentage / 100;
        $sPercent = $settings->skills_percentage / 100;
        $aPercent = $settings->attitude_percentage / 100;

        // Calculate final grade using flexible percentages
        $finalGrade = ($knowledgeAvg * $kPercent) + ($skillsAvg * $sPercent) + ($attitudeAvg * $aPercent);

        return [
            'knowledge_average' => round($knowledgeAvg, 2),
            'skills_average' => round($skillsAvg, 2),
            'attitude_average' => round($attitudeAvg, 2),
            'final_grade' => round($finalGrade, 2),
        ];
    }

    /**
     * Calculate weighted average for entries in a category
     */
    private static function calculateWeightedCategoryAverage($entries)
    {
        if (empty($entries)) {
            return 0;
        }

        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($entries as $entry) {
            $weight = $entry->component->weight ?? 0;
            $normalizedScore = $entry->normalized_score ?? 0;

            $weightedSum += $normalizedScore * $weight;
            $totalWeight += $weight;
        }

        if ($totalWeight == 0) {
            return 0;
        }

        return round(($weightedSum / $totalWeight), 2);
    }

    /**
     * Calculate average for a specific component group (e.g., all Quizzes)
     */
    public static function calculateComponentGroupAverage($studentId, $classId, $term, $subcategory)
    {
        $entries = ComponentEntry::whereHas('component', function ($query) use ($subcategory) {
                $query->where('subcategory', $subcategory);
            })
            ->where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->get();

        if ($entries->isEmpty()) {
            return 0;
        }

        $sum = 0;
        foreach ($entries as $entry) {
            $sum += $entry->normalized_score ?? 0;
        }

        return round($sum / count($entries), 2);
    }

    /**
     * Get subcategory breakdown (e.g., individual quiz scores for a student)
     */
    public static function getSubcategoryBreakdown($studentId, $classId, $term, $subcategory)
    {
        $entries = ComponentEntry::whereHas('component', function ($query) use ($subcategory) {
                $query->where('subcategory', $subcategory);
            })
            ->where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->with('component')
            ->orderBy('component_id')
            ->get();

        return $entries->map(function ($entry) {
            return [
                'component_name' => $entry->component->name,
                'raw_score' => $entry->raw_score,
                'normalized_score' => $entry->normalized_score,
                'max_score' => $entry->component->max_score,
                'weight' => $entry->component->weight,
            ];
        });
    }

    /**
     * Check if student has any entries for a term
     */
    public static function hasEntries($studentId, $classId, $term)
    {
        return ComponentEntry::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->exists();
    }

    /**
     * Get entry count by category for a student
     */
    public static function getEntryCounts($studentId, $classId, $term)
    {
        $entries = ComponentEntry::whereHas('component')
            ->where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->with('component:id,category')
            ->get()
            ->groupBy('component.category')
            ->map->count();

        return [
            'knowledge' => $entries->get('Knowledge', 0),
            'skills' => $entries->get('Skills', 0),
            'attitude' => $entries->get('Attitude', 0),
        ];
    }

    /**
     * Get detailed grade report
     */
    public static function getDetailedReport($studentId, $classId, $term)
    {
        $entries = ComponentEntry::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->with('component')
            ->get();

        $report = [
            'student_id' => $studentId,
            'class_id' => $classId,
            'term' => $term,
            'categories' => [],
            'averages' => self::calculateCategoryAverages($studentId, $classId, $term),
        ];

        // Group by category and build report
        $byCategory = $entries->groupBy('component.category');

        foreach (['Knowledge', 'Skills', 'Attitude'] as $category) {
            $categoryEntries = $byCategory->get($category, collect());
            
            $subcategoryGroups = $categoryEntries->groupBy('component.subcategory');
            $subcategories = [];

            foreach ($subcategoryGroups as $subcategory => $group) {
                $scores = $group->pluck('normalized_score')->toArray();
                $subcategories[] = [
                    'name' => $subcategory,
                    'count' => $group->count(),
                    'scores' => $scores,
                    'average' => count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0,
                ];
            }

            $report['categories'][$category] = $subcategories;
        }

        return $report;
    }

    /**
     * Check if all required components have entries
     */
    public static function validateCompletion($studentId, $classId, $term)
    {
        $errors = [];

        // Get all active components for the class
        $components = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->get()
            ->groupBy('category');

        // Check if each category has at least one entry
        foreach ($components as $category => $cats) {
            $entries = ComponentEntry::whereHas('component', function ($query) use ($category) {
                    $query->where('category', $category);
                })
                ->where('student_id', $studentId)
                ->where('class_id', $classId)
                ->where('term', $term)
                ->count();

            if ($entries == 0) {
                $errors[] = "No entries for $category component";
            }
        }

        return [
            'is_complete' => empty($errors),
            'errors' => $errors,
        ];
    }
}
