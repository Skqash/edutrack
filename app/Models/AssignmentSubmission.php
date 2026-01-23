<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_content',
        'file_path',
        'submitted_at',
        'score',
        'feedback',
        'status',
    ];

    protected $dates = ['submitted_at'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function isLate()
    {
        return $this->submitted_at && $this->submitted_at->greaterThan($this->assignment->due_date);
    }

    public function isGraded()
    {
        return $this->score !== null;
    }
}
