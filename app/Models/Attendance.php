<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\ClassModel;

class Attendance extends Model
{
    protected $table = 'attendance';
    
    protected $fillable = [
        'student_id',
        'class_id',
        'date',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }
}
