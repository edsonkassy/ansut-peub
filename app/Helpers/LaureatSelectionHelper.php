<?php

namespace App\Helpers;

use App\Models\Bachelier;
use Illuminate\Support\Facades\Log;

class LaureatSelectionHelper
{
    /**
     * Barème de sélection des lauréats (100 points maximum)
     */
    
    /**
     * Calcule le score d'excellence académique (50 points max)
     */
    public static function calculateExcellenceAcademique(Bachelier $bachelier): int
    {
        $mention = $bachelier->mention;
        
        if ($mention === 'tres_bien') {
            return 50;
        } elseif ($mention === 'bien') {
            return 30;
        } else {
            // Autres mentions (assez_bien, passable) ou pas de mention
            return 10;
        }
    }

    /**
     * Calcule le score handicap (20 points max)
     */
    public static function calculateHandicapScore(Bachelier $bachelier): int
    {
        $situations = $bachelier->situations_particulieres ?? [];
        
        if (is_array($situations) && in_array('handicap', $situations)) {
            return 20;
        }
        
        // Tous les candidats reçoivent au minimum 10 points
        return 10;
    }

    /**
     * Calcule le score orphelinat (20 points max)
     * 
     * NOTE: Cette méthode nécessite le champ situation_orphelinat dans la table
     * Pour l'instant, on utilise situations_particulieres comme fallback
     */
    public static function calculateOrphelinatScore(Bachelier $bachelier): int
    {
        // Vérifier si le champ situation_orphelinat existe (après migration)
        if (isset($bachelier->situation_orphelinat)) {
            switch ($bachelier->situation_orphelinat) {
                case 'pere_et_mere':
                    return 20;
                case 'pere_ou_mere':
                    return 15;
                case 'non':
                default:
                    return 0;
            }
        }
        
        // Fallback: utiliser situations_particulieres
        $situations = $bachelier->situations_particulieres ?? [];
        if (is_array($situations) && in_array('orphelin', $situations)) {
            // Par défaut, considérer comme "père ou mère" (15 points)
            // À améliorer quand le champ dédié sera disponible
            return 15;
        }
        
        return 0;
    }

    /**
     * Calcule le score genre (10 points max)
     */
    public static function calculateGenreScore(Bachelier $bachelier): int
    {
        return $bachelier->sexe === 'F' ? 10 : 5;
    }

    /**
     * Calcule le score total de sélection des lauréats (100 points max)
     */
    public static function calculateLaureatScore(Bachelier $bachelier): array
    {
        $excellence = self::calculateExcellenceAcademique($bachelier);
        $handicap = self::calculateHandicapScore($bachelier);
        $orphelinat = self::calculateOrphelinatScore($bachelier);
        $genre = self::calculateGenreScore($bachelier);
        
        $scoreTotal = $excellence + $handicap + $orphelinat + $genre;
        
        return [
            'score_total' => $scoreTotal,
            'details' => [
                'excellence_academique' => [
                    'points' => $excellence,
                    'mention' => $bachelier->mention,
                ],
                'handicap' => [
                    'points' => $handicap,
                    'has_handicap' => in_array('handicap', $bachelier->situations_particulieres ?? []),
                ],
                'orphelinat' => [
                    'points' => $orphelinat,
                    'status' => $bachelier->situation_orphelinat ?? 'non',
                ],
                'genre' => [
                    'points' => $genre,
                    'sexe' => $bachelier->sexe,
                ],
            ],
        ];
    }

    /**
     * Calcule et sauvegarde le score de sélection des lauréats
     */
    public static function calculateAndSaveLaureatScore(Bachelier $bachelier): array
    {
        try {
            $scoreResult = self::calculateLaureatScore($bachelier);
            
            // Mettre à jour les champs (à créer dans une migration)
            // Désactiver les events temporairement pour éviter la boucle infinie avec l'Observer
            $bachelier->withoutEvents(function () use ($bachelier, $scoreResult) {
                $bachelier->score_selection_laureat = $scoreResult['score_total'];
                $bachelier->details_score_laureat = $scoreResult['details'];
                $bachelier->date_selection_laureat = now();
                $bachelier->save();
            });

            return $scoreResult;
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul du score de sélection lauréat', [
                'bachelier_id' => $bachelier->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}

