<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'department_code',
        'department_name',
        'head_id',
        'description',
        'status'
    ];

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
