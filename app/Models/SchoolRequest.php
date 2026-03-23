<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_name',
        'school_email',
        'school_phone',
        'school_address',
        'note',
        'status',
        'admin_note',
        'request_type',
        'related_id',
        'related_name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
