<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// KsaSetting is referenced via full namespace in calculateAndUpdate to avoid circular imports

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
     * Attendance is factored in via KsaSetting configuration (attendance_weight + attendance_category)
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
        $skillsAvg    = self::calculateCategoryAverage($categories->get('Skills', collect()));
        $attitudeAvg  = self::calculateCategoryAverage($categories->get('Attitude', collect()));

        // Get KSA weights from KsaSetting (teacher-configurable)
        $ksaSetting = \App\Models\KsaSetting::where('class_id', $classId)->where('term', $term)->first();
        $kPercent = ($ksaSetting->knowledge_weight ?? 40) / 100;
        $sPercent = ($ksaSetting->skills_weight    ?? 50) / 100;
        $aPercent = ($ksaSetting->attitude_weight  ?? 10) / 100;

        // Apply attendance score to the configured category
        if ($ksaSetting && $ksaSetting->total_meetings > 0 && $ksaSetting->attendance_weight > 0) {
            // Calculate attendance score directly: (present+late / total_meetings) × 50 + 50
            $termLabel = ucfirst($term);
            $attendedCount = \App\Models\Attendance::where('student_id', $studentId)
                ->where('class_id', $classId)
                ->where('term', $termLabel)
                ->whereIn('status', ['Present', 'Late'])
                ->count();
            $totalMeetings  = $ksaSetting->total_meetings;
            $attendanceScore = $totalMeetings > 0
                ? min(100, ($attendedCount / $totalMeetings) * 50 + 50)
                : 50;

            // Attendance weight within the chosen category
            $attWeight = $ksaSetting->attendance_weight / 100;
            $category  = strtolower($ksaSetting->attendance_category ?? 'attitude');

            if ($category === 'knowledge') {
                $knowledgeAvg = ($knowledgeAvg * (1 - $attWeight)) + ($attendanceScore * $attWeight);
            } elseif ($category === 'skills') {
                $skillsAvg = ($skillsAvg * (1 - $attWeight)) + ($attendanceScore * $attWeight);
            } else {
                $attitudeAvg = ($attitudeAvg * (1 - $attWeight)) + ($attendanceScore * $attWeight);
            }
        }

        // Final grade = K×kPercent + S×sPercent + A×aPercent
        $finalGrade = ($knowledgeAvg * $kPercent) + ($skillsAvg * $sPercent) + ($attitudeAvg * $aPercent);

        return self::updateOrCreate(
            ['student_id' => $studentId, 'class_id' => $classId, 'term' => $term],
            [
                'knowledge_average' => round($knowledgeAvg, 2),
                'skills_average'    => round($skillsAvg, 2),
                'attitude_average'  => round($attitudeAvg, 2),
                'final_grade'       => round($finalGrade, 2),
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
