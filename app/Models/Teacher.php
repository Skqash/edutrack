<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'qualification',
        'specialization',
        'department',
        'school_id',
        'status',
        'bio',
        'connected_school',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the teacher's full name
     */
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the teacher's role
     */
    public function getRoleAttribute()
    {
        return 'teacher';
    }

    /**
     * Get campus status from related user
     */
    public function getCampusStatusAttribute()
    {
        return $this->user->campus_status ?? 'pending';
    }

    /**
     * Get campus from related user
     */
    public function getCampusAttribute()
    {
        return $this->user->campus ?? null;
    }

    /**
     * Get the school this teacher belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user account for this teacher
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get classes taught by this teacher
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }
}