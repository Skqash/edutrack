<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int|null $class_id
 * @property int|null $subject_id
 * @property int|null $course_id
 * @property string $department
 * @property string $academic_year
 * @property string $semester
 * @property string $status
 * @property string $notes
 */
class TeacherAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'class_id',
        'subject_id',
        'course_id',
        'department',
        'academic_year',
        'semester',
        'status',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id')->where('role', 'teacher');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'assignment_students', 'assignment_id', 'student_id')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeWithClass($query)
    {
        return $query->with('class');
    }

    public function scopeWithSubject($query)
    {
        return $query->with('subject');
    }

    public function scopeWithCourse($query)
    {
        return $query->with('course');
    }

    public function scopeWithTeacher($query)
    {
        return $query->with('teacher');
    }

    // Helper methods
    public function getAssignmentTypeAttribute()
    {
        if ($this->class_id && $this->subject_id) {
            return 'Class & Subject';
        } elseif ($this->class_id) {
            return 'Class Only';
        } elseif ($this->subject_id) {
            return 'Subject Only';
        } elseif ($this->course_id) {
            return 'Course/Department';
        }
        return 'General';
    }

    public function getDisplayNameAttribute()
    {
        $parts = [];
        
        if ($this->class) {
            $parts[] = $this->class->class_name;
        }
        
        if ($this->subject) {
            $parts[] = $this->subject->subject_code;
        }
        
        if ($this->department) {
            $parts[] = $this->department;
        }
        
        return implode(' - ', $parts) ?: 'General Assignment';
    }

    public function assignStudents(array $studentIds)
    {
        $this->students()->syncWithoutDetaching($studentIds);
    }

    public function removeStudents(array $studentIds)
    {
        $this->students()->detach($studentIds);
    }

    public function syncStudents(array $studentIds)
    {
        $this->students()->sync($studentIds);
    }

    public function hasStudent($studentId)
    {
        return $this->students()->where('student_id', $studentId)->exists();
    }

    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }
}
