<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $subject_code
 * @property string $subject_name
 * @property string $category
 * @property int $credit_hours
 * @property int $course_id
 * @property int $instructor_id
 * @property string|null $description
 */
class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'category',
        'credit_hours',
        'course_id',
        'instructor_id',
        'description'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
