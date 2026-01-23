<?php

namespace App\Services;

use App\Models\Bachelier;
use App\Helpers\LaureatSelectionHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BachelierScoringService
{
    /**
     * Calcule et sauvegarde tous les scores d'un bachelier
     * 
     * Cette méthode est appelée automatiquement après :
     * - La création complète du profil bachelier
     * - La mise à jour des informations importantes (mention, situations particulières, etc.)
     * 
     * @param Bachelier $bachelier
     * @param bool $forceRecalculate Force le recalcul même si les scores existent déjà
     * @return array Retourne un tableau avec les résultats du calcul
     */
    public function calculateAllScores(Bachelier $bachelier, bool $forceRecalculate = false): array
    {
        try {
            DB::beginTransaction();

            $results = [
                'peub_score' => null,
                'laureat_score' => null,
                'success' => false,
                'errors' => [],
            ];

            // Vérifier si le profil est suffisamment complet pour calculer les scores
            if (!$this->isProfileCompleteForScoring($bachelier)) {
                $results['errors'][] = 'Le profil n\'est pas suffisamment complet pour calculer les scores';
                DB::rollBack();
                return $results;
            }

            // Calculer le score sur 100 points (barème lauréat)
            // Excellence académique (50pts) + Handicap (20pts) + Orphelinat (20pts) + Genre (10pts)
            try {
                if ($forceRecalculate || is_null($bachelier->score_final_peub)) {
                    // Utiliser le barème sur 100 points
                    $scoreResult = LaureatSelectionHelper::calculateLaureatScore($bachelier);
                    
                    // Extraire les détails AVANT la closure pour pouvoir les réutiliser après
                    $details = $scoreResult['details'];
                    
                    // Sauvegarder dans les champs existants (4 composantes séparées)
                    $bachelier->withoutEvents(function () use ($bachelier, $scoreResult, $details) {
                        // Répartition des 4 composantes dans les 4 champs disponibles
                        $bachelier->score_academique = $details['excellence_academique']['points']; // /50
                        $bachelier->score_geographique = $details['handicap']['points']; // /20
                        $bachelier->score_socio_economique = $details['orphelinat']['points']; // /20
                        $bachelier->score_motivations = $details['genre']['points']; // /10
                        $bachelier->score_final_peub = $scoreResult['score_total'];
                        $bachelier->details_scoring = json_encode($scoreResult['details']);
                        $bachelier->date_calcul_scoring = now();
                        $bachelier->save();
                    });
                    
                    $bachelier->refresh();
                    $results['score'] = [
                        'score_total' => $bachelier->score_final_peub,
                        'composantes' => [
                            'excellence_academique' => $details['excellence_academique']['points'],
                            'handicap' => $details['handicap']['points'],
                            'orphelinat' => $details['orphelinat']['points'],
                            'genre' => $details['genre']['points'],
                        ],
                        'calculated_at' => $bachelier->date_calcul_scoring,
                    ];
                    
                    Log::info('Score calculé avec succès (barème 100 points)', [
                        'bachelier_id' => $bachelier->id,
                        'score_total' => $bachelier->score_final_peub,
                        'details' => $scoreResult['details'],
                    ]);
                } else {
                    $results['score'] = [
                        'score' => $bachelier->score_final_peub,
                        'message' => 'Score déjà calculé (utiliser forceRecalculate=true pour recalculer)',
                    ];
                }
            } catch (\Exception $e) {
                $errorMsg = 'Erreur lors du calcul du score: ' . $e->getMessage();
                $results['errors'][] = $errorMsg;
                Log::error($errorMsg, [
                    'bachelier_id' => $bachelier->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            DB::commit();
            $results['success'] = empty($results['errors']);

            return $results;

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMsg = 'Erreur générale lors du calcul des scores: ' . $e->getMessage();
            $results['errors'][] = $errorMsg;
            Log::error($errorMsg, [
                'bachelier_id' => $bachelier->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $results;
        }
    }

    /**
     * Vérifie si le profil est suffisamment complet pour calculer les scores
     */
    private function isProfileCompleteForScoring(Bachelier $bachelier): bool
    {
        // Vérifications minimales requises
        $requiredFields = [
            'nom',
            'prenoms',
            'date_naissance',
            'sexe',
            'region',
            'matricule_bac',
            'serie_bac',
            'note_bac',
            'mention',
        ];

        foreach ($requiredFields as $field) {
            if (is_null($bachelier->$field) || $bachelier->$field === '') {
                return false;
            }
        }

        return true;
    }

    /**
     * Vérifie si les champs de score lauréat existent dans la base de données
     */
    private function hasLaureatScoreFields(Bachelier $bachelier): bool
    {
        try {
            // Essayer d'accéder au champ pour voir s'il existe
            $test = $bachelier->getAttribute('score_selection_laureat');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * Recalcule les scores pour tous les bacheliers éligibles
     * Utile pour une mise à jour en masse après un changement de barème
     */
    public function recalculateAllScores(): array
    {
        $results = [
            'total' => 0,
            'success' => 0,
            'errors' => 0,
            'details' => [],
        ];

        $bacheliers = Bachelier::where('status_profil', 'verifie')
            ->whereNotNull('mention')
            ->whereNotNull('note_bac')
            ->get();

        foreach ($bacheliers as $bachelier) {
            $results['total']++;
            $scoreResult = $this->calculateAllScores($bachelier, true);
            
            if ($scoreResult['success']) {
                $results['success']++;
            } else {
                $results['errors']++;
                $results['details'][] = [
                    'bachelier_id' => $bachelier->id,
                    'matricule' => $bachelier->matricule_bac,
                    'errors' => $scoreResult['errors'],
                ];
            }
        }

        return $results;
    }
}

