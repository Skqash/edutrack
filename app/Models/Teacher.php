<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use Notifiable;

    protected $table = 'teachers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'teacher_id',
        'first_name',
        'middle_name',
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