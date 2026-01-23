<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favori extends Model
{
    use HasFactory;

    protected $fillable = [
        'bachelier_id',
        'opportunite_id',
    ];

    /**
     * Relations
     */
    public function bachelier(): BelongsTo
    {
        return $this->belongsTo(Bachelier::class);
    }

    public function opportunite(): BelongsTo
    {
        return $this->belongsTo(Opportunite::class);
    }
} 