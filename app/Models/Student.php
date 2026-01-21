<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $student_id
 * @property string $admission_number
 * @property string $roll_number
 * @property int $class_id
 * @property float $gpa
 * @property string $status
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'admission_number',
        'roll_number',
        'class_id',
        'gpa',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function name()
    {
        return $this->user->name ?? 'N/A';
    }
}