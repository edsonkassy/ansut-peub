<?php

namespace App\Console\Commands;

use App\Models\Bachelier;
use App\Jobs\ProcessAiExtraction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessPendingAiExtractions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:process-pending {--limit=50 : Nombre maximum de candidatures à traiter} {--dry-run : Afficher seulement ce qui serait traité}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Traite les extractions IA en attente pour les candidatures PEUB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');
        $dryRun = $this->option('dry-run');

        $this->info("Recherche des candidatures sans extraction IA...");

        // Trouver les bacheliers sans extraction IA
        $pendingBacheliers = Bachelier::whereNull('ai_extraction_completed_at')
            ->whereNotNull('piece_identite_file')
            ->whereNotNull('collante_bac_file')
            ->whereNotNull('motivation')
            ->limit($limit)
            ->get();

        if ($pendingBacheliers->isEmpty()) {
            $this->info("Aucune candidature en attente d'extraction IA trouvée.");
            return 0;
        }

        $this->info("Trouvé {$pendingBacheliers->count()} candidature(s) en attente d'extraction IA.");

        if ($dryRun) {
            $this->warn("Mode DRY-RUN activé - Aucun traitement ne sera effectué.");
            $this->table(
                ['ID', 'Nom', 'Email', 'Région', 'Série BAC'],
                $pendingBacheliers->map(function ($bachelier) {
                    return [
                        $bachelier->id,
                        $bachelier->prenoms . ' ' . $bachelier->nom,
                        $bachelier->email_eleve,
                        $bachelier->region,
                        $bachelier->serie_bac
                    ];
                })
            );
            return 0;
        }

        $bar = $this->output->createProgressBar($pendingBacheliers->count());
        $bar->start();

        $processed = 0;
        $errors = 0;

        foreach ($pendingBacheliers as $bachelier) {
            try {
                $aiData = [
                    'piece_identite_file' => $bachelier->piece_identite_file,
                    'collante_bac_file' => $bachelier->collante_bac_file,
                    'motivation' => $bachelier->motivation,
                    'region' => $bachelier->region,
                    'serie_bac' => $bachelier->serie_bac,
                    'note_bac' => $bachelier->note_bac,
                    'mention' => $bachelier->mention,
                    'situations_particulieres' => $bachelier->situations_particulieres ?? []
                ];

                ProcessAiExtraction::dispatch($bachelier->id, $aiData);
                $processed++;

                Log::info('Job d\'extraction IA dispatché via commande', [
                    'bachelier_id' => $bachelier->id,
                    'email' => $bachelier->email_eleve
                ]);

            } catch (\Exception $e) {
                $errors++;
                Log::error('Erreur lors du dispatch du job d\'extraction IA', [
                    'bachelier_id' => $bachelier->id,
                    'error' => $e->getMessage()
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Traitement terminé :");
        $this->info("- {$processed} job(s) dispatché(s) avec succès");
        if ($errors > 0) {
            $this->error("- {$errors} erreur(s) rencontrée(s)");
        }

        return 0;
    }
}
