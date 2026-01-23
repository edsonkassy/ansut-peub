<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerte extends Model
{
    use HasFactory;

    protected $fillable = [
        'bachelier_id',
        'nom_alerte',
        'criteres',
        'active',
        'derniere_notification',
    ];

    protected $casts = [
        'criteres' => 'array',
        'active' => 'boolean',
        'derniere_notification' => 'datetime',
    ];

    /**
     * Relations
     */
    public function bachelier(): BelongsTo
    {
        return $this->belongsTo(Bachelier::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Methods
     */
    public function updateLastNotification()
    {
        $this->derniere_notification = now();
        $this->save();
    }

    public function desactiver()
    {
        $this->active = false;
        $this->save();
    }

    public function activer()
    {
        $this->active = true;
        $this->save();
    }
} 