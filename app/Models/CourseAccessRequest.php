<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseAccessRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'course_id',
        'campus',
        'school_id',
        'status',
        'reason',
        'admin_note',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Filter by campus
     */
    public function scopeByCampus($query, $campus)
    {
        return $query->where('campus', $campus);
    }

    /**
     * Scope: Filter by school
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}