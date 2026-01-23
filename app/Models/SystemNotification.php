<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read',
        'email_sent',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'email_sent' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Types de notifications
    const TYPE_OTP = 'otp';
    const TYPE_CANDIDATURE_STATUS = 'candidature_status';
    const TYPE_NEW_RESOURCE = 'new_resource';
    const TYPE_NEW_MESSAGE = 'new_message';
    const TYPE_FORUM_REPLY = 'forum_reply';
    const TYPE_FORUM_REACTION = 'forum_reaction';
    const TYPE_OPPORTUNITY_MATCH = 'opportunity_match';
    const TYPE_LIBRARY_COMMENT = 'library_comment';
    const TYPE_SYSTEM_ANNOUNCEMENT = 'system_announcement';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function markAsRead(): void
    {
        $this->update([
            'read' => true,
            'read_at' => now()
        ]);
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_OTP => 'shield',
            self::TYPE_CANDIDATURE_STATUS => 'briefcase',
            self::TYPE_NEW_RESOURCE => 'book-open',
            self::TYPE_NEW_MESSAGE => 'message-circle',
            self::TYPE_FORUM_REPLY => 'reply',
            self::TYPE_FORUM_REACTION => 'heart',
            self::TYPE_OPPORTUNITY_MATCH => 'star',
            self::TYPE_LIBRARY_COMMENT => 'message-square',
            self::TYPE_SYSTEM_ANNOUNCEMENT => 'megaphone',
            default => 'bell'
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_OTP => 'text-blue-600',
            self::TYPE_CANDIDATURE_STATUS => 'text-green-600',
            self::TYPE_NEW_RESOURCE => 'text-purple-600',
            self::TYPE_NEW_MESSAGE => 'text-primary-600',
            self::TYPE_FORUM_REPLY => 'text-indigo-600',
            self::TYPE_FORUM_REACTION => 'text-red-600',
            self::TYPE_OPPORTUNITY_MATCH => 'text-yellow-600',
            self::TYPE_LIBRARY_COMMENT => 'text-gray-600',
            self::TYPE_SYSTEM_ANNOUNCEMENT => 'text-orange-600',
            default => 'text-gray-500'
        };
    }

    public static function createNotification(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data = [],
        bool $sendEmail = false
    ): self {
        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'email_sent' => $sendEmail
        ]);
    }
}