<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BachelierMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'content',
        'read_by_recipient',
        'read_at'
    ];

    protected $casts = [
        'read_by_recipient' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(BachelierConversation::class, 'conversation_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('read_by_recipient', false);
    }

    public function scopeRead($query)
    {
        return $query->where('read_by_recipient', true);
    }

    public function markAsRead(): void
    {
        $this->update([
            'read_by_recipient' => true,
            'read_at' => now()
        ]);
    }

    // Événement après création pour mettre à jour la conversation
    protected static function booted(): void
    {
        static::created(function (BachelierMessage $message) {
            $message->conversation->update([
                'last_message_at' => $message->created_at
            ]);

            // Créer une notification pour le destinataire
            $conversation = $message->conversation;
            $recipient = $conversation->getOtherParticipant($message->sender_id);
            $senderName = $message->sender->bachelier 
                ? $message->sender->bachelier->prenoms . ' ' . $message->sender->bachelier->nom
                : $message->sender->email;

            // Notification dans la base de données
            SystemNotification::createNotification(
                $recipient,
                SystemNotification::TYPE_NEW_MESSAGE,
                'Nouveau message privé',
                "Vous avez reçu un nouveau message de {$senderName}",
                [
                    'conversation_id' => $conversation->id,
                    'sender_name' => $senderName
                ]
            );

            // Notification par email
            $recipient->notify(new \App\Notifications\NewMessageNotification(
                $message,
                $conversation,
                $senderName
            ));
        });
    }
}