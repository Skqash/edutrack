<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $class_name
 * @property string|null $name
 * @property int|null $class_level
 * @property int|null $level
 * @property string|null $section
 * @property string|null $year (1, 2, 3, 4)
 * @property string|null $academic_year
 * @property string|null $semester
 * @property string|null $school_year
 * @property int|null $total_students
 * @property int|null $capacity
 * @property int|null $teacher_id
 * @property int|null $course_id
 * @property int|null $subject_id
 * @property int|null $program_id
 * @property string|null $description
 * @property string|null $status
 * @property string|null $current_term
 * @property int|null $units
 */
class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'class_name',
        'name',
        'level',
        'class_level', // Add class_level
        'section',
        'year', // Integer: 1, 2, 3, 4 (actual DB column)
        'academic_year', // Primary year field used in business logic
        'semester',
        'total_students', // Correct column name instead of capacity
        'teacher_id',
        'course_id',
        'subject_id', // Added subject_id
        'program_id',
        'description',
        'status',
        'current_term',
        'units',
        'campus',
        'school_id', // Add school_id
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function program()
    {
        return $this->belongsTo(Course::class, 'program_id');
    }

    /**
     * School relationship
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'class_id');
    }

    public function gradeEntries()
    {
        return $this->hasMany(GradeEntry::class, 'class_id');
    }

    public function studentCount()
    {
        return $this->students()->count();
    }

    public function utilizationPercentage()
    {
        return round(($this->studentCount() / $this->total_students) * 100, 2);
    }
}
