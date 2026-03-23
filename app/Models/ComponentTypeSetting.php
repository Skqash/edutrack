<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentTypeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'term',
        'ksa_category',
        'component_type',
        'weight_percentage',
    ];

    protected $casts = [
        'weight_percentage' => 'decimal:2',
    ];

    /**
     * Get the class this setting belongs to
     */
    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get default component type weights for Knowledge
     */
    public static function getKnowledgeDefaults(): array
    {
        return [
            'exam' => 60.00,
            'quiz' => 40.00,
        ];
    }

    /**
     * Get default component type weights for Skills
     */
    public static function getSkillsDefaults(): array
    {
        return [
            'output' => 40.00,
            'class_participation' => 30.00,
            'activity' => 15.00,
            'assignment' => 15.00,
        ];
    }

    /**
     * Get default component type weights for Attitude
     */
    public static function getAttitudeDefaults(): array
    {
        return [
            'behavior' => 50.00,
            'attendance' => 30.00,
            'awareness' => 20.00,
        ];
    }

    /**
     * Get defaults for a specific KSA category
     */
    public static function getDefaultsForCategory(string $category): array
    {
        return match($category) {
            'knowledge' => self::getKnowledgeDefaults(),
            'skills' => self::getSkillsDefaults(),
            'attitude' => self::getAttitudeDefaults(),
            default => [],
        };
    }
}
