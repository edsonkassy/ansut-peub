<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DotationMouvementStock extends Model
{
    use HasFactory;

    protected $table = 'dotations_mouvements_stock';

    protected $fillable = [
        'inventaire_id',
        'type_mouvement',
        'quantite',
        'motif',
        'commentaire',
        'effectue_par',
        'attribution_id',
    ];

    /**
     * Relations
     */
    public function inventaire(): BelongsTo
    {
        return $this->belongsTo(DotationInventaire::class, 'inventaire_id');
    }

    public function effectuePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'effectue_par');
    }

    public function attribution(): BelongsTo
    {
        return $this->belongsTo(DotationAttribution::class, 'attribution_id');
    }

    /**
     * Scopes
     */
    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', 'entree');
    }

    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', 'sortie');
    }

    public function scopeRetours($query)
    {
        return $query->where('type_mouvement', 'retour');
    }

    public function scopeAjustements($query)
    {
        return $query->where('type_mouvement', 'ajustement');
    }

    public function scopeByInventaire($query, $inventaireId)
    {
        return $query->where('inventaire_id', $inventaireId);
    }

    /**
     * Accesseurs
     */
    public function getEstEntreeAttribute(): bool
    {
        return $this->type_mouvement === 'entree';
    }

    public function getEstSortieAttribute(): bool
    {
        return $this->type_mouvement === 'sortie';
    }

    public function getEstRetourAttribute(): bool
    {
        return $this->type_mouvement === 'retour';
    }

    public function getEstAjustementAttribute(): bool
    {
        return $this->type_mouvement === 'ajustement';
    }

    /**
     * Méthodes statiques pour créer des mouvements
     */
    public static function creerEntree(int $inventaireId, int $quantite, string $motif, ?string $commentaire = null, ?int $effectuePar = null): self
    {
        $mouvement = self::create([
            'inventaire_id' => $inventaireId,
            'type_mouvement' => 'entree',
            'quantite' => $quantite,
            'motif' => $motif,
            'commentaire' => $commentaire,
            'effectue_par' => $effectuePar ?? auth()->id(),
        ]);

        // Mettre à jour le stock
        $inventaire = DotationInventaire::find($inventaireId);
        $inventaire->stock_total += $quantite;
        $inventaire->stock_disponible += $quantite;
        $inventaire->save();

        return $mouvement;
    }

    public static function creerSortie(int $inventaireId, int $quantite, string $motif, ?int $attributionId = null, ?string $commentaire = null, ?int $effectuePar = null): self
    {
        $mouvement = self::create([
            'inventaire_id' => $inventaireId,
            'type_mouvement' => 'sortie',
            'quantite' => $quantite,
            'motif' => $motif,
            'commentaire' => $commentaire,
            'effectue_par' => $effectuePar ?? auth()->id(),
            'attribution_id' => $attributionId,
        ]);

        // Mettre à jour le stock
        $inventaire = DotationInventaire::find($inventaireId);
        $inventaire->decrementerStock($quantite);

        return $mouvement;
    }

    public static function creerRetour(int $inventaireId, int $quantite, string $motif, ?int $attributionId = null, ?string $commentaire = null, ?int $effectuePar = null): self
    {
        $mouvement = self::create([
            'inventaire_id' => $inventaireId,
            'type_mouvement' => 'retour',
            'quantite' => $quantite,
            'motif' => $motif,
            'commentaire' => $commentaire,
            'effectue_par' => $effectuePar ?? auth()->id(),
            'attribution_id' => $attributionId,
        ]);

        // Mettre à jour le stock
        $inventaire = DotationInventaire::find($inventaireId);
        $inventaire->incrementerStock($quantite);

        return $mouvement;
    }

    public static function creerAjustement(int $inventaireId, int $quantite, string $motif, ?string $commentaire = null, ?int $effectuePar = null): self
    {
        $mouvement = self::create([
            'inventaire_id' => $inventaireId,
            'type_mouvement' => 'ajustement',
            'quantite' => $quantite,
            'motif' => $motif,
            'commentaire' => $commentaire,
            'effectue_par' => $effectuePar ?? auth()->id(),
        ]);

        // Mettre à jour le stock (quantité peut être positive ou négative)
        $inventaire = DotationInventaire::find($inventaireId);
        $inventaire->stock_total += $quantite;
        $inventaire->stock_disponible += $quantite;
        $inventaire->save();

        return $mouvement;
    }
} 