<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'class_name',
        'name',
        'class_level',
        'level',
        'section',
        'year',
        'capacity',
        'teacher_id',
        'subject_id',
        'description',
        'status'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
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

    public function studentCount()
    {
        return $this->students()->count();
    }

    public function utilizationPercentage()
    {
        return round(($this->studentCount() / $this->capacity) * 100, 2);
    }
}
