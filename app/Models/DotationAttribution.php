<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;
use App\Mail\DotationAttributedMail;

class DotationAttribution extends Model
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
        'date_activation',
        'date_suspension',
        'raison_suspension',
        'donnees_specifiques',
    ];

    protected $casts = [
        'date_attribution' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_activation' => 'datetime',
        'date_suspension' => 'datetime',
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

    public function attribuePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attribue_par');
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(DotationMouvementStock::class, 'attribution_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSuspendue($query)
    {
        return $query->where('status', 'suspendue');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('status', 'en_attente');
    }

    public function scopeTerminee($query)
    {
        return $query->where('status', 'terminee');
    }

    public function scopeRetournee($query)
    {
        return $query->where('status', 'retournee');
    }

    public function scopeByType($query, $type)
    {
        return $query->whereHas('inventaire', function ($q) use ($type) {
            $q->where('type_dotation', $type);
        });
    }

    /**
     * Accesseurs
     */
    public function getEstActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getEstSuspendueAttribute(): bool
    {
        return $this->status === 'suspendue';
    }

    public function getEstExpireAttribute(): bool
    {
        return $this->date_fin && $this->date_fin < now();
    }

    /**
     * Méthodes utilitaires
     */
    public function activer(): void
    {
        $previousStatus = $this->status;
        $this->status = 'active';
        $this->date_activation = now();
        $this->save();

        // Envoyer un email de notification si le statut change de "en_attente" à "active"
        if ($previousStatus === 'en_attente') {
            try {
                Mail::to($this->bachelier->email_eleve)
                    ->send(new DotationAttributedMail($this, $this->bachelier));
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de dotation', [
                    'dotation_id' => $this->id,
                    'bachelier_id' => $this->bachelier_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function suspendre(string $raison = null): void
    {
        $this->status = 'suspendue';
        $this->date_suspension = now();
        $this->raison_suspension = $raison;
        $this->save();
    }

    public function terminer(): void
    {
        $this->status = 'terminee';
        $this->save();
        
        // Remettre le stock disponible
        $this->inventaire->incrementerStock();
    }

    public function retourner(): void
    {
        $this->status = 'retournee';
        $this->save();
        
        // Remettre le stock disponible
        $this->inventaire->incrementerStock();
    }
} 