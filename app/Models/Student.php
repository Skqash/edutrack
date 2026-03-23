<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $student_id Format: YYYY-XXXX-S (e.g., 2022-0233-V)
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string $email
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $address
 * @property date|null $birth_date
 * @property string|null $gender
 * @property int|null $course_id
 * @property int $year 1, 2, 3, or 4
 * @property int|null $year_level Alias for year field (1-4)
 * @property string $section A, B, C, etc.
 * @property int $class_id Reference to ClassModel
 * @property string|null $department College department (e.g., 'BSIT', 'BEED', 'BSHM')
 * @property float $gpa Grade Point Average
 * @property string $status enrollment status
 * @property string|null $school School/institution name
 * @property string|null $campus Campus affiliation
 * @property date|null $enrollment_date
 * @property string|null $academic_year
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * 
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo course()
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo class()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany grades()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany attendance()
 * 
 * @method \Illuminate\Database\Eloquent\Builder byDepartment(string $department) Filter students by department
 * @method \Illuminate\Database\Eloquent\Builder byYearLevel(int $year) Filter students by year level (1-4)
 * @method \Illuminate\Database\Eloquent\Builder byClass(int $classId) Filter students by class
 * @method \Illuminate\Database\Eloquent\Builder byDepartmentYearClass(string $dept, int $year, int $classId) Filter by dept, year, and class
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'password',
        'phone',
        'address',
        'birth_date',
        'gender',
        'course_id',
        'year',
        'year_level',
        'section',
        'class_id',
        'gpa',
        'status',
        'school',
        'department',
        'campus',
        'enrollment_date',
        'academic_year',
        'school_id', // Add school_id
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Get student's full name
     */
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        return $name;
    }

    /**
     * Get student's name for display (first + last)
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Course relationship
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * School relationship
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get year display (1st, 2nd, 3rd, 4th year)
     */
    public function getYearDisplayAttribute()
    {
        $yearMap = [
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
        ];

        return $yearMap[$this->year] ?? 'Unknown';
    }

    /**
     * Get full section display (e.g., "Year 1, Section A")
     */
    public function getSectionDisplayAttribute()
    {
        return "Year {$this->year}, Section {$this->section}";
    }

    /**
     * Calculate GPA from student's grades
     */
    public function calculateGPA()
    {
        $grades = $this->grades()->whereNotNull('final_grade')->get();
        if ($grades->isEmpty()) {
            return 0;
        }

        $totalPoints = $grades->sum('grade_point');

        return round($totalPoints / $grades->count(), 2);
    }

    /**
     * Query scope: Filter by campus
     */
    public function scopeByCampus($query, $campus)
    {
        return $query->where('campus', $campus);
    }

    /**
     * Query scope: Filter by school
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Query scope: Apply campus isolation for teacher
     */
    public function scopeForTeacher($query, $teacherCampus = null, $teacherSchoolId = null)
    {
        return $query->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
                    ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId));
    }

    /**
     * Query scope: Filter by class
     */
    public function scopeInClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Query scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Query scope: Active students only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Query scope: With grades loaded
     */
    public function scopeWithGrades($query)
    {
        return $query->with('grades.class', 'grades.teacher', 'grades.subject');
    }

    /**
     * Query scope: With attendance loaded
     */
    public function scopeWithAttendance($query)
    {
        return $query->with('attendance');
    }

    /**
     * Query scope: Filter students by department (added Phase 4)
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Query scope: Filter students by year level (added Phase 4)
     */
    public function scopeByYearLevel($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Query scope: Filter students by class (added Phase 4)
     */
    public function scopeByClassId($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Query scope: Filter by department, year level, and class (added Phase 4)
     * Usage: Student::byDepartmentYearClass('BSIT', 2, 5)->get()
     */
    public function scopeByDepartmentYearClass($query, $department, $year, $classId)
    {
        return $query
            ->where('department', $department)
            ->where('year', $year)
            ->where('class_id', $classId);
    }
}

