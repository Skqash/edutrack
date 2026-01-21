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
        // Knowledge Components
        'q1', 'q2', 'q3', 'q4', 'q5',
        'prelim_exam', 'midterm_exam', 'final_exam',
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
        'grade_letter',
        'remarks',
        'grading_period',
    ];

    protected $casts = [
        'q1' => 'decimal:2',
        'q2' => 'decimal:2',
        'q3' => 'decimal:2',
        'q4' => 'decimal:2',
        'q5' => 'decimal:2',
        'prelim_exam' => 'decimal:2',
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
     * Quizzes (Q1-Q5): 40% of Knowledge (configurable items)
     * Exams (PR, MD, FE): 60% of Knowledge (configurable items)
     *
     * @param array $quizzes [q1, q2, q3, q4, q5] - raw scores
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

        // Normalize quiz scores based on configured max values
        $quizzes = array_filter(array_map(function($q) { return floatval($q ?? 0); }, $quizzes));
        $normalizedQuizzes = [];
        
        foreach ($quizzes as $i => $score) {
            $quizNum = $i + 1;
            $normalizedScore = $range->normalizeQuizScore($score, $quizNum);
            $normalizedQuizzes[] = $normalizedScore;
        }
        
        $quizAverage = count($normalizedQuizzes) > 0 ? array_sum($normalizedQuizzes) / count($normalizedQuizzes) : 0;
        $quizPart = $quizAverage * 0.40; // 40% of knowledge

        // Normalize exam scores based on configured max values
        if ($term === 'midterm') {
            $prelim = floatval($exams['prelim'] ?? 0);
            $midterm = floatval($exams['midterm'] ?? 0);
            $normPrelim = $range->normalizeExamScore($prelim, 'prelim');
            $normMidterm = $range->normalizeExamScore($midterm, 'midterm');
            $examAverage = ($normPrelim + $normMidterm) / 2;
        } else {
            $midterm = floatval($exams['midterm'] ?? 0);
            $final = floatval($exams['final'] ?? 0);
            $normMidterm = $range->normalizeExamScore($midterm, 'midterm');
            $normFinal = $range->normalizeExamScore($final, 'final');
            $examAverage = ($normMidterm + $normFinal) / 2;
        }
        $examPart = $examAverage * 0.60; // 60% of knowledge

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

        if ($term === 'midterm') {
            $prelim = floatval($exams['prelim'] ?? 0);
            $midterm = floatval($exams['midterm'] ?? 0);
            $examAverage = ($prelim + $midterm) / 2;
        } else {
            $midterm = floatval($exams['midterm'] ?? 0);
            $final = floatval($exams['final'] ?? 0);
            $examAverage = ($midterm + $final) / 2;
        }
        $examPart = $examAverage * 0.60;

        return round($quizPart + $examPart, 2);
    }

    /**
     * Calculate Skills Score (50% of term grade)
     * Output: 40%
     * Class Participation: 30%
     * Activities: 15%
     * Assignments: 15%
     *
     * @param float $output Output score (raw)
     * @param float $classParticipation Class participation score (raw)
     * @param float $activities Activities score (raw)
     * @param float $assignments Assignments score (raw)
     * @param AssessmentRange|null $range Assessment range config, if null uses defaults
     * @return float Skills score (0-100)
     */
    public static function calculateSkills($output, $classParticipation, $activities, $assignments, $range = null)
    {
        if ($range === null) {
            // Default calculation (backward compatible)
            $output = max(0, min(100, floatval($output ?? 0)));
            $classParticipation = max(0, min(100, floatval($classParticipation ?? 0)));
            $activities = max(0, min(100, floatval($activities ?? 0)));
            $assignments = max(0, min(100, floatval($assignments ?? 0)));

            $skills = ($output * 0.40) + 
                      ($classParticipation * 0.30) + 
                      ($activities * 0.15) + 
                      ($assignments * 0.15);

            return round($skills, 2);
        }

        // Normalize with configured ranges
        $normOutput = $range->normalizeSkillScore(floatval($output ?? 0), 'output');
        $normCP = $range->normalizeSkillScore(floatval($classParticipation ?? 0), 'class_participation');
        $normAct = $range->normalizeSkillScore(floatval($activities ?? 0), 'activities');
        $normAssign = $range->normalizeSkillScore(floatval($assignments ?? 0), 'assignments');

        $skills = ($normOutput * 0.40) + 
                  ($normCP * 0.30) + 
                  ($normAct * 0.15) + 
                  ($normAssign * 0.15);

        return round(max(0, min(100, $skills)), 2);
    }

    /**
     * Calculate Attitude Score (10% of term grade)
     * Behavior: 50%
     * Awareness: 50%
     *
     * @param float $behavior Behavior score (raw)
     * @param float $awareness Awareness score (raw)
     * @param AssessmentRange|null $range Assessment range config, if null uses defaults
     * @return float Attitude score (0-100)
     */
    public static function calculateAttitude($behavior, $awareness, $range = null)
    {
        if ($range === null) {
            // Default calculation (backward compatible)
            $behavior = max(0, min(100, floatval($behavior ?? 0)));
            $awareness = max(0, min(100, floatval($awareness ?? 0)));
            $attitude = ($behavior * 0.50) + ($awareness * 0.50);
            return round($attitude, 2);
        }

        // Normalize with configured ranges
        $normBehavior = $range->normalizeAttitudeScore(floatval($behavior ?? 0), 'behavior');
        $normAwareness = $range->normalizeAttitudeScore(floatval($awareness ?? 0), 'awareness');

        $attitude = ($normBehavior * 0.50) + ($normAwareness * 0.50);

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
