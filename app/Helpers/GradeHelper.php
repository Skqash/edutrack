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
}
