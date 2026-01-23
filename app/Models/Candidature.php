<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'bachelier_id',
        'opportunite_id',
        'type_interaction',
        'lettre_motivation',
        'documents_joints',
        'status',
        'date_soumission',
        'date_reponse',
        'commentaire_partenaire',
        'score_matching',
        'evaluation_experience',
        'commentaire_evaluation',
        'certificat_obtenu',
        'code_utilise',
    ];

    protected $casts = [
        'documents_joints' => 'array',
        'date_soumission' => 'datetime',
        'date_reponse' => 'datetime',
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

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
} 