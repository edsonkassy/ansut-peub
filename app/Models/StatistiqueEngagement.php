<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatistiqueEngagement extends Model
{
    use HasFactory;

    protected $table = 'statistiques_engagement';

    protected $fillable = [
        'user_id',
        'action',
        'entite_type',
        'entite_id',
        'metadonnees',
        'created_at',
    ];

    protected $casts = [
        'metadonnees' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    protected $dates = [
        'created_at',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByEntity($query, $type, $id = null)
    {
        $query = $query->where('entite_type', $type);
        
        if ($id) {
            $query->where('entite_id', $id);
        }
        
        return $query;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Static helper methods
     */
    public static function track($userId, $action, $entityType = null, $entityId = null, $metadata = null)
    {
        return static::create([
            'user_id' => $userId,
            'action' => $action,
            'entite_type' => $entityType,
            'entite_id' => $entityId,
            'metadonnees' => $metadata,
            'created_at' => now(),
        ]);
    }
} 