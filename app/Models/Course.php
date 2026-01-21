<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $course_code
 * @property string $course_name
 * @property string|null $description
 * @property int $instructor_id
 * @property string $status
 * @property int $credit_hours
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'instructor_id',
        'status',
        'credit_hours'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
