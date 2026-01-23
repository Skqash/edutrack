<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'teacher_id',
        'title',
        'description',
        'due_date',
        'max_score',
        'term',
        'instructions',
    ];

    protected $dates = ['due_date'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function getStudentSubmission($studentId)
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }
}
