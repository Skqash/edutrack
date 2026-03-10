<?php

namespace App\Helpers;

use App\Models\Grade;

/**
 * GradeHelper - Comprehensive grading system utilities
 * Supports CHED Philippines grading standards
 */
class GradeHelper
{
    /**
     * Format grade as HTML badge (using CHED grade points instead of letters)
     * @param float $score Numeric score (0-100)
     * @return string HTML badge string
     */
    public static function formatGradeBadge($score)
    {
        $gradePoint = Grade::getGradePoint($score);
        $color = Grade::getGradeColor($score);
        $displayScore = round($score, 2);
        
        $colorMap = [
            'success' => '#d4edda',
            'info' => '#cfe2ff',
            'warning' => '#fff3cd',
            'secondary' => '#e2e3e5',
            'danger' => '#f8d7da',
        ];
        
        $bgColor = $colorMap[$color] ?? '#f8f9fa';
        $textColor = self::getContrastColor($bgColor);
        
        return sprintf(
            '<span class="badge bg-%s" style="background-color: %s !important; color: %s;">%.2f (%.1f)</span>',
            $color,
            $bgColor,
            $textColor,
            $gradePoint,
            $displayScore
        );
    }

    /**
     * Get appropriate text color for background
     * @param string $bgColor Hex background color
     * @return string Text color (white or black)
     */
    private static function getContrastColor($bgColor)
    {
        // Convert hex to RGB
        $hex = str_replace('#', '', $bgColor);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Get grade performance description
     * @param float $score Numeric score
     * @return string Performance description
     */
    public static function getPerformanceDescription($score)
    {
        if ($score >= 90) {
            return 'Excellent - Outstanding performance';
        } elseif ($score >= 80) {
            return 'Very Good - Strong performance';
        } elseif ($score >= 70) {
            return 'Good - Satisfactory performance';
        } elseif ($score >= 60) {
            return 'Fair - Passing performance';
        } else {
            return 'Poor - Below passing standard';
        }
    }

    /**
     * Calculate quartile for grade distribution analysis
     * @param array $grades Array of scores
     * @return array Quartile values [Q1, Q2/median, Q3]
     */
    public static function calculateQuartiles($grades)
    {
        if (empty($grades)) {
            return [0, 0, 0];
        }

        sort($grades);
        $count = count($grades);
        $mid = floor($count / 2);

        // Calculate Q2 (median)
        if ($count % 2 == 0) {
            $Q2 = ($grades[$mid - 1] + $grades[$mid]) / 2;
        } else {
            $Q2 = $grades[$mid];
        }

        // Calculate Q1
        if ($mid % 2 == 0) {
            $Q1 = ($grades[floor($mid / 2) - 1] + $grades[floor($mid / 2)]) / 2;
        } else {
            $Q1 = $grades[floor($mid / 2)];
        }

        // Calculate Q3
        $upper = array_slice($grades, ceil($count / 2));
        $upperMid = floor(count($upper) / 2);
        if (count($upper) % 2 == 0) {
            $Q3 = ($upper[$upperMid - 1] + $upper[$upperMid]) / 2;
        } else {
            $Q3 = $upper[$upperMid];
        }

        return [round($Q1, 2), round($Q2, 2), round($Q3, 2)];
    }

    /**
     * Identify outliers in grade distribution
     * @param array $grades Array of scores
     * @return array Indices of outlier values
     */
    public static function findOutliers($grades)
    {
        if (count($grades) < 4) {
            return [];
        }

        $quartiles = self::calculateQuartiles($grades);
        $Q1 = $quartiles[0];
        $Q3 = $quartiles[2];
        $IQR = $Q3 - $Q1;
        $lowerBound = $Q1 - (1.5 * $IQR);
        $upperBound = $Q3 + (1.5 * $IQR);

        $outliers = [];
        foreach ($grades as $index => $value) {
            if ($value < $lowerBound || $value > $upperBound) {
                $outliers[] = $index;
            }
        }

        return $outliers;
    }

    /**
     * Generate grade distribution for chart display
     * @param array $grades Array of Grade models
     * @return array Distribution data [label => count]
     */
    public static function getGradeDistribution($grades)
    {
        $distribution = [
            'A (90-100)' => 0,
            'B (80-89)' => 0,
            'C (70-79)' => 0,
            'D (60-69)' => 0,
            'F (0-59)' => 0,
        ];

        foreach ($grades as $grade) {
            $score = $grade->final_grade ?? 0;
            if ($score >= 90) {
                $distribution['A (90-100)']++;
            } elseif ($score >= 80) {
                $distribution['B (80-89)']++;
            } elseif ($score >= 70) {
                $distribution['C (70-79)']++;
            } elseif ($score >= 60) {
                $distribution['D (60-69)']++;
            } else {
                $distribution['F (0-59)']++;
            }
        }

        return $distribution;
    }

    /**
     * Generate improvement recommendations based on grade analysis
     * @param float $classAverage Average grade
     * @param float $passingRate Pass percentage
     * @return array Recommendations
     */
    public static function generateRecommendations($classAverage, $passingRate)
    {
        $recommendations = [];

        if ($classAverage < 70) {
            $recommendations[] = "⚠️ Class average is below 70. Consider reviewing teaching methods or providing additional support.";
        }

        if ($passingRate < 80) {
            $recommendations[] = "📚 More than 20% of students are failing. Implement intervention programs or tutoring sessions.";
        }

        if ($classAverage >= 85 && $passingRate >= 95) {
            $recommendations[] = "✨ Excellent class performance! Consider advanced/enrichment activities for high achievers.";
        }

        if ($classAverage >= 70 && $classAverage < 80) {
            $recommendations[] = "✓ Satisfactory performance. Focus on helping D-grade students improve to C range.";
        }

        return $recommendations;
    }

    /**
     * Format KSA component breakdown
     * @param float $knowledge Knowledge score
     * @param float $skills Skills score
     * @param float $attitude Attitude score
     * @return array Formatted breakdown
     */
    public static function formatKSABreakdown($knowledge, $skills, $attitude)
    {
        return [
            'Knowledge' => [
                'score' => round($knowledge, 2),
                'weight' => 40,
                'contribution' => round(($knowledge * 0.40), 2),
            ],
            'Skills' => [
                'score' => round($skills, 2),
                'weight' => 50,
                'contribution' => round(($skills * 0.50), 2),
            ],
            'Attitude' => [
                'score' => round($attitude, 2),
                'weight' => 10,
                'contribution' => round(($attitude * 0.10), 2),
            ],
        ];
    }

    /**
     * Get trend indicator for grade comparison
     * @param float $current Current grade
     * @param float $previous Previous grade
     * @return array Trend data [icon, text, direction]
     */
    public static function getTrendIndicator($current, $previous)
    {
        if ($previous == 0) {
            return ['→', 'No previous grade', 'neutral'];
        }

        $diff = $current - $previous;

        if ($diff > 5) {
            return ['📈', 'Significant improvement', 'up'];
        } elseif ($diff > 0) {
            return ['↗', 'Slight improvement', 'up'];
        } elseif ($diff < -5) {
            return ['📉', 'Significant decline', 'down'];
        } elseif ($diff < 0) {
            return ['↘', 'Slight decline', 'down'];
        } else {
            return ['→', 'No change', 'neutral'];
        }
    }

    /**
     * Format NEW grading system component breakdown
     * @param $grade Grade model instance
     * @return array Formatted breakdown for display
     */
    public static function formatNewGradingBreakdown($grade)
    {
        return [
            'knowledge' => [
                'average' => round($grade->knowledge_average ?? 0, 2),
                'weight' => 40,
                'contribution' => round(($grade->knowledge_average ?? 0) * 0.40, 2),
            ],
            'skills' => [
                'average' => round($grade->skills_average ?? 0, 2),
                'weight' => 50,
                'contribution' => round(($grade->skills_average ?? 0) * 0.50, 2),
            ],
            'attitude' => [
                'average' => round($grade->attitude_average ?? 0, 2),
                'weight' => 10,
                'contribution' => round(($grade->attitude_average ?? 0) * 0.10, 2),
            ],
            'grades' => [
                'midterm' => round($grade->midterm_grade ?? 0, 2),
                'final' => round($grade->final_grade_value ?? 0, 2),
                'overall' => round($grade->overall_grade ?? 0, 2),
            ],
        ];
    }

    /**
     * Get grade status badge for NEW system
     * @param float $score Overall grade score
     * @return array Badge properties [color, label, icon]
     */
    public static function getGradeStatusBadgeNew($score)
    {
        $score = floatval($score);

        if ($score >= 98.0) {
            return ['success', 'A+', '⭐⭐⭐'];
        } elseif ($score >= 95.0) {
            return ['success', 'A', '⭐⭐'];
        } elseif ($score >= 92.0) {
            return ['success', 'A-', '⭐'];
        } elseif ($score >= 89.0) {
            return ['info', 'B+', '✓✓✓'];
        } elseif ($score >= 86.0) {
            return ['info', 'B', '✓✓'];
        } elseif ($score >= 83.0) {
            return ['info', 'B-', '✓'];
        } elseif ($score >= 80.0) {
            return ['warning', 'C+', '⚠'];
        } elseif ($score >= 77.0) {
            return ['warning', 'C', '⚠⚠'];
        } elseif ($score >= 74.0) {
            return ['warning', 'C-', '⚠⚠⚠'];
        } elseif ($score >= 71.0) {
            return ['secondary', 'D+', '→'];
        } elseif ($score >= 70.0) {
            return ['secondary', 'D', '→→'];
        } elseif ($score >= 60.0) {
            return ['secondary', 'D-', '→→→'];
        } else {
            return ['danger', 'F', '✗'];
        }
    }

    /**
     * Validate NEW grading system data
     * @param array $gradeData Grade data to validate
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validateNewGradingData($gradeData)
    {
        $errors = [];

        // Check exams
        $exams = [
            'exam_prelim' => $gradeData['exam_prelim'] ?? null,
            'exam_midterm' => $gradeData['exam_midterm'] ?? null,
            'exam_final' => $gradeData['exam_final'] ?? null,
        ];

        foreach ($exams as $name => $value) {
            if ($value !== null && ($value < 0 || $value > 100)) {
                $errors[] = "{$name} must be between 0 and 100";
            }
        }

        // Check quizzes
        for ($i = 1; $i <= 5; $i++) {
            $value = $gradeData["quiz_{$i}"] ?? null;
            if ($value !== null && ($value < 0 || $value > 100)) {
                $errors[] = "quiz_{$i} must be between 0 and 100";
            }
        }

        // Check skill components
        $skillComponents = ['output', 'class_participation', 'activities', 'assignments'];
        foreach ($skillComponents as $component) {
            for ($i = 1; $i <= 3; $i++) {
                $value = $gradeData["{$component}_{$i}"] ?? null;
                if ($value !== null && ($value < 0 || $value > 100)) {
                    $errors[] = "{$component}_{$i} must be between 0 and 100";
                }
            }
        }

        // Check attitude components
        $attitudeComponents = ['behavior', 'awareness'];
        foreach ($attitudeComponents as $component) {
            for ($i = 1; $i <= 3; $i++) {
                $value = $gradeData["{$component}_{$i}"] ?? null;
                if ($value !== null && ($value < 0 || $value > 100)) {
                    $errors[] = "{$component}_{$i} must be between 0 and 100";
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Generate NEW grading system report for a class
     * @param $classId Class ID
     * @param $teacherId Teacher ID
     * @return array Report data
     */
    public static function generateNewGradingReport($classId, $teacherId)
    {
        $grades = Grade::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('student')
            ->get();

        if ($grades->isEmpty()) {
            return [
                'total_students' => 0,
                'grades_entered' => 0,
                'completion_percentage' => 0,
                'statistics' => [],
            ];
        }

        // Calculate statistics
        $knowledgeScores = $grades->pluck('knowledge_average')->filter()->toArray();
        $skillsScores = $grades->pluck('skills_average')->filter()->toArray();
        $attitudeScores = $grades->pluck('attitude_average')->filter()->toArray();
        $overallScores = $grades->pluck('overall_grade')->filter()->toArray();

        return [
            'total_students' => $grades->count(),
            'grades_entered' => count($overallScores),
            'completion_percentage' => count($overallScores) > 0 ? (count($overallScores) / $grades->count() * 100) : 0,
            'statistics' => [
                'knowledge' => [
                    'average' => !empty($knowledgeScores) ? array_sum($knowledgeScores) / count($knowledgeScores) : 0,
                    'highest' => !empty($knowledgeScores) ? max($knowledgeScores) : 0,
                    'lowest' => !empty($knowledgeScores) ? min($knowledgeScores) : 0,
                ],
                'skills' => [
                    'average' => !empty($skillsScores) ? array_sum($skillsScores) / count($skillsScores) : 0,
                    'highest' => !empty($skillsScores) ? max($skillsScores) : 0,
                    'lowest' => !empty($skillsScores) ? min($skillsScores) : 0,
                ],
                'attitude' => [
                    'average' => !empty($attitudeScores) ? array_sum($attitudeScores) / count($attitudeScores) : 0,
                    'highest' => !empty($attitudeScores) ? max($attitudeScores) : 0,
                    'lowest' => !empty($attitudeScores) ? min($attitudeScores) : 0,
                ],
                'overall' => [
                    'average' => !empty($overallScores) ? array_sum($overallScores) / count($overallScores) : 0,
                    'highest' => !empty($overallScores) ? max($overallScores) : 0,
                    'lowest' => !empty($overallScores) ? min($overallScores) : 0,
                ],
            ],
        ];
    }

    /**
     * ============================================================
     * CALCULATE TERM GRADES WITH DECIMAL SCALE (1.0-5.0)
     * ============================================================
     * 
     * Calculate midterm and final term grades with pass/fail status
     * Decimal Scale: 1.0 (Excellent) to 5.0 (Failed)
     * Pass: Grade <= 3.0
     * Fail: Grade > 3.0
     * 
     * @param float $knowledge Knowledge average (0-100)
     * @param float $skills Skills average (0-100)
     * @param float $attitude Attitude average (0-100)
     * @return array [
     *      'term_grade' => float (0-100),
     *      'decimal_grade' => float (1.0-5.0),
     *      'status' => string ('Passed'|'Failed'),
     *      'remarks' => string (Performance description),
     *      'grade_label' => string (Letter grade representation)
     * ]
     */
    public static function calculateTermGradeWithDecimal($knowledge, $skills, $attitude)
    {
        // Calculate term grade using CHED formula
        // Knowledge: 40%, Skills: 50%, Attitude: 10%
        $knowledge = floatval($knowledge ?? 0);
        $skills = floatval($skills ?? 0);
        $attitude = floatval($attitude ?? 0);

        $termGrade = ($knowledge * 0.40) + ($skills * 0.50) + ($attitude * 0.10);
        $termGrade = round(max(0, min(100, $termGrade)), 2);

        // Convert to decimal scale (1.0-5.0)
        $decimalGrade = self::convertToDecimalScale($termGrade);

        // Determine pass/fail status
        $status = $decimalGrade <= 3.0 ? 'Passed' : 'Failed';

        // Get performance remarks
        $remarks = self::getPerformanceRemarks($termGrade);

        // Get grade label
        $gradeLabel = self::getGradeLabel($decimalGrade);

        return [
            'term_grade' => $termGrade,
            'decimal_grade' => $decimalGrade,
            'status' => $status,
            'remarks' => $remarks,
            'grade_label' => $gradeLabel,
        ];
    }

    /**
     * Calculate midterm grade with all components
     * 
     * @param float $midtermKnowledge Midterm knowledge average
     * @param float $midtermSkills Midterm skills average
     * @param float $midtermAttitude Midterm attitude average
     * @return array Complete midterm grade data with decimal scale
     */
    public static function calculateMidtermGrade($midtermKnowledge, $midtermSkills, $midtermAttitude)
    {
        return array_merge(
            self::calculateTermGradeWithDecimal($midtermKnowledge, $midtermSkills, $midtermAttitude),
            [
                'period' => 'Midterm',
                'weight' => 0.40, // Midterm is 40% of overall grade
            ]
        );
    }

    /**
     * Calculate final term grade with all components
     * 
     * @param float $finalKnowledge Final knowledge average
     * @param float $finalSkills Final skills average
     * @param float $finalAttitude Final attitude average
     * @return array Complete final grade data with decimal scale
     */
    public static function calculateFinalGrade($finalKnowledge, $finalSkills, $finalAttitude)
    {
        return array_merge(
            self::calculateTermGradeWithDecimal($finalKnowledge, $finalSkills, $finalAttitude),
            [
                'period' => 'Final',
                'weight' => 0.60, // Final is 60% of overall grade
            ]
        );
    }

    /**
     * Calculate overall grade from midterm and final
     * Overall = (Midterm * 0.40) + (Final * 0.60)
     * 
     * @param array $midtermData Midterm grade data from calculateMidtermGrade()
     * @param array $finalData Final grade data from calculateFinalGrade()
     * @return array Overall grade data with decimal scale and pass/fail
     */
    public static function calculateOverallTermGrade($midtermData, $finalData)
    {
        // Calculate weighted overall grade (0-100)
        $overallTermGrade = ($midtermData['term_grade'] * 0.40) + ($finalData['term_grade'] * 0.60);
        $overallTermGrade = round($overallTermGrade, 2);

        // Convert to decimal scale
        $overallDecimalGrade = self::convertToDecimalScale($overallTermGrade);

        // Determine pass/fail
        $status = $overallDecimalGrade <= 3.0 ? 'Passed' : 'Failed';

        // Get remarks
        $remarks = self::getPerformanceRemarks($overallTermGrade);

        // Get grade label
        $gradeLabel = self::getGradeLabel($overallDecimalGrade);

        return [
            'period' => 'Overall',
            'term_grade' => $overallTermGrade,
            'decimal_grade' => $overallDecimalGrade,
            'status' => $status,
            'remarks' => $remarks,
            'grade_label' => $gradeLabel,
            'midterm_contribution' => round($midtermData['term_grade'] * 0.40, 2),
            'final_contribution' => round($finalData['term_grade'] * 0.60, 2),
        ];
    }

    /**
     * Convert 0-100 grade to 1.0-5.0 decimal scale
     * 1.0 = Excellent (98-100)
     * 5.0 = Failed (Below 70)
     * 
     * @param float $score Numeric score (0-100)
     * @return float Decimal grade (1.0-5.0)
     */
    public static function convertToDecimalScale($score)
    {
        $score = floatval($score ?? 0);

        // Grading Scale Mapping (1.0 to 5.0)
        // 1.0 is highest/excellent, 5.0 is failed
        if ($score >= 98.0) return 1.00;
        elseif ($score >= 95.0) return 1.25;
        elseif ($score >= 92.0) return 1.50;
        elseif ($score >= 89.0) return 1.75;
        elseif ($score >= 86.0) return 2.00;
        elseif ($score >= 83.0) return 2.25;
        elseif ($score >= 80.0) return 2.50;
        elseif ($score >= 77.0) return 2.75;
        elseif ($score >= 74.0) return 3.00;
        elseif ($score >= 71.0) return 3.25;
        elseif ($score >= 70.0) return 3.50;
        else return 5.00; // Failed
    }

    /**
     * Get performance remarks based on term grade
     * 
     * @param float $score Numeric score (0-100)
     * @return string Performance description
     */
    public static function getPerformanceRemarks($score)
    {
        $score = floatval($score ?? 0);

        if ($score >= 98) {
            return '⭐⭐⭐ Excellent - Exceptional performance';
        } elseif ($score >= 95) {
            return '⭐⭐ Excellent - Outstanding performance';
        } elseif ($score >= 92) {
            return '⭐ Excellent - Strong performance';
        } elseif ($score >= 89) {
            return 'Very Good - Impressive performance';
        } elseif ($score >= 86) {
            return 'Very Good - Strong performance';
        } elseif ($score >= 83) {
            return 'Good - Solid performance';
        } elseif ($score >= 80) {
            return 'Good - Satisfactory performance';
        } elseif ($score >= 77) {
            return 'Satisfactory - Meets standards';
        } elseif ($score >= 74) {
            return 'Satisfactory - Acceptable performance';
        } elseif ($score >= 71) {
            return 'Fair - Needs improvement';
        } elseif ($score >= 70) {
            return 'Fair - Barely passing';
        } else {
            return '✗ Failed - Below passing standard';
        }
    }

    /**
     * Get grade label for decimal scale
     * 
     * @param float $decimalGrade Decimal grade (1.0-5.0)
     * @return string Grade label
     */
    public static function getGradeLabel($decimalGrade)
    {
        $decimalGrade = floatval($decimalGrade ?? 0);

        if ($decimalGrade <= 1.00) return 'A+ (Excellent)';
        elseif ($decimalGrade <= 1.25) return 'A (Excellent)';
        elseif ($decimalGrade <= 1.50) return 'A- (Excellent)';
        elseif ($decimalGrade <= 1.75) return 'B+ (Very Good)';
        elseif ($decimalGrade <= 2.00) return 'B (Very Good)';
        elseif ($decimalGrade <= 2.25) return 'B- (Very Good)';
        elseif ($decimalGrade <= 2.50) return 'C+ (Good)';
        elseif ($decimalGrade <= 2.75) return 'C (Good)';
        elseif ($decimalGrade <= 3.00) return 'C- (Satisfactory)';
        elseif ($decimalGrade <= 3.25) return 'D+ (Fair)';
        elseif ($decimalGrade <= 3.50) return 'D (Fair)';
        elseif ($decimalGrade <= 5.00) return 'F (Failed)';
        else return 'INC (Incomplete)';
    }

    /**
     * Extract letter grade from full grade label
     * 
     * @param string $gradeLabel Full label like "F (Failed)" or "A+ (Excellent)"
     * @return string Just the letter grade like "F" or "A+"
     */
    public static function extractLetterGrade($gradeLabel)
    {
        // Extract the part before " (" - this gives us the letter grade only
        if (strpos($gradeLabel, ' (') !== false) {
            return explode(' (', $gradeLabel)[0];
        }
        return $gradeLabel; // Fallback in case format is different
    }

    /**
     * Get complete student grade summary
     * 
     * @param float $midtermKnowledge Midterm knowledge score
     * @param float $midtermSkills Midterm skills score
     * @param float $midtermAttitude Midterm attitude score
     * @param float $finalKnowledge Final knowledge score
     * @param float $finalSkills Final skills score
     * @param float $finalAttitude Final attitude score
     * @return array Complete grade summary with all calculations
     */
    public static function getCompleteGradeSummary(
        $midtermKnowledge, $midtermSkills, $midtermAttitude,
        $finalKnowledge, $finalSkills, $finalAttitude
    ) {
        // Calculate midterm grade
        $midtermGrade = self::calculateMidtermGrade($midtermKnowledge, $midtermSkills, $midtermAttitude);

        // Calculate final grade
        $finalGrade = self::calculateFinalGrade($finalKnowledge, $finalSkills, $finalAttitude);

        // Calculate overall grade
        $overallGrade = self::calculateOverallTermGrade($midtermGrade, $finalGrade);

        return [
            'midterm' => $midtermGrade,
            'final' => $finalGrade,
            'overall' => $overallGrade,
            'summary' => [
                'student_status' => $overallGrade['status'],
                'final_grade_decimal' => $overallGrade['decimal_grade'],
                'final_grade_numeric' => $overallGrade['term_grade'],
                'grade_label' => $overallGrade['grade_label'],
                'remarks' => $overallGrade['remarks'],
            ],
        ];
    }
}
