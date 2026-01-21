<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = [
        'class_code',
        'class_name',
        'capacity',
        'description',
        'status',
    ];

    protected $table = 'classes';
}