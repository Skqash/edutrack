<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $program_code
 * @property string $program_name
 * @property int|null $department_id
 * @property int|null $program_head_id
 * @property int $total_years
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo head()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo department()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany subjects()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany classes()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany instructors() Multiple course instructors via pivot
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany instructorUsers() Users assigned as instructors
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_code',
        'program_name',
        'course_code',
        'department_id',
        'program_head_id',
        'total_years',
        'description',
        'status',
        'campus',
        'school_id',
        'college', // Add college field
        'department', // Add department field
    ];

    protected $hidden = [
        'course_name', // Hide virtual attribute from JSON
        'course_code', // Hide virtual attribute from JSON
    ];

    protected $casts = [
        'department_id' => 'integer',
        'program_head_id' => 'integer',
        'total_years' => 'integer',
    ];

    // Accessors for backwards compatibility
    public function getCourseCodeAttribute()
    {
        return $this->program_code;
    }

    public function getDepartmentAttribute()
    {
        // First try the department relationship
        $department = $this->getRelationValue('department');
        if ($department) {
            return $department->department_name;
        }
        
        // Fall back to college field if no department relationship
        $college = $this->getAttributeFromArray('college');
        if ($college) {
            return $college;
        }
        
        // Fall back to string department field
        return $this->getAttributeFromArray('department');
    }

    public function getCollegeAttribute()
    {
        $department = $this->getRelationValue('department');
        if ($department && $department->college) {
            return $department->college->college_name;
        }
        
        // Fall back to string field if relationship not loaded
        return $this->getAttributeFromArray('college');
    }

    public function getDurationAttribute()
    {
        // Keep legacy support for code that expects duration string
        if ($this->getAttributeFromArray('duration')) {
            return $this->getAttributeFromArray('duration');
        }

        return $this->total_years ? "{$this->total_years} Years" : null;
    }

    public function getStudentsAttribute()
    {
        // If eager-loaded count is available, use it for performance
        if (array_key_exists('students_count', $this->attributes)) {
            return (int) $this->attributes['students_count'];
        }

        return $this->students()->count();
    }

    public function getStudentCountAttribute()
    {
        // Get actual enrolled students count for this course
        return Student::where('course_id', $this->id)->count();
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'program_head_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
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
        // Subjects are linked via program_id (not course_id) in the subjects table
        return $this->hasMany(Subject::class, 'program_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'course_id');
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    // Multiple instructors support via pivot table
    public function instructors()
    {
        return $this->hasMany(CourseInstructor::class);
    }

    // Get all users assigned as instructors for this course
    public function instructorUsers()
    {
        return $this->belongsToMany(
            User::class,
            'course_instructors',
            'course_id',
            'user_id'
        )
            ->withPivot('role')
            ->withTimestamps();
    }

    // Scope for active programs
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    // Scope by department (accepts department id or department name)
    public function scopeByDepartment($query, $department)
    {
        if (is_numeric($department)) {
            return $query->where('department_id', $department);
        }

        return $query->whereHas('department', function ($q) use ($department) {
            $q->where('department_name', $department);
        });
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
