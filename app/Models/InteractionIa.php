<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InteractionIa extends Model
{
    use HasFactory;

    protected $table = 'interactions_ia';

    protected $fillable = [
        'user_id',
        'type_interaction',
        'question',
        'reponse',
        'contexte',
        'satisfaction',
        'created_at',
    ];

    protected $casts = [
        'contexte' => 'array',
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
    public function scopeByType($query, $type)
    {
        return $query->where('type_interaction', $type);
    }

    public function scopeWithSatisfaction($query)
    {
        return $query->whereNotNull('satisfaction');
    }

    /**
     * Methods
     */
    public function evaluer(int $note)
    {
        $this->satisfaction = $note;
        $this->save();
    }
} 