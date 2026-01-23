<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'expediteur_type',
        'expediteur_id',
        'contenu',
        'fichiers_joints',
        'lu',
    ];

    protected $casts = [
        'fichiers_joints' => 'array',
        'lu' => 'boolean',
    ];

    /**
     * Relations
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function expediteur(): MorphTo
    {
        return $this->morphTo('expediteur', 'expediteur_type', 'expediteur_id');
    }

    /**
     * Scopes
     */
    public function scopeNonLus($query)
    {
        return $query->where('lu', false);
    }

    public function scopeParExpediteur($query, $type, $id)
    {
        return $query->where('expediteur_type', $type)
                    ->where('expediteur_id', $id);
    }

    /**
     * Methods
     */
    public function marquerCommeLu()
    {
        $this->lu = true;
        $this->save();
    }

    /**
     * Boot method to update conversation last activity
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($message) {
            $message->conversation->updateLastActivity();
        });
    }
} 