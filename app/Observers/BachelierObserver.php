<?php

namespace App\Observers;

use App\Models\Bachelier;
use App\Services\BachelierScoringService;
use Illuminate\Support\Facades\Log;

class BachelierObserver
{
    /**
     * Champs critiques qui nécessitent un recalcul des scores
     */
    private array $criticalFields = [
        'mention',
        'note_bac',
        'serie_bac',
        'situations_particulieres',
        'situation_orphelinat',
        'sexe',
        'region',
    ];

    /**
     * Handle the Bachelier "created" event.
     */
    public function created(Bachelier $bachelier): void
    {
        // Calculer les scores après création du profil complet
        $this->calculateScores($bachelier, 'created');
    }

    /**
     * Handle the Bachelier "updated" event.
     */
    public function updated(Bachelier $bachelier): void
    {
        // Vérifier si des champs critiques ont été modifiés
        $hasCriticalChanges = false;
        
        foreach ($this->criticalFields as $field) {
            if ($bachelier->wasChanged($field)) {
                $hasCriticalChanges = true;
                break;
            }
        }

        // Recalculer les scores uniquement si des champs critiques ont changé
        if ($hasCriticalChanges) {
            $this->calculateScores($bachelier, 'updated');
        }
    }

    /**
     * Calcule les scores pour un bachelier
     */
    private function calculateScores(Bachelier $bachelier, string $event): void
    {
        try {
            $scoringService = app(BachelierScoringService::class);
            $result = $scoringService->calculateAllScores($bachelier);

            if ($result['success']) {
                Log::info("Score calculé automatiquement après {$event} (barème 100 points)", [
                    'bachelier_id' => $bachelier->id,
                    'matricule' => $bachelier->matricule_bac,
                    'score_total' => $result['score']['score_total'] ?? null,
                ]);
            } else {
                Log::warning("Erreurs lors du calcul automatique des scores après {$event}", [
                    'bachelier_id' => $bachelier->id,
                    'matricule' => $bachelier->matricule_bac,
                    'errors' => $result['errors'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Exception lors du calcul automatique des scores après {$event}", [
                'bachelier_id' => $bachelier->id,
                'matricule' => $bachelier->matricule_bac,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Ne pas faire échouer l'opération si le calcul de score échoue
        }
    }

    /**
     * Handle the Bachelier "deleted" event.
     */
    public function deleted(Bachelier $bachelier): void
    {
        //
    }

    /**
     * Handle the Bachelier "restored" event.
     */
    public function restored(Bachelier $bachelier): void
    {
        //
    }

    /**
     * Handle the Bachelier "force deleted" event.
     */
    public function forceDeleted(Bachelier $bachelier): void
    {
        //
    }
}
