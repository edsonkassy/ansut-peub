<?php

namespace App\Console\Commands;

use App\Helpers\PeubScoringHelper;
use App\Models\Bachelier;
use Illuminate\Console\Command;

/**
 * Recalcule mention, score_academique et score_final_peub pour tous les
 * bacheliers dont le profil n'est pas encore vérifié (status_profil !=
 * 'verifie'), suite à la correction du barème des mentions (#8) et des points
 * régionaux (#23).
 *
 * Garde-fou : si le recalcul changerait la mention d'au moins un boursier PEUB
 * (boursier_peub = true), la commande s'arrête AVANT d'écrire quoi que ce soit
 * et liste les bacheliers concernés, pour arbitrage. boursier_peub n'est
 * JAMAIS modifié par cette commande, quel que soit le résultat.
 *
 *   php artisan bacheliers:recalculer-scoring --dry-run
 *   php artisan bacheliers:recalculer-scoring
 *   php artisan bacheliers:recalculer-scoring --force   (ignore le garde-fou, après arbitrage)
 */
class RecalculerScoringBacheliers extends Command
{
    protected $signature = 'bacheliers:recalculer-scoring
                            {--dry-run : Simule le recalcul sans rien écrire}
                            {--force : Ignore le garde-fou boursiers et recalcule quand même}';

    protected $description = "Recalcule mention et scores PEUB après correction du barème (profils non vérifiés uniquement)";

    public function handle(): int
    {
        $bacheliers = Bachelier::where('status_profil', '!=', 'verifie')
            ->whereNotNull('note_bac')
            ->get();

        $this->info("{$bacheliers->count()} bachelier(s) non vérifié(s) à recalculer.");

        // Garde-fou : boursiers PEUB dont la mention changerait
        $boursiersImpactes = [];
        foreach ($bacheliers as $bachelier) {
            if (!$bachelier->boursier_peub) {
                continue;
            }
            $nouvelleMention = Bachelier::calculateMention((float) $bachelier->note_bac, $bachelier->serie_bac);
            if ($nouvelleMention !== $bachelier->mention) {
                $boursiersImpactes[] = $bachelier;
            }
        }

        if (count($boursiersImpactes) > 0 && !$this->option('force')) {
            $this->error(count($boursiersImpactes) . ' boursier(s) PEUB verraient leur mention changer :');
            foreach ($boursiersImpactes as $b) {
                $ancienne = $b->mention ?? 'aucune';
                $nouvelle = Bachelier::calculateMention((float) $b->note_bac, $b->serie_bac) ?? 'aucune';
                $this->line(" - #{$b->id} {$b->nom} {$b->prenoms} ({$b->serie_bac}, {$b->note_bac} pts) : {$ancienne} -> {$nouvelle}");
            }
            $this->warn('Recalcul interrompu, rien n\'a été écrit. boursier_peub n\'est jamais modifié par cette commande.');
            $this->warn('Relancez avec --force pour ignorer ce garde-fou, une fois l\'arbitrage fait.');
            return self::FAILURE;
        }

        if ($this->option('dry-run')) {
            $this->comment('Mode --dry-run : simulation, rien n\'a été écrit.');
            $rows = $bacheliers->map(function ($b) {
                return [
                    $b->id,
                    "{$b->nom} {$b->prenoms}",
                    $b->serie_bac,
                    $b->mention ?? '-',
                    Bachelier::calculateMention((float) $b->note_bac, $b->serie_bac) ?? '-',
                ];
            })->toArray();
            $this->table(['ID', 'Nom', 'Série', 'Ancienne mention', 'Nouvelle mention'], $rows);
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($bacheliers->count());
        $bar->start();
        foreach ($bacheliers as $bachelier) {
            $bachelier->mention = Bachelier::calculateMention((float) $bachelier->note_bac, $bachelier->serie_bac);
            $bachelier->save();
            PeubScoringHelper::calculateAndSaveScore($bachelier);
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        $this->info('Recalcul terminé.');
        return self::SUCCESS;
    }
}
