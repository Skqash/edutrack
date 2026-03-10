<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $subject_code
 * @property string $subject_name
 * @property string $category
 * @property int $credit_hours
 * @property int|null $course_id
 * @property int|null $instructor_id
 * @property string|null $description
 * @property string|null $type
 * @property string|null $program
 */
class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'category',
        'credit_hours',
        'course_id',
        'instructor_id',
        'description',
        'type',
        'program',
    ];

    protected $casts = [
        'credit_hours' => 'integer',
    ];

    // Boot method to sync program with course
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($subject) {
            // Auto-sync program with course when course_id is set
            if ($subject->course_id && ! $subject->program) {
                $course = Course::find($subject->course_id);
                $subject->program = $course ? $course->program_name : 'General Education';
            }

            // If no course_id and no program, set to General Education
            if (! $subject->course_id && ! $subject->program) {
                $subject->program = 'General Education';
            }
        });

        static::updated(function ($subject) {
            // Update program when course changes
            if ($subject->wasChanged('course_id') && $subject->course_id) {
                $course = Course::find($subject->course_id);
                $subject->program = $course ? $course->program_name : 'General Education';
                $subject->saveQuietly(); // Avoid infinite loop
            }
        });
    }

    // Accessor for program name
    public function getProgramAttribute()
    {
        if (isset($this->attributes['program'])) {
            return $this->attributes['program'];
        }

        return $this->course?->program_name ?? 'General Education';
    }

    // Accessor for units (alias for credit_hours)
    public function getUnitsAttribute()
    {
        return $this->credit_hours;
    }

    // Accessor for program code from course
    public function getProgramCodeAttribute()
    {
        return $this->course?->program_code ?? 'GEN';
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject', 'subject_id', 'teacher_id')
            ->withPivot('status', 'assigned_at')
            ->withTimestamps()
            ->where('role', 'teacher');
    }

    // Scope by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope by program
    public function scopeByProgram($query, $program)
    {
        return $query->where('program', $program)
            ->orWhereHas('course', function ($q) use ($program) {
                $q->where('program_name', $program);
            });
    }

    // Scope by type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope core subjects
    public function scopeCore($query)
    {
        return $query->where('type', 'Core');
    }

    // Scope elective subjects
    public function scopeElective($query)
    {
        return $query->where('type', 'Elective');
    }

    // Scope general education subjects
    public function scopeGeneral($query)
    {
        return $query->where('type', 'General');
    }

    // Scope by course
    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Get subjects grouped by program for display
    public static function getGroupedByProgram()
    {
        return self::with('course')->get()->groupBy(function ($subject) {
            return $subject->program ?? 'General Education';
        });
    }

    // Sync subject with course
    public function syncWithCourse()
    {
        if ($this->course_id) {
            $course = Course::find($this->course_id);
            if ($course) {
                $this->program = $course->program_name;
                $this->saveQuietly();
            }
        }
    }
}
