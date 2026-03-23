<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeComponentEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_id',
        'student_id',
        'raw_score',
        'normalized_score',
    ];

    protected $casts = [
        'raw_score' => 'decimal:2',
        'normalized_score' => 'decimal:2',
    ];

    /**
     * Get the component this entry belongs to
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(GradeComponent::class, 'component_id');
    }

    /**
     * Get the student this entry belongs to
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
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
