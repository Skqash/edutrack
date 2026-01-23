<?php

namespace App\Helpers;

/**
 * GradeValidationHelper - Input validation utilities for grading system
 */
class GradeValidationHelper
{
    /**
     * Validate a single grade score
     * @param mixed $score The score to validate
     * @param float $minScore Minimum allowed score (default 0)
     * @param float $maxScore Maximum allowed score (default 100)
     * @return array [valid => bool, error => string]
     */
    public static function validateScore($score, $minScore = 0, $maxScore = 100)
    {
        if ($score === null || $score === '') {
            return ['valid' => true, 'error' => null]; // Allow empty for optional fields
        }

        if (!is_numeric($score)) {
            return ['valid' => false, 'error' => 'Score must be numeric'];
        }

        $numericScore = floatval($score);

        if ($numericScore < $minScore) {
            return ['valid' => false, 'error' => "Score must be at least {$minScore}"];
        }

        if ($numericScore > $maxScore) {
            return ['valid' => false, 'error' => "Score must not exceed {$maxScore}"];
        }

        return ['valid' => true, 'error' => null];
    }

    /**
     * Validate all grades for a student
     * @param array $gradeData Grade data array
     * @return array Validation result [valid => bool, errors => array]
     */
    public static function validateStudentGrades($gradeData)
    {
        $errors = [];

        // Quiz validation
        for ($i = 1; $i <= 5; $i++) {
            $key = "q{$i}";
            if (isset($gradeData[$key])) {
                $validation = self::validateScore($gradeData[$key], 0, 100);
                if (!$validation['valid']) {
                    $errors[$key] = $validation['error'];
                }
            }
        }

        // Exam validation
        foreach (['midterm_exam', 'final_exam'] as $exam) {
            if (isset($gradeData[$exam])) {
                $validation = self::validateScore($gradeData[$exam], 0, 100);
                if (!$validation['valid']) {
                    $errors[$exam] = $validation['error'];
                }
            }
        }

        // Skills validation
        foreach (['output_score', 'class_participation_score', 'activities_score', 'assignments_score'] as $skill) {
            if (isset($gradeData[$skill])) {
                $validation = self::validateScore($gradeData[$skill], 0, 100);
                if (!$validation['valid']) {
                    $errors[$skill] = $validation['error'];
                }
            }
        }

        // Attitude validation
        foreach (['behavior_score', 'awareness_score'] as $attitude) {
            if (isset($gradeData[$attitude])) {
                $validation = self::validateScore($gradeData[$attitude], 0, 100);
                if (!$validation['valid']) {
                    $errors[$attitude] = $validation['error'];
                }
            }
        }

        // Attendance validation
        if (isset($gradeData['attendance_score'])) {
            $validation = self::validateScore($gradeData['attendance_score'], 0, 100);
            if (!$validation['valid']) {
                $errors['attendance_score'] = $validation['error'];
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Sanitize grade input
     * @param mixed $score Score to sanitize
     * @return float|null Sanitized score or null if invalid
     */
    public static function sanitizeScore($score)
    {
        if ($score === null || $score === '') {
            return null;
        }

        $sanitized = floatval($score);
        
        // Clamp to 0-100 range
        return max(0, min(100, $sanitized));
    }

    /**
     * Validate attendance data
     * @param int $presentClasses Number of classes attended
     * @param int $totalClasses Total classes held
     * @return array Validation result
     */
    public static function validateAttendance($presentClasses, $totalClasses)
    {
        $errors = [];

        if (!is_numeric($presentClasses) || $presentClasses < 0) {
            $errors['present_classes'] = 'Present classes must be a non-negative number';
        }

        if (!is_numeric($totalClasses) || $totalClasses <= 0) {
            $errors['total_classes'] = 'Total classes must be a positive number';
        }

        if (!empty($errors)) {
            return ['valid' => false, 'errors' => $errors];
        }

        if ($presentClasses > $totalClasses) {
            $errors['present_classes'] = 'Present classes cannot exceed total classes';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Get validation error message for display
     * @param array $errors Errors array from validation
     * @return string HTML error message
     */
    public static function getErrorHTML($errors)
    {
        if (empty($errors)) {
            return '';
        }

        $html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        $html .= '<strong>Validation Errors:</strong><ul class="mb-0">';

        foreach ($errors as $field => $error) {
            $html .= '<li>' . htmlspecialchars($error) . '</li>';
        }

        $html .= '</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';

        return $html;
    }
}
