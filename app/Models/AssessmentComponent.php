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
    ];

    protected $casts = [
        'max_score' => 'integer',
        'weight' => 'float',
        'passing_score' => 'float',
        'order' => 'integer',
        'is_active' => 'boolean',
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
}
