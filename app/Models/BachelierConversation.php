<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BachelierConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'initiator_id',
        'participant_id',
        'subject',
        'last_message_at',
        'initiator_archived',
        'participant_archived'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'initiator_archived' => 'boolean',
        'participant_archived' => 'boolean',
    ];

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(BachelierMessage::class, 'conversation_id')->orderBy('created_at');
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(BachelierMessage::class, 'conversation_id')->latest();
    }

    // Récupère les conversations pour un utilisateur donné
    public function scopeForUser($query, int $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('initiator_id', $userId)
              ->orWhere('participant_id', $userId);
        });
    }

    // Exclut les conversations archivées pour l'utilisateur
    public function scopeNotArchivedFor($query, int $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where(function($subQ) use ($userId) {
                $subQ->where('initiator_id', $userId)
                     ->where('initiator_archived', false);
            })->orWhere(function($subQ) use ($userId) {
                $subQ->where('participant_id', $userId)
                     ->where('participant_archived', false);
            });
        });
    }

    // Obtient l'autre participant de la conversation
    public function getOtherParticipant(int $userId): User
    {
        return $this->initiator_id === $userId 
            ? $this->participant 
            : $this->initiator;
    }

    // Vérifie si l'utilisateur a des messages non lus
    public function hasUnreadMessagesFor(int $userId): bool
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('read_by_recipient', false)
            ->exists();
    }

    // Compte les messages non lus pour un utilisateur
    public function getUnreadCountFor(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('read_by_recipient', false)
            ->count();
    }

    // Marque tous les messages comme lus pour l'utilisateur
    public function markAsReadFor(int $userId): void
    {
        $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('read_by_recipient', false)
            ->update([
                'read_by_recipient' => true,
                'read_at' => now()
            ]);
    }

    // Archive la conversation pour un utilisateur
    public function archiveFor(int $userId): void
    {
        if ($this->initiator_id === $userId) {
            $this->update(['initiator_archived' => true]);
        } else {
            $this->update(['participant_archived' => true]);
        }
    }

    // Trouve ou crée une conversation entre deux utilisateurs
    public static function findOrCreateBetween(int $userId1, int $userId2): self
    {
        // S'assurer que userId1 < userId2 pour éviter les doublons
        if ($userId1 > $userId2) {
            [$userId1, $userId2] = [$userId2, $userId1];
        }

        return self::firstOrCreate([
            'initiator_id' => $userId1,
            'participant_id' => $userId2,
        ]);
    }
}