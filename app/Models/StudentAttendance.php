<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $table = 'student_attendance';

    protected $fillable = [
        'student_id',
        'class_id',
        'subject_id',
        'term',
        'attendance_score',
        'total_classes',
        'present_classes',
        'absent_classes',
        'remarks',
    ];

    protected $casts = [
        'attendance_score' => 'float',
    ];

    /**
     * Get the student associated with this attendance record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the class associated with this attendance record.
     */
    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get the subject associated with this attendance record.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Calculate attendance score based on present vs total classes.
     */
    public function calculateAttendanceScore(): float
    {
        if ($this->total_classes <= 0) {
            return 0;
        }
        
        $percentage = ($this->present_classes / $this->total_classes) * 100;
        
        // Store calculated score
        $this->update(['attendance_score' => round($percentage, 2)]);
        
        return round($percentage, 2);
    }
}
