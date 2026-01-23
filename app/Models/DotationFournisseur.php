<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DotationFournisseur extends Model
{
    use HasFactory;

    protected $table = 'dotations_fournisseurs';

    protected $fillable = [
        'nom',
        'contact_email',
        'contact_telephone',
        'status',
        'contrat_url',
    ];

    /**
     * Obtenir les articles d'inventaire fournis par ce fournisseur.
     */
    public function inventaire()
    {
        return $this->hasMany(DotationInventaire::class, 'fournisseur_id');
    }

    /**
     * Relations
     */
    public function inventaires(): HasMany
    {
        return $this->hasMany(DotationInventaire::class, 'fournisseur_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSuspendu($query)
    {
        return $query->where('status', 'suspendu');
    }

    public function scopeArchive($query)
    {
        return $query->where('status', 'archive');
    }
} 