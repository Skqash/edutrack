<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'component_id',
        'term',
        'raw_score',
        'normalized_score',
        'remarks',
    ];

    protected $casts = [
        'raw_score' => 'decimal:2',
        'normalized_score' => 'decimal:2',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(GradeComponent::class, 'component_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Auto-calculate normalized score when raw score is set
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($entry) {
            if ($entry->raw_score !== null && $entry->component) {
                $entry->normalized_score = $entry->component->normalizeScore($entry->raw_score);
            }
        });
    }
}
