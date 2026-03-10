<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $program_code
 * @property string $program_name
 * @property string|null $college
 * @property string|null $department
 * @property string|null $description
 * @property int|null $head_id
 * @property string $status
 * @property string|null $duration
 * @property int|null $max_students
 * @property int|null $current_students
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_code',
        'program_name',
        'college',
        'department',
        'description',
        'head_id',
        'status',
        'duration',
        'max_students',
        'current_students',
    ];

    protected $casts = [
        'max_students' => 'integer',
        'current_students' => 'integer',
    ];

    // Accessor for backward compatibility
    public function getCourseCodeAttribute()
    {
        return $this->program_code;
    }

    public function getCourseNameAttribute()
    {
        return $this->program_name;
    }

    // Accessor for students count
    public function getStudentsAttribute()
    {
        return $this->current_students ?? 0;
    }

    // Accessor for department (fallback to college)
    public function getDepartmentAttribute()
    {
        return $this->department ?? $this->college ?? 'General Education';
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function students()
    {
        return $this->hasManyThrough(User::class, ClassModel::class, 'course_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    // Scope for active programs
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    // Scope by college
    public function scopeByCollege($query, $college)
    {
        return $query->where('college', $college);
    }

    // Sync all subjects in this course
    public function syncSubjects()
    {
        $subjects = $this->subjects;
        $updated = 0;

        foreach ($subjects as $subject) {
            $oldProgram = $subject->program;
            $subject->program = $this->program_name;

            if ($oldProgram !== $subject->program) {
                $subject->saveQuietly();
                $updated++;
            }
        }

        return $updated;
    }

    // Get total credits for this course
    public function getTotalCreditsAttribute()
    {
        return $this->subjects->sum('credit_hours');
    }

    // Get core subjects count
    public function getCoreSubjectsCountAttribute()
    {
        return $this->subjects()->where('type', 'Core')->count();
    }

    // Get elective subjects count
    public function getElectiveSubjectsCountAttribute()
    {
        return $this->subjects()->where('type', 'Elective')->count();
    }
}
