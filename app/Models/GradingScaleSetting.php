<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GradingScaleSetting extends Model
{
    use HasFactory;

    protected $table = 'grading_scale_settings';

    protected $fillable = [
        'class_id',
        'teacher_id',
        'term',
        'knowledge_percentage',
        'skills_percentage',
        'attitude_percentage',
        'is_locked',
        'description',
    ];

    protected $casts = [
        'knowledge_percentage' => 'float',
        'skills_percentage' => 'float',
        'attitude_percentage' => 'float',
        'is_locked' => 'boolean',
    ];

    /**
     * Get the class this setting belongs to
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get the teacher who created this setting
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Validate that percentages sum to 100
     */
    public static function validatePercentages($k, $s, $a): bool
    {
        $total = $k + $s + $a;
        return abs($total - 100.0) < 0.01; // Account for float rounding
    }

    /**
     * Get or create default settings for a class/term
     */
    public static function getOrCreateDefault($classId, $teacherId, $term = 'midterm')
    {
        return self::firstOrCreate(
            [
                'class_id' => $classId,
                'term' => $term,
            ],
            [
                'teacher_id' => $teacherId,
                'knowledge_percentage' => 40.00,
                'skills_percentage' => 50.00,
                'attitude_percentage' => 10.00,
                'is_locked' => false,
            ]
        );
    }

    /**
     * Get percentages as array
     */
    public function getPercentagesArray(): array
    {
        return [
            'Knowledge' => $this->knowledge_percentage,
            'Skills' => $this->skills_percentage,
            'Attitude' => $this->attitude_percentage,
        ];
    }
}
