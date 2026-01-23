<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle de compatibilité pour les dotations
 * Utilise maintenant les nouvelles tables harmonisées
 */
class Dotation extends Model
{
    use HasFactory;

    protected $table = 'dotations_attributions';

    protected $fillable = [
        'bachelier_id',
        'inventaire_id',
        'identifiant_unique',
        'date_attribution',
        'date_debut',
        'date_fin',
        'status',
        'attribue_par',
        'donnees_specifiques',
    ];

    protected $casts = [
        'date_attribution' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'donnees_specifiques' => 'array',
    ];

    /**
     * Relations
     */
    public function bachelier(): BelongsTo
    {
        return $this->belongsTo(Bachelier::class);
    }

    public function inventaire(): BelongsTo
    {
        return $this->belongsTo(DotationInventaire::class, 'inventaire_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('status', 'en_attente');
    }

    public function scopeByType($query, $type)
    {
        return $query->whereHas('inventaire', function ($q) use ($type) {
            $q->where('type_dotation', $type);
        });
    }

    /**
     * Accesseurs pour maintenir la compatibilité
     */
    public function getTypeDotationAttribute(): string
    {
        return $this->inventaire->type_dotation ?? '';
    }

    public function getNomDotationAttribute(): string
    {
        return $this->inventaire->nom ?? '';
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->inventaire->description;
    }

    public function getValeurMonetaireAttribute(): ?float
    {
        return $this->inventaire->valeur_unitaire;
    }

    public function getQuantiteAttribute(): int
    {
        return 1; // Une attribution = 1 unité
    }

    public function getFournisseurAttribute(): ?string
    {
        return $this->inventaire->fournisseur->nom ?? null;
    }

    public function getNumeroSerieAttribute(): ?string
    {
        return $this->identifiant_unique;
    }

    public function getConditionsUtilisationAttribute(): ?string
    {
        return $this->donnees_specifiques['conditions_utilisation'] ?? null;
    }

    /**
     * Méthodes statiques pour créer des dotations
     */
    public static function creerPourBachelier(int $bachelierId, int $inventaireId, array $donnees = []): self
    {
        $attribution = self::create([
            'bachelier_id' => $bachelierId,
            'inventaire_id' => $inventaireId,
            'identifiant_unique' => $donnees['identifiant_unique'] ?? null,
            'date_attribution' => $donnees['date_attribution'] ?? now(),
            'date_debut' => $donnees['date_debut'] ?? null,
            'date_fin' => $donnees['date_fin'] ?? null,
            'status' => $donnees['status'] ?? 'en_attente',
            'attribue_par' => $donnees['attribue_par'] ?? auth()->id(),
            'donnees_specifiques' => $donnees['donnees_specifiques'] ?? [],
        ]);

        // Créer le mouvement de stock
        DotationMouvementStock::creerSortie(
            $inventaireId,
            1,
            'Attribution à étudiant',
            $attribution->id,
            "Attribution à " . $attribution->bachelier->nom . " " . $attribution->bachelier->prenoms,
            $attribution->attribue_par
        );

        return $attribution;
    }
} 