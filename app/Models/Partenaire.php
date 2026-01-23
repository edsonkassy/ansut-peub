<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom_organisation',
        'type_organisation',
        'secteur_activite',
        'region',
        'commune',
        'adresse',
        'telephone',
        'site_web',
        'description',
        'logo',
        'personne_contact_nom',
        'personne_contact_fonction',
        'personne_contact_email',
        'personne_contact_telephone',
        'status_verification',
        'date_verification'
    ];

    protected $casts = [
        'date_verification' => 'date',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function opportunites(): HasMany
    {
        return $this->hasMany(Opportunite::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Les types d'opportunités autorisés
     */
    public function typesOpportunites()
    {
        return $this->hasMany(PartenaireOpportuniteType::class);
    }

    /**
     * Vérifie si le partenaire peut créer un type d'opportunité
     */
    public function peutCreerOpportunite($type)
    {
        return $this->typesOpportunites()->where('type_opportunite', $type)->exists();
    }

    /**
     * Liste des types d'opportunités disponibles
     */
    public static function typesOpportunitesDisponibles()
    {
        return [
            'stage' => 'Stages',
            'emploi' => 'Emplois',
            'formation' => 'Formations',
            'bourse' => 'Bourses',
            'concours' => 'Concours',
            'evenement' => 'Événements',
            'promotion' => 'Promotions'
        ];
    }

    /**
     * Obtient les types d'opportunités sous forme de collection
     */
    public function getTypesOpportunitesAttribute()
    {
        return $this->typesOpportunites()->get();
    }

    /**
     * Scopes
     */
    public function scopeVerifies($query)
    {
        return $query->where('status_verification', 'verified');
    }

    public function scopeActifs($query)
    {
        return $query->where('status_verification', 'verified');
    }
} 