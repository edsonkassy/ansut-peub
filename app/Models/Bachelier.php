<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bachelier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'prenoms',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'piece_identite_type',
        'piece_identite_file',
        'piece_identite_extracted_data',
        'telephone_eleve',
        'telephone_parent',
        'email_eleve',
        'email_parent',
        'region',
        'commune',
        'matricule_bac',
        'serie_bac',
        'note_bac',
        'mention',
        'etablissement_nom',
        'etablissement_type',
        'annee_bac',
        'collante_bac_file',
        'collante_bac_extracted_data',
        'pensionnaire_internat',
        'bourse_scolaire_lycee',
        'profession_pere',
        'profession_mere',
        'situations_particulieres',
        'possede_ordinateur',
        'connexion_internet',
        'acces_smartphone',
        'acces_ia',
        'motivation',
        'projet_professionnel',
        'motivation_ai_score',
        'motivation_ai_analysis',
        'competences',
        'langues',
        'photo',
        'cv_path',
        'photo_profil',
        'boursier_peub',
        'date_integration_peub',
        'status_candidature',
        'status_profil',
        'date_verification',
        'bio',
        'ai_extraction_completed_at',
        'ai_model_used',
        'ai_extraction_metadata',
        'score_academique',
        'score_geographique',
        'score_socio_economique',
        'score_motivations',
        'score_final_peub',
        'rang_peub',
        'details_scoring',
        'date_calcul_scoring',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'annee_bac' => 'integer',
        'note_bac' => 'decimal:2',
        'pensionnaire_internat' => 'boolean',
        'bourse_scolaire_lycee' => 'boolean',
        'situations_particulieres' => 'array',
        'possede_ordinateur' => 'boolean',
        'competences' => 'array',
        'langues' => 'array',
        'boursier_peub' => 'boolean',
        'date_integration_peub' => 'date',
        'date_verification' => 'date',
        'score_academique' => 'decimal:2',
        'score_geographique' => 'decimal:2',
        'score_socio_economique' => 'decimal:2',
        'score_motivations' => 'decimal:2',
        'score_final_peub' => 'decimal:2',
        'details_scoring' => 'array',
        'date_calcul_scoring' => 'datetime',
        'piece_identite_extracted_data' => 'array',
        'collante_bac_extracted_data' => 'array',
        'motivation_ai_analysis' => 'array',
        'ai_extraction_completed_at' => 'datetime',
        'ai_extraction_metadata' => 'array',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    public function favoris(): HasMany
    {
        return $this->hasMany(Favori::class);
    }

    public function dotations(): HasMany
    {
        return $this->hasMany(Dotation::class);
    }

    public function dotationsAttributions(): HasMany
    {
        return $this->hasMany(DotationAttribution::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function alertes(): HasMany
    {
        return $this->hasMany(Alerte::class);
    }

    public function interactionsIa(): HasMany
    {
        return $this->hasMany(InteractionIa::class);
    }

    public function parcoursUniversitaires(): HasMany
    {
        return $this->hasMany(ParcoursUniversitaire::class);
    }

    /**
     * Calculer la mention du BAC basée sur la note (système ivoirien sur 400 points)
     * 
     * Barème Côte d'Ivoire:
     * - Passable: 240-279 points (60-69.75%)
     * - Assez Bien: 280-319 points (70-79.75%)
     * - Bien: 320-359 points (80-89.75%)
     * - Très Bien: 360-400 points (90-100%)
     * 
     * @param float $note La note sur 400 points
     * @return string|null La mention ou null si note < 240
     */
    public static function calculateMention(float $note): ?string
    {
        if ($note < 240) {
            return null; // Pas de mention en dessous de 240
        } elseif ($note < 280) {
            return 'passable';
        } elseif ($note < 320) {
            return 'assez_bien';
        } elseif ($note < 360) {
            return 'bien';
        } else {
            return 'tres_bien';
        }
    }

    /**
     * Scopes
     */
    public function scopeBoursiers($query)
    {
        return $query->where('boursier_peub', true);
    }

    public function scopeNonBoursiers($query)
    {
        return $query->where('boursier_peub', false);
    }

    public function scopeActifs($query)
    {
        return $query->whereHas('user', function ($query) {
            $query->where('status', 'active');
        });
    }

    public function scopeVerifies($query)
    {
        return $query->where('status_profil', 'verifie');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('status_candidature', 'en_attente');
    }

    public function scopeAcceptes($query)
    {
        return $query->where('status_candidature', 'accepte');
    }

    /**
     * Accessors
     */
    public function getNomCompletAttribute()
    {
        return $this->nom . ' ' . $this->prenoms;
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getStatutPeubTextAttribute()
    {
        return $this->boursier_peub ? 'Boursier PEUB' : 'Candidat Standard';
    }

    public function getStatutCandidatureTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status_candidature));
    }

    public function getStatutProfilTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status_profil));
    }

    /**
     * Methods
     */
    public function hasActiveDotations()
    {
        return $this->dotationsAttributions()->where('status', 'active')->exists();
    }

    public function getActiveDotations()
    {
        return $this->dotationsAttributions()->where('status', 'active')->with('inventaire.fournisseur')->get();
    }

    public function getDotationsByType(string $type)
    {
        return $this->dotationsAttributions()
            ->whereHas('inventaire', function($query) use ($type) {
                $query->where('type_dotation', $type);
            })
            ->with('inventaire.fournisseur')
            ->get();
    }

    public function getActiveDotationsByType(string $type)
    {
        return $this->dotationsAttributions()
            ->where('status', 'active')
            ->whereHas('inventaire', function($query) use ($type) {
                $query->where('type_dotation', $type);
            })
            ->with('inventaire.fournisseur')
            ->get();
    }

    public function hasActiveDotationOfType(string $type): bool
    {
        return $this->dotationsAttributions()
            ->where('status', 'active')
            ->whereHas('inventaire', function($query) use ($type) {
                $query->where('type_dotation', $type);
            })
            ->exists();
    }

    public function hasOrdinateurPortable(): bool
    {
        return $this->hasActiveDotationOfType('ordinateur_portable');
    }

    public function hasConnexionInternet(): bool
    {
        return $this->hasActiveDotationOfType('connexion_internet');
    }

    public function hasAbonnementIA(): bool
    {
        return $this->hasActiveDotationOfType('abonnement_ia');
    }

    public function attribuerDotation(int $inventaireId, array $donnees = []): DotationAttribution
    {
        return DotationAttribution::create([
            'bachelier_id' => $this->id,
            'inventaire_id' => $inventaireId,
            'identifiant_unique' => $donnees['identifiant_unique'] ?? null,
            'date_attribution' => $donnees['date_attribution'] ?? now(),
            'date_debut' => $donnees['date_debut'] ?? null,
            'date_fin' => $donnees['date_fin'] ?? null,
            'status' => $donnees['status'] ?? 'en_attente',
            'attribue_par' => $donnees['attribue_par'] ?? auth()->id(),
            'donnees_specifiques' => $donnees['donnees_specifiques'] ?? [],
        ]);
    }

    public function suspendreToutesDotationsOfType(string $type, string $raison = null): int
    {
        $dotations = $this->dotationsAttributions()
            ->where('status', 'active')
            ->whereHas('inventaire', function($query) use ($type) {
                $query->where('type_dotation', $type);
            })
            ->get();

        $count = 0;
        foreach ($dotations as $dotation) {
            $dotation->suspendre($raison);
            $count++;
        }

        return $count;
    }

    public function reactiverToutesDotationsOfType(string $type): int
    {
        $dotations = $this->dotationsAttributions()
            ->where('status', 'suspendue')
            ->whereHas('inventaire', function($query) use ($type) {
                $query->where('type_dotation', $type);
            })
            ->get();

        $count = 0;
        foreach ($dotations as $dotation) {
            $dotation->activer();
            $count++;
        }

        return $count;
    }

    public function canApplyToOpportunity($opportunite)
    {
        // Vérifier si l'opportunité est publiée
        if ($opportunite->status !== 'published') {
            return false;
        }

        // Vérifier la date limite
        if ($opportunite->date_limite_candidature && $opportunite->date_limite_candidature->isPast()) {
            return false;
        }

        // Vérifier qu'il n'a pas déjà postulé
        if ($this->candidatures()->where('opportunite_id', $opportunite->id)->exists()) {
            return false;
        }

        return true;
    }

    public function getMatchingScore($opportunite)
    {
        $score = 50; // Score de base

        // Vérifier la série BAC
        if ($opportunite->series_acceptees && in_array($this->serie_bac, $opportunite->series_acceptees)) {
            $score += 20;
        }

        // Vérifier la moyenne
        if ($opportunite->moyenne_minimum && $this->note_bac >= $opportunite->moyenne_minimum) {
            $score += 15;
        }

        // Vérifier la région
        if ($opportunite->regions_ciblees && in_array($this->region, $opportunite->regions_ciblees)) {
            $score += 10;
        }

        // Bonus pour les boursiers PEUB
        if ($this->boursier_peub) {
            $score += 5;
        }

        return min($score, 100);
    }

    public function isEligibleForPeub()
    {
        // Critères d'éligibilité pour PEUB
        $criteria = [
            'has_bac' => $this->annee_bac && $this->annee_bac >= 2020,
            'good_grades' => $this->note_bac && $this->note_bac >= 12,
            'financial_need' => $this->situations_particulieres && in_array('situation_financiere_difficile', $this->situations_particulieres),
            'no_computer' => !$this->possede_ordinateur,
            'no_internet' => $this->connexion_internet === 'aucune',
        ];

        return $criteria;
    }

    public function getPeubEligibilityScore()
    {
        $criteria = $this->isEligibleForPeub();
        $score = 0;

        if ($criteria['has_bac']) $score += 20;
        if ($criteria['good_grades']) $score += 25;
        if ($criteria['financial_need']) $score += 30;
        if ($criteria['no_computer']) $score += 15;
        if ($criteria['no_internet']) $score += 10;

        return $score;
    }

    /**
     * Méthodes pour le scoring PEUB
     */
    public function calculatePeubScore()
    {
        return \App\Helpers\PeubScoringHelper::calculateAndSaveScore($this);
    }

    public function getPeubScoreDetails()
    {
        return $this->details_scoring;
    }

    public function getPeubScoreBreakdown()
    {
        if (!$this->details_scoring) {
            return null;
        }

        return [
            'academique' => [
                'score' => $this->score_academique,
                'pourcentage' => '30%',
                'details' => $this->details_scoring['academique']['details'] ?? []
            ],
            'geographique' => [
                'score' => $this->score_geographique,
                'pourcentage' => '30%',
                'details' => $this->details_scoring['geographique']['details'] ?? []
            ],
            'socio_economique' => [
                'score' => $this->score_socio_economique,
                'pourcentage' => '30%',
                'details' => $this->details_scoring['socio_economique']['details'] ?? []
            ],
            'motivations' => [
                'score' => $this->score_motivations,
                'pourcentage' => '10%',
                'details' => $this->details_scoring['motivations']['details'] ?? []
            ],
            'final' => [
                'score' => $this->score_final_peub,
                'rang' => $this->rang_peub
            ]
        ];
    }

    public function isInTop2000()
    {
        return $this->rang_peub && $this->rang_peub <= 2000;
    }

    public function getPeubStatus()
    {
        if ($this->boursier_peub) {
            return 'Boursier PEUB';
        }

        if ($this->isInTop2000()) {
            return 'Éligible PEUB';
        }

        return 'Candidat Standard';
    }
} 