<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $role
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'employee_id',
        'qualification',
        'specialization',
        'department',
        'campus',
        'campus_status',
        'campus_approved_at',
        'campus_approved_by',
        'connected_school',
        'bio',
        'theme',
        'grading_scheme',
        'grading_weights',
        'school_id', // Add school_id
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'grading_weights' => 'array',
        'campus_approved_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function schoolRequests()
    {
        return $this->hasMany(SchoolRequest::class);
    }

    /**
     * Get the admin who approved the campus affiliation
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'campus_approved_by');
    }

    /**
     * Get the school this user belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id');
    }

    /**
     * Get the subjects assigned to this teacher.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject', 'teacher_id', 'subject_id')
            ->withPivot('status', 'assigned_at')
            ->withTimestamps();
    }

    public function gradesPosted()
    {
        return $this->hasMany(Grade::class, 'teacher_id');
    }

    /**
     * Get the course access requests made by this teacher
     */
    public function courseAccessRequests()
    {
        return $this->hasMany(CourseAccessRequest::class, 'teacher_id');
    }
}
