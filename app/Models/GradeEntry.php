<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradeEntry extends Model
{
    use HasFactory;

    protected $table = 'grade_entries';

    protected $fillable = [
        'student_id', 'class_id', 'teacher_id', 'term',
        // Knowledge
        'exam_pr', 'exam_md', 'quiz_1', 'quiz_2', 'quiz_3', 'quiz_4', 'quiz_5',
        // Skills
        'output_1', 'output_2', 'output_3',
        'classpart_1', 'classpart_2', 'classpart_3',
        'activity_1', 'activity_2', 'activity_3',
        'assignment_1', 'assignment_2', 'assignment_3',
        // Attitude
        'behavior_1', 'behavior_2', 'behavior_3',
        'awareness_1', 'awareness_2', 'awareness_3',
        // Computed
        'exam_average', 'quiz_average', 'knowledge_average',
        'output_average', 'classpart_average', 'activity_average', 'assignment_average', 'skills_average',
        'behavior_average', 'awareness_average', 'attitude_average',
        'term_grade', 'remarks'
    ];

    protected $casts = [
        'exam_pr' => 'decimal:2',
        'exam_md' => 'decimal:2',
        'quiz_1' => 'decimal:2',
        'quiz_2' => 'decimal:2',
        'quiz_3' => 'decimal:2',
        'quiz_4' => 'decimal:2',
        'quiz_5' => 'decimal:2',
        'output_1' => 'decimal:2',
        'output_2' => 'decimal:2',
        'output_3' => 'decimal:2',
        'classpart_1' => 'decimal:2',
        'classpart_2' => 'decimal:2',
        'classpart_3' => 'decimal:2',
        'activity_1' => 'decimal:2',
        'activity_2' => 'decimal:2',
        'activity_3' => 'decimal:2',
        'assignment_1' => 'decimal:2',
        'assignment_2' => 'decimal:2',
        'assignment_3' => 'decimal:2',
        'behavior_1' => 'decimal:2',
        'behavior_2' => 'decimal:2',
        'behavior_3' => 'decimal:2',
        'awareness_1' => 'decimal:2',
        'awareness_2' => 'decimal:2',
        'awareness_3' => 'decimal:2',
        'exam_average' => 'decimal:2',
        'quiz_average' => 'decimal:2',
        'knowledge_average' => 'decimal:2',
        'output_average' => 'decimal:2',
        'classpart_average' => 'decimal:2',
        'activity_average' => 'decimal:2',
        'assignment_average' => 'decimal:2',
        'skills_average' => 'decimal:2',
        'behavior_average' => 'decimal:2',
        'awareness_average' => 'decimal:2',
        'attitude_average' => 'decimal:2',
        'term_grade' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
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
     * Compute all averages for this entry and return computed values
     */
    public function computeAverages(array $weights): array
    {
        // EXAM AVERAGE
        $examPr = (float)($this->exam_pr ?? 0);
        $examMd = (float)($this->exam_md ?? 0);
        $examAverage = ($examPr + $examMd) / 2;

        // QUIZ AVERAGE
        $quizzes = [
            (float)($this->quiz_1 ?? 0),
            (float)($this->quiz_2 ?? 0),
            (float)($this->quiz_3 ?? 0),
            (float)($this->quiz_4 ?? 0),
            (float)($this->quiz_5 ?? 0),
        ];
        $quizAverage = array_sum($quizzes) / count($quizzes);

        // KNOWLEDGE = (Exam 60% + Quiz 40%)
        $knowledgeAverage = ($examAverage * 0.60) + ($quizAverage * 0.40);

        // SKILLS COMPONENTS
        $outputs = [(float)($this->output_1 ?? 0), (float)($this->output_2 ?? 0), (float)($this->output_3 ?? 0)];
        $outputAverage = array_sum($outputs) / count($outputs);

        $classparts = [(float)($this->classpart_1 ?? 0), (float)($this->classpart_2 ?? 0), (float)($this->classpart_3 ?? 0)];
        $classpartAverage = array_sum($classparts) / count($classparts);

        $activities = [(float)($this->activity_1 ?? 0), (float)($this->activity_2 ?? 0), (float)($this->activity_3 ?? 0)];
        $activityAverage = array_sum($activities) / count($activities);

        $assignments = [(float)($this->assignment_1 ?? 0), (float)($this->assignment_2 ?? 0), (float)($this->assignment_3 ?? 0)];
        $assignmentAverage = array_sum($assignments) / count($assignments);

        // SKILLS = (Output 40% + ClassPart 30% + Activities 15% + Assignments 15%)
        $skillsAverage = (
            ($outputAverage * 0.40) +
            ($classpartAverage * 0.30) +
            ($activityAverage * 0.15) +
            ($assignmentAverage * 0.15)
        );

        // ATTITUDE COMPONENTS
        $behaviors = [(float)($this->behavior_1 ?? 0), (float)($this->behavior_2 ?? 0), (float)($this->behavior_3 ?? 0)];
        $behaviorAverage = array_sum($behaviors) / count($behaviors);

        $awareness_arr = [(float)($this->awareness_1 ?? 0), (float)($this->awareness_2 ?? 0), (float)($this->awareness_3 ?? 0)];
        $awarenessAverage = array_sum($awareness_arr) / count($awareness_arr);

        // ATTITUDE = (Behavior 50% + Awareness 50%)
        $attitudeAverage = ($behaviorAverage * 0.50) + ($awarenessAverage * 0.50);

        // TERM GRADE = (Knowledge % + Skills % + Attitude %)
        $k = $weights['knowledge'] / 100;
        $s = $weights['skills'] / 100;
        $a = $weights['attitude'] / 100;

        $termGrade = (
            ($knowledgeAverage * $k) +
            ($skillsAverage * $s) +
            ($attitudeAverage * $a)
        );

        return [
            'exam_average' => $examAverage,
            'quiz_average' => $quizAverage,
            'knowledge_average' => $knowledgeAverage,
            'output_average' => $outputAverage,
            'classpart_average' => $classpartAverage,
            'activity_average' => $activityAverage,
            'assignment_average' => $assignmentAverage,
            'skills_average' => $skillsAverage,
            'behavior_average' => $behaviorAverage,
            'awareness_average' => $awarenessAverage,
            'attitude_average' => $attitudeAverage,
            'term_grade' => $termGrade,
        ];
    }
}
