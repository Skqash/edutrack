<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Subject;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'class_id',
        'teacher_id',
        'term',
        // Knowledge Components - Quizzes (flexible 1-10)
        'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10',
        'midterm_exam', 'final_exam',
        'knowledge_score',
        // Skills Components
        'output_score',
        'class_participation_score',
        'activities_score',
        'assignments_score',
        'skills_score',
        // Attitude Components
        'behavior_score',
        'awareness_score',
        'attitude_score',
        // Final Grade
        'final_grade',
        'grade_point',
        'remarks',
        'grading_period',
    ];

    protected $casts = [
        'q1' => 'decimal:2',
        'q2' => 'decimal:2',
        'q3' => 'decimal:2',
        'q4' => 'decimal:2',
        'q5' => 'decimal:2',
        'q6' => 'decimal:2',
        'q7' => 'decimal:2',
        'q8' => 'decimal:2',
        'q9' => 'decimal:2',
        'q10' => 'decimal:2',
        'midterm_exam' => 'decimal:2',
        'final_exam' => 'decimal:2',
        'knowledge_score' => 'decimal:2',
        'output_score' => 'decimal:2',
        'class_participation_score' => 'decimal:2',
        'activities_score' => 'decimal:2',
        'assignments_score' => 'decimal:2',
        'skills_score' => 'decimal:2',
        'behavior_score' => 'decimal:2',
        'awareness_score' => 'decimal:2',
        'attitude_score' => 'decimal:2',
        'final_grade' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * CHED PHILIPPINES GRADING SYSTEM
     * Knowledge: 40% of Term
     * Skills: 50% of Term
     * Attitude: 10% of Term
     */

    /**
     * Calculate Knowledge Score (40% of term grade)
     * 
     * DETAILED BREAKDOWN:
     * - Knowledge = 40% of total grade
     * - Within Knowledge:
     *   - Quizzes: 40% of Knowledge = 16% of total grade
     *     - 5 quizzes equally distributed, each = 3.2% of total grade
     *   - Exam: 60% of Knowledge = 24% of total grade
     *
     * @param array $quizzes [q1, q2, q3, q4, q5, ...] - raw scores
     * @param array $exams [prelim, midterm, final] - raw scores
     * @param AssessmentRange|null $range Assessment range config, if null uses defaults
     * @param string $term 'midterm' or 'final'
     * @return float Knowledge score (0-100)
     */
    public static function calculateKnowledge($quizzes, $exams, $range = null, $term = 'midterm')
    {
        // If no range provided, use default calculations (backward compatible)
        if ($range === null) {
            return self::calculateKnowledgeDefault($quizzes, $exams, $term);
        }

        // Get quiz max scores (handles both equal distribution and custom)
        $quizMaxScores = $range->getQuizMaxScores();
        
        // Normalize quiz scores based on configured max values
        // Each quiz: normalize to 0-100 scale, then average
        $quizzes = array_filter(array_map(function($q) { return floatval($q ?? 0); }, $quizzes));
        $normalizedQuizzes = [];
        
        $quizIndex = 1;
        foreach ($quizzes as $score) {
            $quizKey = 'q' . $quizIndex;
            $maxScore = $quizMaxScores[$quizKey] ?? 20;
            $normalizedScore = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
            $normalizedQuizzes[] = $normalizedScore;
            $quizIndex++;
        }
        
        // Calculate quiz average (40% of knowledge = 16% of total)
        $quizAverage = count($normalizedQuizzes) > 0 ? array_sum($normalizedQuizzes) / count($normalizedQuizzes) : 0;
        $quizPart = $quizAverage * 0.40; // 40% of knowledge score

        // Normalize exam scores and calculate average (60% of knowledge = 24% of total)
        // We only have 2 exams: Midterm and Final
        $midterm = floatval($exams['midterm'] ?? 0);
        $final = floatval($exams['final'] ?? 0);
        $normMidterm = $range->normalizeExamScore($midterm, 'midterm');
        $normFinal = $range->normalizeExamScore($final, 'final');
        $examAverage = ($normMidterm + $normFinal) / 2;
        $examPart = $examAverage * 0.60; // 60% of knowledge score

        $knowledge = $quizPart + $examPart;
        return round($knowledge, 2);
    }

    /**
     * Default knowledge calculation (backward compatible)
     */
    private static function calculateKnowledgeDefault($quizzes, $exams, $term = 'midterm')
    {
        $quizzes = array_filter(array_map(function($q) { return floatval($q ?? 0); }, $quizzes));
        $quizTotal = array_sum($quizzes);
        $quizAverage = count($quizzes) > 0 ? ($quizTotal / (5 * count($quizzes))) * 100 : 0;
        $quizPart = $quizAverage * 0.40;

        // Only use Midterm and Final exams
        $midterm = floatval($exams['midterm'] ?? 0);
        $final = floatval($exams['final'] ?? 0);
        $examAverage = ($midterm + $final) / 2;
        $examPart = $examAverage * 0.60;

        return round($quizPart + $examPart, 2);
    }

    /**
     * Calculate Skills Score (50% of term grade)
     * 
     * DETAILED BREAKDOWN:
     * - Skills = 50% of total grade
     * - Within Skills (averaged across 3 periods: Prelim, Midterm, Final):
     *   - Output: 40% of Skills = 20% of total grade
     *     - 3 inputs (prelim, midterm, final), each = 6.67% of total grade
     *   - Class Participation: 30% of Skills = 15% of total grade
     *     - 3 inputs (prelim, midterm, final), each = 5% of total grade
     *   - Activities: 15% of Skills = 7.5% of total grade
     *     - 3 inputs (prelim, midterm, final), each = 2.5% of total grade
     *   - Assignments: 15% of Skills = 7.5% of total grade
     *     - 3 inputs (prelim, midterm, final), each = 2.5% of total grade
     *
     * @param array $output Output scores [prelim, midterm, final] - raw scores
     * @param array $classParticipation Class participation scores [prelim, midterm, final] - raw scores
     * @param array $activities Activities scores [prelim, midterm, final] - raw scores
     * @param array $assignments Assignments scores [prelim, midterm, final] - raw scores
     * @param AssessmentRange|null $range Assessment range config
     * @return float Skills score (0-100)
     */
    public static function calculateSkills(array|float $output, array|float $classParticipation, array|float $activities, array|float $assignments, $range = null)
    {
        if ($range === null) {
            // Default calculation (backward compatible)
            // If inputs are arrays, average them first
            if (is_array($output)) {
                $output = array_filter(array_map(function($o) { return floatval($o ?? 0); }, $output));
                $output = count($output) > 0 ? array_sum($output) / count($output) : 0;
            } else {
                $output = max(0, min(100, floatval($output ?? 0)));
            }

            if (is_array($classParticipation)) {
                $classParticipation = array_filter(array_map(function($cp) { return floatval($cp ?? 0); }, $classParticipation));
                $classParticipation = count($classParticipation) > 0 ? array_sum($classParticipation) / count($classParticipation) : 0;
            } else {
                $classParticipation = max(0, min(100, floatval($classParticipation ?? 0)));
            }

            if (is_array($activities)) {
                $activities = array_filter(array_map(function($a) { return floatval($a ?? 0); }, $activities));
                $activities = count($activities) > 0 ? array_sum($activities) / count($activities) : 0;
            } else {
                $activities = max(0, min(100, floatval($activities ?? 0)));
            }

            if (is_array($assignments)) {
                $assignments = array_filter(array_map(function($a) { return floatval($a ?? 0); }, $assignments));
                $assignments = count($assignments) > 0 ? array_sum($assignments) / count($assignments) : 0;
            } else {
                $assignments = max(0, min(100, floatval($assignments ?? 0)));
            }

            $skills = ($output * 0.40) + 
                      ($classParticipation * 0.30) + 
                      ($activities * 0.15) + 
                      ($assignments * 0.15);

            return round($skills, 2);
        }

        // With range: normalize each period's inputs and average
        // Output: average of 3 periods
        $outputPeriods = is_array($output) ? $output : [$output];
        $outputPeriods = array_filter(array_map(function($o) { return floatval($o ?? 0); }, $outputPeriods));
        $normOutputs = [];
        foreach ($outputPeriods as $score) {
            $normOutputs[] = $range->normalizeSkillScore($score, 'output');
        }
        $normOutput = count($normOutputs) > 0 ? array_sum($normOutputs) / count($normOutputs) : 0;

        // Class Participation: average of 3 periods
        $cpPeriods = is_array($classParticipation) ? $classParticipation : [$classParticipation];
        $cpPeriods = array_filter(array_map(function($cp) { return floatval($cp ?? 0); }, $cpPeriods));
        $normCPs = [];
        foreach ($cpPeriods as $score) {
            $normCPs[] = $range->normalizeSkillScore($score, 'class_participation');
        }
        $normCP = count($normCPs) > 0 ? array_sum($normCPs) / count($normCPs) : 0;

        // Activities: average of 3 periods
        $actPeriods = is_array($activities) ? $activities : [$activities];
        $actPeriods = array_filter(array_map(function($a) { return floatval($a ?? 0); }, $actPeriods));
        $normActs = [];
        foreach ($actPeriods as $score) {
            $normActs[] = $range->normalizeSkillScore($score, 'activities');
        }
        $normAct = count($normActs) > 0 ? array_sum($normActs) / count($normActs) : 0;

        // Assignments: average of 3 periods
        $assignPeriods = is_array($assignments) ? $assignments : [$assignments];
        $assignPeriods = array_filter(array_map(function($a) { return floatval($a ?? 0); }, $assignPeriods));
        $normAssigns = [];
        foreach ($assignPeriods as $score) {
            $normAssigns[] = $range->normalizeSkillScore($score, 'assignments');
        }
        $normAssign = count($normAssigns) > 0 ? array_sum($normAssigns) / count($normAssigns) : 0;

        $skills = ($normOutput * 0.40) + 
                  ($normCP * 0.30) + 
                  ($normAct * 0.15) + 
                  ($normAssign * 0.15);

        return round(max(0, min(100, $skills)), 2);
    }

    /**
     * Calculate Attitude Score (10% of term grade)
     * 
     * DETAILED BREAKDOWN:
     * - Attitude = 10% of total grade
     * - Within Attitude:
     *   - Behavior: 50% of Attitude = 5% of total grade
     *   - Engagement: 50% of Attitude = 5% of total grade
     *     - Within Engagement:
     *       - Attendance: 60% of Engagement = 3% of total grade
     *       - Class Participation & Awareness: 40% of Engagement = 2% of total grade
     *
     * @param float $behavior Behavior score (raw)
     * @param float $awareness Awareness/Class Participation score (raw)
     * @param float $attendance Attendance score (raw, optional)
     * @param AssessmentRange|null $range Assessment range config
     * @return float Attitude score (0-100)
     */
    public static function calculateAttitude($behavior, $awareness, $attendance = null, $range = null)
    {
        if ($range === null) {
            // Default calculation (backward compatible)
            $behavior = max(0, min(100, floatval($behavior ?? 0)));
            $awareness = max(0, min(100, floatval($awareness ?? 0)));

            if ($attendance !== null) {
                // New calculation with attendance weighting
                $attendance = max(0, min(100, floatval($attendance ?? 0)));
                $engagement = ($attendance * 0.60) + ($awareness * 0.40);
                $attitude = ($behavior * 0.50) + ($engagement * 0.50);
            } else {
                // Legacy calculation
                $attitude = ($behavior * 0.50) + ($awareness * 0.50);
            }

            return round($attitude, 2);
        }

        // With range: normalize scores
        $normBehavior = $range->normalizeAttitudeScore(floatval($behavior ?? 0), 'behavior');

        if ($attendance !== null) {
            // New calculation with attendance weighting
            $normAttendance = $range->normalizeAttitudeScore(floatval($attendance ?? 0), 'attendance');
            $normAwareness = $range->normalizeAttitudeScore(floatval($awareness ?? 0), 'awareness');
            
            // Engagement = Attendance (60%) + Awareness (40%)
            $engagement = ($normAttendance * 0.60) + ($normAwareness * 0.40);
            
            // Attitude = Behavior (50%) + Engagement (50%)
            $attitude = ($normBehavior * 0.50) + ($engagement * 0.50);
        } else {
            // Legacy calculation
            $normAwareness = $range->normalizeAttitudeScore(floatval($awareness ?? 0), 'awareness');
            $attitude = ($normBehavior * 0.50) + ($normAwareness * 0.50);
        }

        return round(max(0, min(100, $attitude)), 2);
    }

    /**
     * Calculate Final Term Grade (CHED Philippines)
     * Knowledge: 40%
     * Skills: 50%
     * Attitude: 10%
     *
     * @param float $knowledge Knowledge score (0-100)
     * @param float $skills Skills score (0-100)
     * @param float $attitude Attitude score (0-100)
     * @return float Final grade (0-100)
     */
    public static function calculateFinalGrade($knowledge, $skills, $attitude)
    {
        $knowledge = max(0, min(100, floatval($knowledge ?? 0)));
        $skills = max(0, min(100, floatval($skills ?? 0)));
        $attitude = max(0, min(100, floatval($attitude ?? 0)));

        $finalGrade = ($knowledge * 0.40) + ($skills * 0.50) + ($attitude * 0.10);

        return round($finalGrade, 2);
    }

    /**
     * Get letter grade from numeric score
     * 90-100: A
     * 80-89: B
     * 70-79: C
     * 60-69: D
     * Below 60: F
     *
     * @param float $score Numeric score (0-100)
     * @return string Letter grade (A, B, C, D, F)
     * @deprecated Use getGradePoint() instead for CHED-compliant grading
     */
    public static function getLetterGrade($score)
    {
        $score = floatval($score ?? 0);

        if ($score >= 90) {
            return 'A';
        } elseif ($score >= 80) {
            return 'B';
        } elseif ($score >= 70) {
            return 'C';
        } elseif ($score >= 60) {
            return 'D';
        } else {
            return 'F';
        }
    }

    /**
     * Convert numeric grade to 4.0 scale grade point (CHED Philippines Standard)
     *
     * Grade Point Scale:
     * 70-74 = 4.00
     * 75 = 3.00
     * 76-78 = 2.75
     * 79-81 = 2.50
     * 82-84 = 2.25
     * 85-87 = 2.00
     * 88-90 = 1.75
     * 91-93 = 1.50
     * 94-96 = 1.25
     * 97-100 = 1.00
     * Below 70 = INC (Incomplete)
     *
     * @param float $score Numeric score (0-100)
     * @return float|string Grade point (4.00 to 1.00) or 'INC' if below 70
     */
    public static function getGradePoint($score)
    {
        $score = floatval($score ?? 0);

        if ($score < 70) {
            return 'INC'; // Incomplete
        } elseif ($score <= 74) {
            return 4.00;
        } elseif ($score <= 75) {
            return 3.00;
        } elseif ($score <= 78) {
            return 2.75;
        } elseif ($score <= 81) {
            return 2.50;
        } elseif ($score <= 84) {
            return 2.25;
        } elseif ($score <= 87) {
            return 2.00;
        } elseif ($score <= 90) {
            return 1.75;
        } elseif ($score <= 93) {
            return 1.50;
        } elseif ($score <= 96) {
            return 1.25;
        } else { // 97-100
            return 1.00;
        }
    }

    /**
     * Get grade badge color
     *
     * @param float $score Numeric score (0-100)
     * @return string Bootstrap color class
     */
    public static function getGradeColor($score)
    {
        $score = floatval($score);

        if ($score >= 90) {
            return 'success'; // A - Green
        } elseif ($score >= 80) {
            return 'info'; // B - Blue
        } elseif ($score >= 70) {
            return 'warning'; // C - Yellow
        } elseif ($score >= 60) {
            return 'secondary'; // D - Gray
        } else {
            return 'danger'; // F - Red
        }
    }

    /**
     * Convert numerical score (0-100) to 4.0 scale grade
     * Based on the grading scale:
     * 70-100 = 1.0-4.0
     * Below 70 = 5.0 (Failed)
     *
     * @param float $score The numerical score (0-100)
     * @return float The converted grade on 4.0 scale
     */
    public static function convertToNumericGrade($score)
    {
        $score = floatval($score);

        // Grading Scale Mapping (based on provided image)
        if ($score >= 98.0) return 4.0;
        elseif ($score >= 95.0) return 4.0;
        elseif ($score >= 92.0) return 4.0;
        elseif ($score >= 89.0) return 3.75;
        elseif ($score >= 86.0) return 3.5;
        elseif ($score >= 83.0) return 3.25;
        elseif ($score >= 80.0) return 3.0;
        elseif ($score >= 77.0) return 2.75;
        elseif ($score >= 74.0) return 2.5;
        elseif ($score >= 71.0) return 2.25;
        elseif ($score >= 70.0) return 2.0;
        else return 5.0; // Failed
    }

    /**
     * Get grading scale table (70-100 to 4.0-2.0, below 70 = 5.0)
     *
     * @return array Grading scale mapping
     */
    public static function getGradingScale()
    {
        return [
            ['min' => 98.0, 'max' => 100.0, 'grade' => 4.0, 'label' => 'Excellent'],
            ['min' => 95.0, 'max' => 97.9, 'grade' => 4.0, 'label' => 'Excellent'],
            ['min' => 92.0, 'max' => 94.9, 'grade' => 4.0, 'label' => 'Excellent'],
            ['min' => 89.0, 'max' => 91.9, 'grade' => 3.75, 'label' => 'Very Good'],
            ['min' => 86.0, 'max' => 88.9, 'grade' => 3.5, 'label' => 'Very Good'],
            ['min' => 83.0, 'max' => 85.9, 'grade' => 3.25, 'label' => 'Good'],
            ['min' => 80.0, 'max' => 82.9, 'grade' => 3.0, 'label' => 'Good'],
            ['min' => 77.0, 'max' => 79.9, 'grade' => 2.75, 'label' => 'Satisfactory'],
            ['min' => 74.0, 'max' => 76.9, 'grade' => 2.5, 'label' => 'Satisfactory'],
            ['min' => 71.0, 'max' => 73.9, 'grade' => 2.25, 'label' => 'Fair'],
            ['min' => 70.0, 'max' => 70.9, 'grade' => 2.0, 'label' => 'Fair'],
            ['min' => 0.0, 'max' => 69.9, 'grade' => 5.0, 'label' => 'Failed'],
        ];
    }

    /**
     * Scope to filter grades by teacher
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope to filter grades by class
     */
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Scope to filter grades by grading period
     */
    public function scopeByPeriod($query, $period)
    {
        return $query->where('grading_period', $period);
    }
}
