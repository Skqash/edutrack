<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type', // info, warning, success, danger
        'read_at',
        'action_url',
        'icon'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that the notification belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Get unread count for a user
     */
    public static function unreadCount($userId): int
    {
        return self::where('user_id', $userId)
                   ->whereNull('read_at')
                   ->count();
    }
}
