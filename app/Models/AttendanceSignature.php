<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSignature extends Model
{
    protected $table = 'student_attendance_signatures';

    protected $fillable = [
        'student_id',
        'class_id',
        'teacher_id',
        'signature_type',
        'signature_data',
        'signature_filename',
        'signature_mime_type',
        'signature_size',
        'term',
        'signed_date',
        'ip_address',
        'user_agent',
        'additional_metadata',
        'is_verified',
        'verified_at',
        'verified_by',
        'verification_notes',
        'status',
        'is_active',
    ];

    protected $casts = [
        'signed_date' => 'date',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'additional_metadata' => 'json',
    ];

    /**
     * Get the student that owns this signature
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the class that owns this signature
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class);
    }

    /**
     * Get the teacher who uploaded this signature
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the user who verified this signature
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope to get pending signatures
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved signatures
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get active signatures
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get signatures for a specific term
     */
    public function scopeForTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    /**
     * Approve a signature
     */
    public function approve($verifiedBy, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $verifiedBy,
            'verification_notes' => $notes,
        ]);
        return $this;
    }

    /**
     * Reject a signature
     */
    public function reject($verifiedBy, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'is_verified' => false,
            'verified_at' => now(),
            'verified_by' => $verifiedBy,
            'verification_notes' => $notes,
        ]);
        return $this;
    }

    /**
     * Archive a signature
     */
    public function archive()
    {
        $this->update([
            'status' => 'archived',
            'is_active' => false,
        ]);
        return $this;
    }
}
