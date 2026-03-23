<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComponentAverage extends Model
{
    use HasFactory;

    protected $table = 'component_averages';

    protected $fillable = [
        'student_id',
        'class_id',
        'term',
        'knowledge_average',
        'skills_average',
        'attitude_average',
        'final_grade',
    ];

    protected $casts = [
        'knowledge_average' => 'decimal:2',
        'skills_average' => 'decimal:2',
        'attitude_average' => 'decimal:2',
        'final_grade' => 'decimal:2',
    ];

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the class
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Calculate and update averages for a student for a given term using flexible KSA percentages
     * Now includes attendance in the final grade calculation
     */
    public static function calculateAndUpdate($studentId, $classId, $term)
    {
        // Get all component entries for this student/class/term
        $entries = ComponentEntry::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->with('component')
            ->get();

        if ($entries->isEmpty()) {
            return null;
        }

        // Group by category and calculate weighted averages
        $categories = $entries->groupBy('component.category');

        $knowledgeAvg = self::calculateCategoryAverage($categories->get('Knowledge', collect()));
        $skillsAvg = self::calculateCategoryAverage($categories->get('Skills', collect()));
        $attitudeAvg = self::calculateCategoryAverage($categories->get('Attitude', collect()));

        // Get flexible KSA percentages from settings
        $settings = GradingScaleSetting::getOrCreateDefault($classId, null, $term);
        $kPercent = $settings->knowledge_percentage / 100;
        $sPercent = $settings->skills_percentage / 100;
        $aPercent = $settings->attitude_percentage / 100;

        // Calculate component grade (Knowledge + Skills + Attitude)
        $componentGrade = ($knowledgeAvg * $kPercent) + ($skillsAvg * $sPercent) + ($attitudeAvg * $aPercent);

        // Get attendance score and calculate final grade with attendance
        $class = ClassModel::find($classId);
        $attendanceWeight = $class->attendance_percentage ?? 0;
        $componentWeight = 100 - $attendanceWeight;
        
        // Get attendance score
        $attendanceService = new \App\Services\AttendanceCalculationService();
        $attendanceData = $attendanceService->calculateAttendanceScore($studentId, $classId, ucfirst($term));
        $attendanceScore = $attendanceData['attendance_score'];
        
        // Calculate final grade: (component_grade × component_weight%) + (attendance_score × attendance_weight%)
        $finalGrade = ($componentGrade * ($componentWeight / 100)) + ($attendanceScore * ($attendanceWeight / 100));

        // Update or create the average record
        return self::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_id' => $classId,
                'term' => $term,
            ],
            [
                'knowledge_average' => $knowledgeAvg,
                'skills_average' => $skillsAvg,
                'attitude_average' => $attitudeAvg,
                'final_grade' => $finalGrade,
            ]
        );
    }

    /**
     * Calculate weighted average for a category
     * Now properly handles subcategories (e.g., multiple quizzes)
     * 
     * Process:
     * 1. Each component score is normalized: (raw/max) × 50 + 50
     * 2. Group by subcategory and average
     * 3. Apply weights to get category total
     */
    private static function calculateCategoryAverage($categoryEntries)
    {
        if ($categoryEntries->isEmpty()) {
            return 0;
        }

        // Group entries by subcategory
        $subcategories = $categoryEntries->groupBy('component.subcategory');
        
        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($subcategories as $subcategory => $entries) {
            // Calculate average for this subcategory
            // All entries in a subcategory (e.g., Quiz 1, Quiz 2, Quiz 3) are averaged first
            $subcategoryAverage = $entries->avg('normalized_score');
            
            // Get the weight for this subcategory (use first entry's weight as they should be the same)
            $weight = $entries->first()->component->weight ?? 0;
            
            $weightedSum += $subcategoryAverage * $weight;
            $totalWeight += $weight;
        }

        if ($totalWeight == 0) {
            return 0;
        }

        return round($weightedSum / $totalWeight, 2);
    }

    /**
     * Convert to decimal grade (1.0 - 5.0 scale)
     */
    public function getDecimalGrade()
    {
        $grade = $this->final_grade ?? 0;

        if ($grade >= 98) return 1.0;
        elseif ($grade >= 95) return 1.25;
        elseif ($grade >= 92) return 1.50;
        elseif ($grade >= 89) return 1.75;
        elseif ($grade >= 86) return 2.00;
        elseif ($grade >= 83) return 2.25;
        elseif ($grade >= 80) return 2.50;
        elseif ($grade >= 77) return 2.75;
        elseif ($grade >= 74) return 3.00;
        elseif ($grade >= 71) return 3.25;
        elseif ($grade >= 70) return 3.50;
        else return 5.0;
    }

    /**
     * Get letter grade
     */
    public function getLetterGrade()
    {
        $grade = $this->final_grade ?? 0;

        if ($grade >= 98) return 'A+';
        elseif ($grade >= 95) return 'A';
        elseif ($grade >= 92) return 'A-';
        elseif ($grade >= 89) return 'B+';
        elseif ($grade >= 86) return 'B';
        elseif ($grade >= 83) return 'B-';
        elseif ($grade >= 80) return 'C+';
        elseif ($grade >= 77) return 'C';
        elseif ($grade >= 74) return 'C-';
        elseif ($grade >= 71) return 'D+';
        elseif ($grade >= 70) return 'D';
        else return 'F';
    }
}
