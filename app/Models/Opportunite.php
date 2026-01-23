<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opportunite extends Model
{
    use HasFactory;

    protected $fillable = [
        'partenaire_id',
        'titre',
        'type',
        'description',
        'illustration',
        'competences_requises',
        'criteres_eligibilite',
        'pays',
        'ville',
        'duree',
        'remuneration',
        'date_debut',
        'date_fin',
        'date_limite_candidature',
        'nombre_places',
        'niveau_etude_requis',
        'series_acceptees',
        'moyenne_minimum',
        'regions_ciblees',
        'documents_requis',
        'contact_email',
        'contact_telephone',
        'lien_externe',
        'status',
        'vues',
        'candidatures_count'
    ];

    protected $casts = [
        'competences_requises' => 'array',
        'criteres_eligibilite' => 'array',
        'series_acceptees' => 'array',
        'regions_ciblees' => 'array',
        'documents_requis' => 'array',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_limite_candidature' => 'date',
        'moyenne_minimum' => 'decimal:2'
    ];

    /**
     * Relations
     */
    public function partenaire(): BelongsTo
    {
        return $this->belongsTo(Partenaire::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    public function favoris(): HasMany
    {
        return $this->hasMany(Favori::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                    ->where('date_limite_candidature', '>=', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mutators & Accessors
     */
    public function incrementVues()
    {
        $this->increment('vues');
    }

    public function incrementCandidatures()
    {
        $this->increment('candidatures_count');
    }

    /**
     * Check if opportunity is favorited by a specific bachelier
     */
    public function isFavoritedBy(Bachelier $bachelier): bool
    {
        return $this->favoris()->where('bachelier_id', $bachelier->id)->exists();
    }

    /**
     * Check if a bachelier can apply to this opportunity
     */
    public function canApply(Bachelier $bachelier): bool
    {
        // Check if already applied
        $alreadyApplied = $this->candidatures()->where('bachelier_id', $bachelier->id)->exists();
        
        // Check if deadline has passed
        $deadlinePassed = $this->date_limite_candidature < now();
        
        // Check if opportunity is active
        $isActive = $this->status === 'published';
        
        return !$alreadyApplied && !$deadlinePassed && $isActive;
    }

    /**
     * Calculate AI score for a bachelier
     */
    public function calculateAIScore(Bachelier $bachelier): int
    {
        $score = 0;
        
        // Base score
        $score += 30;
        
        // Check competences match
        if ($bachelier->competences && $this->competences_requises) {
            $bachelierCompetences = is_array($bachelier->competences) ? $bachelier->competences : explode(',', $bachelier->competences);
            $requiredCompetences = is_array($this->competences_requises) ? $this->competences_requises : explode(',', $this->competences_requises);
            
            $bachelierCompetences = array_map('strtolower', $bachelierCompetences);
            $requiredCompetences = array_map('strtolower', $requiredCompetences);
            
            $matchingCompetences = array_intersect($bachelierCompetences, $requiredCompetences);
            $score += count($matchingCompetences) * 10;
        }
        
        // Location match
        if ($bachelier->region && $this->ville && strtolower($bachelier->region) === strtolower($this->ville)) {
            $score += 10;
        }
        
        return min(100, $score);
    }
} 