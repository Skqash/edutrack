<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KsaSetting extends Model
{
    use HasFactory;

    protected $table = 'ksa_settings';

    protected $fillable = [
        'class_id',
        'teacher_id',
        'term',
        'knowledge_weight',
        'skills_weight',
        'attitude_weight',
        'total_meetings',
        'attendance_weight',
        'attendance_category',
        'grading_scale',
        'use_weighted_average',
        'round_final_grade',
        'decimal_places',
        'passing_grade',
        'minimum_attendance',
        'include_attendance_in_attitude',
        'auto_calculate',
        'custom_settings',
        'is_locked',
    ];

    protected $casts = [
        'knowledge_weight' => 'decimal:2',
        'skills_weight' => 'decimal:2',
        'attitude_weight' => 'decimal:2',
        'attendance_weight' => 'decimal:2',
        'passing_grade' => 'decimal:2',
        'minimum_attendance' => 'decimal:2',
        'use_weighted_average' => 'boolean',
        'round_final_grade' => 'boolean',
        'include_attendance_in_attitude' => 'boolean',
        'auto_calculate' => 'boolean',
        'is_locked' => 'boolean',
        'custom_settings' => 'array',
    ];

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Validate that weights sum to 100
     */
    public function validateWeights(): bool
    {
        $sum = $this->knowledge_weight + $this->skills_weight + $this->attitude_weight;
        return abs($sum - 100) < 0.01; // Allow for floating point precision
    }

    /**
     * Get or create default KSA settings for a class/term
     */
    public static function getOrCreateDefault(int $classId, string $term, int $teacherId = null): self
    {
        if (!$teacherId) {
            $teacherId = auth()->id();
        }

        return self::firstOrCreate(
            ['class_id' => $classId, 'term' => $term],
            [
                'teacher_id' => $teacherId,
                'knowledge_weight' => 40.00,
                'skills_weight' => 50.00,
                'attitude_weight' => 10.00,
                'grading_scale' => 'percentage',
                'use_weighted_average' => true,
                'round_final_grade' => true,
                'decimal_places' => 2,
                'passing_grade' => 75.00,
                'minimum_attendance' => 75.00,
                'include_attendance_in_attitude' => true,
                'auto_calculate' => true,
            ]
        );
    }
}
