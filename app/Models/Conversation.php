<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'bachelier_id',
        'partenaire_id',
        'opportunite_id',
        'sujet',
        'status',
        'derniere_activite',
    ];

    protected $casts = [
        'derniere_activite' => 'datetime',
    ];

    /**
     * Relations
     */
    public function bachelier(): BelongsTo
    {
        return $this->belongsTo(Bachelier::class);
    }

    public function partenaire(): BelongsTo
    {
        return $this->belongsTo(Partenaire::class);
    }

    public function opportunite(): BelongsTo
    {
        return $this->belongsTo(Opportunite::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('derniere_activite', 'desc');
    }

    /**
     * Methods
     */
    public function updateLastActivity()
    {
        $this->derniere_activite = now();
        $this->save();
    }
} 