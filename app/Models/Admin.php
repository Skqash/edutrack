<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'id';

    protected $fillable = [
        'admin_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'contact_number'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public $timestamps = false; // your table uses custom created_at only
}
