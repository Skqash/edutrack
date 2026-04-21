<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class GradingSheetTemplate extends Model
{
    protected $fillable = [
        'school_id',
        'teacher_id',
        'name',
        'slug',
        'description',
        'template_type',
        'header_config',
        'columns_config',
        'calculations_config',
        'styling_config',
        'sections',
        'include_components',
        'include_ksa_breakdown',
        'include_final_grade',
        'include_decimal_grade',
        'include_remarks',
        'include_signatures',
        'include_grade_scale_legend',
        'columns_per_page',
        'page_orientation',
        'font_family',
        'font_size',
        'is_default',
        'is_active',
        'usage_count',
        'last_used_at',
    ];

    protected $casts = [
        'header_config' => 'json',
        'columns_config' => 'json',
        'calculations_config' => 'json',
        'styling_config' => 'json',
        'sections' => 'json',
        'include_components' => 'boolean',
        'include_ksa_breakdown' => 'boolean',
        'include_final_grade' => 'boolean',
        'include_decimal_grade' => 'boolean',
        'include_remarks' => 'boolean',
        'include_signatures' => 'boolean',
        'include_grade_scale_legend' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get the school that owns this template
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the teacher that owns this template
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get active templates
     */
    public function scopeActive($query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get templates for a school
     */
    public function scopeForSchool($query, $schoolId): Builder
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope to get templates for a teacher
     */
    public function scopeForTeacher($query, $teacherId): Builder
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope to get templates by type
     */
    public function scopeByType($query, $type): Builder
    {
        return $query->where('template_type', $type);
    }

    /**
     * Get default template
     */
    public static function getDefault()
    {
        return self::where('is_default', true)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Mark template as used
     */
    public function markAsUsed()
    {
        $this->update([
            'usage_count' => $this->usage_count + 1,
            'last_used_at' => now(),
        ]);
        return $this;
    }

    /**
     * Set as default (unset others)
     */
    public function setAsDefault()
    {
        // Remove default from other templates in same school/teacher
        $query = self::where('is_default', true);
        
        if ($this->school_id) {
            $query->where('school_id', $this->school_id);
        } else if ($this->teacher_id) {
            $query->where('teacher_id', $this->teacher_id);
        }
        
        $query->update(['is_default' => false]);
        
        // Set this as default
        $this->update(['is_default' => true]);
        return $this;
    }

    /**
     * Get preview data
     */
    public function getPreviewData()
    {
        return [
            'type' => $this->template_type,
            'columns' => $this->columns_config ?? [],
            'sections' => $this->sections ?? [],
            'include_components' => $this->include_components,
            'include_ksa_breakdown' => $this->include_ksa_breakdown,
            'include_final_grade' => $this->include_final_grade,
            'include_signatures' => $this->include_signatures,
        ];
    }
}
