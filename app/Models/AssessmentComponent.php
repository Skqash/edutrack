<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentComponent extends Model
{
    use HasFactory;

    protected $table = 'assessment_components';

    protected $fillable = [
        'class_id',
        'teacher_id',
        'category', // Knowledge, Skills, Attitude
        'subcategory', // Quiz, Exam, Output, Activity, etc
        'name', // Display name
        'max_score', // Maximum points for this component
        'weight', // Weight percentage within the category
        'passing_score', // Minimum score to pass (optional)
        'order', // Display order within category
        'is_active', // Soft delete via boolean
        'entry_mode', // manual, automated, hybrid
        'calculation_formula', // Formula for automated calculation
        'is_quiz_component', // Is this a quiz
        'quiz_type', // objective, subjective, mixed
        'show_in_report', // Show in grading sheet
        'min_attempts', // Minimum attempts
        'use_best_attempt', // Use best attempt for automated
        'use_average_attempt', // Use average for automated
        'component_metadata', // Additional JSON metadata
    ];

    protected $casts = [
        'max_score' => 'integer',
        'weight' => 'float',
        'passing_score' => 'float',
        'order' => 'integer',
        'is_active' => 'boolean',
        'is_quiz_component' => 'boolean',
        'show_in_report' => 'boolean',
        'min_attempts' => 'integer',
        'use_best_attempt' => 'boolean',
        'use_average_attempt' => 'boolean',
        'component_metadata' => 'json',
    ];

    /**
     * Get entries for this component
     */
    public function entries()
    {
        return $this->hasMany(ComponentEntry::class, 'component_id');
    }

    /**
     * Get the class this component belongs to
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get the teacher who created this component
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Normalize a raw score using the formula: (raw_score / max_score) × 50 + 50
     * This ensures scores range from 50 (0% correct) to 100 (100% correct)
     */
    public function normalizeScore($rawScore): float
    {
        if ($this->max_score <= 0) {
            return 50; // Minimum score is 50
        }
        
        // Formula: (score / max) × 50 + 50
        $percentage = $rawScore / $this->max_score;
        $normalizedScore = ($percentage * 50) + 50;
        
        // Cap at 100
        return min(100, round($normalizedScore, 2));
    }

    /**
     * Scope: Get all active components for a class
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get components by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Check if this is a quiz component
     */
    public function isQuiz(): bool
    {
        return $this->is_quiz_component === true;
    }

    /**
     * Check if this component is manually entered
     */
    public function isManualEntry(): bool
    {
        return $this->entry_mode === 'manual';
    }

    /**
     * Check if this component is auto-calculated
     */
    public function isAutomatedEntry(): bool
    {
        return $this->entry_mode === 'automated';
    }

    /**
     * Check if this component can be both
     */
    public function isHybridEntry(): bool
    {
        return $this->entry_mode === 'hybrid';
    }

    /**
     * Get the entry mode display name
     */
    public function getEntryModeLabel(): string
    {
        return match($this->entry_mode) {
            'manual' => 'Manual Entry',
            'automated' => 'Automated',
            'hybrid' => 'Hybrid (Manual & Automated)',
            default => 'Unknown'
        };
    }

    /**
     * Get quiz type display name
     */
    public function getQuizTypeLabel(): ?string
    {
        if (!$this->isQuiz()) return null;
        
        return match($this->quiz_type) {
            'objective' => 'Objective Quiz',
            'subjective' => 'Subjective Quiz',
            'mixed' => 'Mixed Questions',
            default => 'Quiz'
        };
    }

    /**
     * Check if should use best attempt
     */
    public function shouldUseBestAttempt(): bool
    {
        return $this->use_best_attempt === true;
    }

    /**
     * Check if should use average attempt
     */
    public function shouldUseAverageAttempt(): bool
    {
        return $this->use_average_attempt === true;
    }

    /**
     * Get calculation formula
     */
    public function getFormula(): ?string
    {
        return $this->calculation_formula;
    }

    /**
     * Set calculation formula
     */
    public function setFormula(string $formula)
    {
        $this->calculation_formula = $formula;
        return $this;
    }

    /**
     * Get component metadata
     */
    public function getMetadata($key = null)
    {
        $metadata = $this->component_metadata ?? [];
        
        if ($key) {
            return $metadata[$key] ?? null;
        }
        
        return $metadata;
    }

    /**
     * Check if component should show in report
     */
    public function showInReport(): bool
    {
        return $this->show_in_report === true;
    }
}
