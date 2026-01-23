<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $student_id
 * @property string $admission_number
 * @property string $roll_number
 * @property int $class_id
 * @property float $gpa
 * @property string $status
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'admission_number',
        'roll_number',
        'class_id',
        'gpa',
        'status'
    ];

    /**
     * Eager load relationships by default for performance
     */
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
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
     * Get student's full name from related user
     */
    public function getFullNameAttribute()
    {
        return $this->user?->name ?? 'N/A';
    }

    /**
     * Get student's email from related user
     */
    public function getEmailAttribute()
    {
        return $this->user?->email ?? 'N/A';
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
}