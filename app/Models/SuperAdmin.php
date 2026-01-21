<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperAdmin extends Authenticatable
{
    use Notifiable;

    protected $table = 'super_admins';
    protected $primaryKey = 'id';

    protected $fillable = [
        'super_id',
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

    public $timestamps = false;
}
