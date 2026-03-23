<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Subject Model - Represents curriculum subjects
 *
 * @property int $id
 * @property string $subject_code
 * @property string $subject_name
 * @property string $category (Core / General Ed / Major / Specialization)
 * @property int $credit_hours
 * @property int|null $program_id (FK to courses table)
 * @property string|null $description
 * @property string|null $semester (1 or 2)
 * @property int|null $year_level (1-4)
 * @property-read Course $program
 */
class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'category',
        'credit_hours',
        'program_id',
        'description',
        'semester',
        'year_level',
        'campus',
        'school_id',
        'campus_code', // Campus code separated from subject code
    ];

    protected $casts = [
        'credit_hours' => 'integer',
        'year_level' => 'integer',
    ];

    // Accessor for program code from course
    public function getProgramCodeAttribute()
    {
        return $this->program?->program_code ?? 'GEN';
    }

    // Accessor for units (alias for credit_hours)
    public function getUnitsAttribute()
    {
        return $this->credit_hours;
    }

    /**
     * Relationship: Subject belongs to a Program (Course)
     * Renamed from course() to program() for clarity
     */
    public function program()
    {
        return $this->belongsTo(Course::class, 'program_id');
    }

    /**
     * Alias for backward compatibility
     */
    public function course()
    {
        return $this->program();
    }

    /**
     * School relationship
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Campus school relationship via campus_code
     */
    public function campusSchool()
    {
        return $this->belongsTo(School::class, 'campus_code', 'school_code');
    }

    /**
     * Get full subject code with campus prefix
     */
    public function getFullSubjectCodeAttribute()
    {
        return $this->campus_code ? $this->campus_code . '-' . $this->subject_code : $this->subject_code;
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

    /**
     * Get all instructors for this subject (via pivot table)
     * Supports multiple instructors with different roles
     */
    public function instructors()
    {
        return $this->hasMany(SubjectInstructor::class);
    }

    /**
     * Get all instructor users for this subject
     */
    public function instructorUsers()
    {
        return $this->belongsToMany(User::class, 'subject_instructors', 'subject_id', 'user_id')
            ->withPivot('role', 'status', 'assigned_at')
            ->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_subjects', 'subject_id', 'class_id');
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
        // Subject uses program_id to link to courses
        return $query->where('program_id', $courseId);
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
        if ($this->program_id) {
            $course = Course::find($this->program_id);
            if ($course) {
                $this->program_name = $course->program_name;
                $this->saveQuietly();
            }
        }
    }
}
