<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentRange extends Model
{
    use HasFactory;

    protected $table = 'assessment_ranges';

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'quiz_1_max',
        'quiz_2_max',
        'quiz_3_max',
        'quiz_4_max',
        'quiz_5_max',
        'prelim_exam_max',
        'midterm_exam_max',
        'final_exam_max',
        'output_max',
        'class_participation_max',
        'activities_max',
        'assignments_max',
        'behavior_max',
        'awareness_max',
        'attendance_max',
        'attendance_required',
        'total_quiz_items',
        'num_quizzes',
        'equal_quiz_distribution',
        'quiz_distribution',
        'notes',
        // Two-term period-based columns for skills (Midterm & Final only)
        'class_participation_midterm',
        'class_participation_final',
        'activities_midterm',
        'activities_final',
        'assignments_midterm',
        'assignments_final',
        'output_midterm',
        'output_final',
        // Two-term period-based columns for attitude (Midterm & Final only)
        'behavior_midterm',
        'behavior_final',
        'awareness_midterm',
        'awareness_final',
        'quiz_max',
        'exam_max',
        'attendance_min_percentage',
    ];

    protected $casts = [
        'attendance_required' => 'boolean',
    ];

    /**
     * Get the class associated with this assessment range.
     */
    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get the subject associated with this assessment range.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher associated with this assessment range.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the maximum possible score for a quiz.
     */
    public function getQuizMax($quizNumber): int
    {
        return $this->{"quiz_{$quizNumber}_max"} ?? 20;
    }

    /**
     * Get the maximum possible score for an exam.
     */
    public function getExamMax($examType): int
    {
        return $this->{"{$examType}_exam_max"} ?? 60;
    }

    /**
     * Convert a raw score to 100-point scale for Knowledge.
     */
    public function normalizeQuizScore($rawScore, $quizNumber): float
    {
        $maxScore = $this->getQuizMax($quizNumber);

        return ($rawScore / $maxScore) * 100;
    }

    /**
     * Convert a raw exam score to 100-point scale.
     */
    public function normalizeExamScore($rawScore, $examType): float
    {
        $maxScore = $this->getExamMax($examType);

        return ($rawScore / $maxScore) * 100;
    }

    /**
     * Convert a raw skills component score to 100-point scale.
     */
    public function normalizeSkillScore($rawScore, $skillComponent): float
    {
        $maxScore = $this->{"{$skillComponent}_max"} ?? 100;

        return ($rawScore / $maxScore) * 100;
    }

    /**
     * Convert a raw attitude score to 100-point scale.
     */
    public function normalizeAttitudeScore($rawScore, $attitudeComponent): float
    {
        $maxScore = $this->{"{$attitudeComponent}_max"} ?? 100;

        return ($rawScore / $maxScore) * 100;
    }

    /**
     * Convert raw attendance to 100-point scale.
     */
    public function normalizeAttendanceScore($rawScore): float
    {
        $maxScore = $this->attendance_max;

        return ($rawScore / $maxScore) * 100;
    }

    /**
     * Get all quiz maximum scores as array.
     */
    public function getQuizMaxScores(): array
    {
        // If using total quiz items with equal distribution
        if ($this->equal_quiz_distribution && $this->total_quiz_items) {
            $perQuiz = (int) ($this->total_quiz_items / $this->num_quizzes);
            $distribution = [];
            for ($i = 1; $i <= $this->num_quizzes; $i++) {
                $distribution['q'.$i] = $perQuiz;
            }

            return $distribution;
        }

        // If using custom distribution (JSON)
        if ($this->quiz_distribution) {
            return json_decode($this->quiz_distribution, true);
        }

        // Fall back to individual quiz max values
        return [
            'q1' => $this->quiz_1_max,
            'q2' => $this->quiz_2_max,
            'q3' => $this->quiz_3_max,
            'q4' => $this->quiz_4_max,
            'q5' => $this->quiz_5_max,
        ];
    }

    /**
     * Get total quiz items (for display).
     */
    public function getTotalQuizItems(): int
    {
        return $this->total_quiz_items ?? 100;
    }

    /**
     * Get number of quizzes.
     */
    public function getNumQuizzes(): int
    {
        return $this->num_quizzes ?? 5;
    }

    /**
     * Calculate per-quiz percentage of total quizzes.
     * Each quiz = 100% / num_quizzes
     */
    public function getQuizPercentage(): float
    {
        return 100 / $this->getNumQuizzes();
    }

    /**
     * Get all exam maximum scores as array.
     */
    public function getExamMaxScores(): array
    {
        return [
            'midterm' => $this->midterm_exam_max,
            'final' => $this->final_exam_max,
        ];
    }
}
