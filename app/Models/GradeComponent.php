<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeComponent extends Model
{
    use HasFactory;

    protected $table = 'assessment_components';

    protected $fillable = [
        'class_id',
        'teacher_id',
        'category',
        'subcategory',
        'name',
        'max_score',
        'weight',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_score' => 'integer',
        'weight' => 'decimal:2',
        'order' => 'integer',
    ];

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(ComponentEntry::class, 'component_id');
    }

    /**
     * Normalize a raw score using the formula: (raw_score / max_score) × 50 + 50
     * This ensures scores range from 50 (0% correct) to 100 (100% correct)
     */
    public function normalizeScore(float $rawScore): float
    {
        if ($this->max_score == 0) {
            return 50; // Minimum score is 50
        }
        
        // Formula: (score / max) × 50 + 50
        $percentage = $rawScore / $this->max_score;
        $normalizedScore = ($percentage * 50) + 50;
        
        // Cap at 100
        return min(100, round($normalizedScore, 2));
    }

    /**
     * Get entry for a specific student and term
     */
    public function getEntryForStudent(int $studentId, string $term): ?ComponentEntry
    {
        return $this->entries()
            ->where('student_id', $studentId)
            ->where('term', $term)
            ->first();
    }

    /**
     * Scope: Get components by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Get active components
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
