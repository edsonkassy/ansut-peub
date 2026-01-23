<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DotationInventaire extends Model
{
    use HasFactory;

    protected $table = 'dotations_inventaire';

    protected $fillable = [
        'nom',
        'code_interne',
        'type_dotation',
        'description',
        'valeur_unitaire',
        'prix_mensuel',
        'stock_total',
        'stock_disponible',
        'stock_attribue',
        'stock_minimum',
        'fournisseur_id',
        'date_achat',
        'marque',
        'modele',
        'caracteristiques',
        'duree_validite',
        'status',
        'metadata',
    ];

    protected $casts = [
        'date_achat' => 'date',
        'metadata' => 'array',
        'valeur_unitaire' => 'decimal:2',
        'prix_mensuel' => 'decimal:2',
    ];

    /**
     * Relations
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(DotationFournisseur::class, 'fournisseur_id');
    }

    public function attributions(): HasMany
    {
        return $this->hasMany(DotationAttribution::class, 'inventaire_id');
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(DotationMouvementStock::class, 'inventaire_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type_dotation', $type);
    }

    public function scopeStockFaible($query)
    {
        return $query->whereColumn('stock_disponible', '<=', 'stock_minimum');
    }

    public function scopeDisponible($query)
    {
        return $query->where('stock_disponible', '>', 0);
    }

    /**
     * Accesseurs
     */
    public function getEstDisponibleAttribute(): bool
    {
        return $this->stock_disponible > 0;
    }

    public function getEstStockFaibleAttribute(): bool
    {
        return $this->stock_disponible <= $this->stock_minimum;
    }

    /**
     * Méthodes utilitaires
     */
    public function decrementerStock(int $quantite = 1): void
    {
        $this->stock_disponible -= $quantite;
        $this->stock_attribue += $quantite;
        $this->save();
    }

    public function incrementerStock(int $quantite = 1): void
    {
        $this->stock_disponible += $quantite;
        $this->stock_attribue -= $quantite;
        $this->save();
    }

    public function hasBeenAttributedTo(int $bachelierId): bool
    {
        return $this->attributions()->where('bachelier_id', $bachelierId)->exists();
    }
} 