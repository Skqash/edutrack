<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * School Model - Represents CPSU campuses and other educational institutions
 *
 * @property int $id
 * @property string $school_code Unique identifier (e.g., 'CPSU-MAIN', 'CPSU-VIC')
 * @property string $school_name Full name (e.g., 'CPSU Main Campus - Kabankalan')
 * @property string $short_name Short display name (e.g., 'Kabankalan', 'Victorias')
 * @property string $campus_type 'main' or 'satellite'
 * @property string $location Full address
 * @property string $city
 * @property string $province
 * @property string $region
 * @property string|null $contact_number
 * @property string|null $email
 * @property string|null $description
 * @property string $status 'Active' or 'Inactive'
 * @property date|null $established_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_code',
        'school_name',
        'short_name',
        'campus_type',
        'location',
        'city',
        'province',
        'region',
        'contact_number',
        'email',
        'description',
        'status',
        'established_date',
    ];

    protected $casts = [
        'established_date' => 'date',
    ];

    /**
     * Get all users (teachers, admins) affiliated with this school
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all students enrolled in this school
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get all courses offered by this school
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get all subjects taught in this school
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get all classes in this school
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    /**
     * Get display name for UI
     */
    public function getDisplayNameAttribute()
    {
        return $this->short_name;
    }

    /**
     * Get full display name
     */
    public function getFullDisplayNameAttribute()
    {
        return $this->school_name;
    }

    /**
     * Check if this is the main campus
     */
    public function isMainCampus()
    {
        return $this->campus_type === 'main';
    }

    /**
     * Check if this is a satellite campus
     */
    public function isSatelliteCampus()
    {
        return $this->campus_type === 'satellite';
    }

    /**
     * Scope for active schools only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for main campus
     */
    public function scopeMainCampus($query)
    {
        return $query->where('campus_type', 'main');
    }

    /**
     * Scope for satellite campuses
     */
    public function scopeSatelliteCampuses($query)
    {
        return $query->where('campus_type', 'satellite');
    }

    /**
     * Scope for CPSU campuses (exclude independent schools)
     */
    public function scopeCpsuCampuses($query)
    {
        return $query->where('school_code', 'like', 'CPSU-%');
    }

    /**
     * Get statistics for this school
     */
    public function getStatistics()
    {
        return [
            'total_students' => $this->students()->count(),
            'total_teachers' => $this->users()->where('role', 'teacher')->count(),
            'total_courses' => $this->courses()->count(),
            'total_classes' => $this->classes()->count(),
            'active_students' => $this->students()->where('status', 'Active')->count(),
            'active_teachers' => $this->users()->where('role', 'teacher')->where('status', 'Active')->count(),
        ];
    }
}