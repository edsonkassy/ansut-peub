<?php

namespace App\Jobs;

use App\Models\Bachelier;
use App\Services\AiExtractionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAiExtraction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes
    public $tries = 3;
    public $backoff = [60, 180, 300]; // Retry delays

    protected $bachelierId;
    protected $aiData;

    /**
     * Create a new job instance.
     */
    public function __construct(int $bachelierId, array $aiData)
    {
        $this->bachelierId = $bachelierId;
        $this->aiData = $aiData;
    }

    /**
     * Execute the job.
     */
    public function handle(AiExtractionService $aiService): void
    {
        try {
            $bachelier = Bachelier::findOrFail($this->bachelierId);
            
            Log::info('Début du traitement IA pour le bachelier', [
                'bachelier_id' => $this->bachelierId,
                'email' => $bachelier->email_eleve
            ]);

            // Traitement IA
            $aiResults = $aiService->processBachelierData($this->aiData);

            // Mise à jour du bachelier avec les données extraites
            $updateData = [
                'ai_extraction_completed_at' => now(),
                'ai_model_used' => 'gpt-4o-mini',
                'ai_extraction_metadata' => [
                    'overall_success' => $aiResults['overall_success'],
                    'tokens_used' => ($aiResults['identity_extraction']['tokens_used'] ?? 0) + 
                                   ($aiResults['bac_extraction']['tokens_used'] ?? 0) + 
                                   ($aiResults['motivation_analysis']['tokens_used'] ?? 0),
                    'processed_via_job' => true,
                    'job_id' => $this->job->getJobId()
                ]
            ];

            // Ajouter les données extraites si disponibles
            if ($aiResults['identity_extraction']['success'] ?? false) {
                $updateData['piece_identite_extracted_data'] = $aiResults['identity_extraction']['extracted_data'];
            }

            if ($aiResults['bac_extraction']['success'] ?? false) {
                $updateData['collante_bac_extracted_data'] = $aiResults['bac_extraction']['extracted_data'];
            }

            if ($aiResults['motivation_analysis']['success'] ?? false) {
                $updateData['motivation_ai_score'] = $aiResults['motivation_analysis']['score'];
                $updateData['motivation_ai_analysis'] = $aiResults['motivation_analysis']['analysis'];
            }

            $bachelier->update($updateData);

            Log::info('Traitement IA terminé avec succès', [
                'bachelier_id' => $this->bachelierId,
                'overall_success' => $aiResults['overall_success'],
                'identity_success' => $aiResults['identity_extraction']['success'] ?? false,
                'bac_success' => $aiResults['bac_extraction']['success'] ?? false,
                'motivation_success' => $aiResults['motivation_analysis']['success'] ?? false
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement IA en arrière-plan', [
                'bachelier_id' => $this->bachelierId,
                'error' => $e->getMessage(),
                'job_id' => $this->job->getJobId()
            ]);

            // Marquer le bachelier comme ayant eu une erreur d'extraction
            try {
                $bachelier = Bachelier::find($this->bachelierId);
                if ($bachelier) {
                    $bachelier->update([
                        'ai_extraction_metadata' => [
                            'overall_success' => false,
                            'error' => $e->getMessage(),
                            'processed_via_job' => true,
                            'job_id' => $this->job->getJobId(),
                            'failed_at' => now()
                        ]
                    ]);
                }
            } catch (\Exception $updateError) {
                Log::error('Impossible de mettre à jour le statut d\'erreur', [
                    'bachelier_id' => $this->bachelierId,
                    'error' => $updateError->getMessage()
                ]);
            }

            throw $e; // Relancer l'exception pour permettre les retries
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job d\'extraction IA définitivement échoué', [
            'bachelier_id' => $this->bachelierId,
            'error' => $exception->getMessage(),
            'job_id' => $this->job->getJobId()
        ]);

        // Marquer le bachelier comme ayant eu un échec définitif
        try {
            $bachelier = Bachelier::find($this->bachelierId);
            if ($bachelier) {
                $bachelier->update([
                    'ai_extraction_metadata' => [
                        'overall_success' => false,
                        'error' => $exception->getMessage(),
                        'processed_via_job' => true,
                        'job_id' => $this->job->getJobId(),
                        'failed_at' => now(),
                        'permanently_failed' => true
                    ]
                ]);
            }
        } catch (\Exception $updateError) {
            Log::error('Impossible de mettre à jour le statut d\'échec définitif', [
                'bachelier_id' => $this->bachelierId,
                'error' => $updateError->getMessage()
            ]);
        }
    }
}
