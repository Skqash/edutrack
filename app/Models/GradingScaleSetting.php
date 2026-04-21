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
        'attendance_affects_grade',
        'is_locked',
        'description',
        'grading_mode',
        'quiz_entry_mode',
        'hybrid_components_config',
        'output_format',
        'enable_esignature',
        'enable_auto_calculation',
        'enable_weighted_components',
        'passing_grade',
        'attendance_weight_percentage',
        'settings_updated_at',
        'component_weight_mode',
    ];

    protected $casts = [
        'knowledge_percentage' => 'float',
        'skills_percentage' => 'float',
        'attitude_percentage' => 'float',
        'attendance_affects_grade' => 'boolean',
        'is_locked' => 'boolean',
        'hybrid_components_config' => 'json',
        'enable_esignature' => 'boolean',
        'enable_auto_calculation' => 'boolean',
        'enable_weighted_components' => 'boolean',
        'passing_grade' => 'float',
        'settings_updated_at' => 'datetime',
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
     * Get the grading sheet template for this setting
     */
    public function template()
    {
        return $this->hasOne(GradingSheetTemplate::class, 'school_id', 'school_id')
            ->where('is_default', true);
    }

    /**
     * Get all signatures for this class/term
     */
    public function signatures()
    {
        return $this->hasMany(AttendanceSignature::class, 'class_id', 'class_id')
            ->where('term', $this->term);
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
                'attendance_affects_grade' => true, // Default to true for backward compatibility
                'is_locked' => false,
                'component_weight_mode' => 'semi-auto', // Default mode
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

    /**
     * Check if this is manual grading mode
     */
    public function isManualMode(): bool
    {
        return $this->grading_mode === 'manual';
    }

    /**
     * Check if this is automated grading mode
     */
    public function isAutomatedMode(): bool
    {
        return $this->grading_mode === 'automated';
    }

    /**
     * Check if this is hybrid grading mode
     */
    public function isHybridMode(): bool
    {
        return $this->grading_mode === 'hybrid';
    }

    /**
     * Check if this is standard grading mode
     */
    public function isStandardMode(): bool
    {
        return $this->grading_mode === 'standard';
    }

    /**
     * Check if quizzes are automated
     */
    public function hasAutomatedQuizzes(): bool
    {
        return in_array($this->quiz_entry_mode, ['automated', 'both']);
    }

    /**
     * Check if quizzes are manual entry allowed
     */
    public function hasManualQuizzes(): bool
    {
        return in_array($this->quiz_entry_mode, ['manual', 'both']);
    }

    /**
     * Check if e-signature is enabled
     */
    public function isESignatureEnabled(): bool
    {
        return $this->enable_esignature === true;
    }

    /**
     * Get hybrid component configuration
     */
    public function getHybridComponentConfig($componentId = null)
    {
        $config = $this->hybrid_components_config ?? [];
        
        if ($componentId) {
            return $config[$componentId] ?? 'manual';
        }
        
        return $config;
    }

    /**
     * Update hybrid component configuration
     */
    public function updateHybridComponentConfig($componentId, $mode)
    {
        $config = $this->hybrid_components_config ?? [];
        $config[$componentId] = $mode;
        $this->update([
            'hybrid_components_config' => $config,
            'settings_updated_at' => now(),
        ]);
    }

    /**
     * Lock settings
     */
    public function lock()
    {
        $this->update(['is_locked' => true]);
    }

    /**
     * Unlock settings
     */
    public function unlock()
    {
        $this->update(['is_locked' => false]);
    }
}
