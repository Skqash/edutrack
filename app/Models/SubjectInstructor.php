<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectInstructor extends Model
{
    use HasFactory;

    protected $table = 'subject_instructors';
    protected $fillable = ['subject_id', 'user_id', 'role'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
