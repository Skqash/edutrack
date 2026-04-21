<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'admin_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'school_id',
        'status',
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
     * Get the admin's full name
     */
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the admin's role
     */
    public function getRoleAttribute()
    {
        return 'admin';
    }

    /**
     * Check if admin is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get the school this admin belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user account for this admin
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}