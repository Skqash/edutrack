<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SuperAdmin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'super_admins';

    protected $fillable = [
        'user_id',
        'super_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
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
     * Get the super admin's full name
     */
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the super admin's role
     */
    public function getRoleAttribute()
    {
        return 'superadmin';
    }

    /**
     * Get the user account for this super admin
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if super admin is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
