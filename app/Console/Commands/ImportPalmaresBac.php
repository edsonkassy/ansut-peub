<?php

namespace App\Console\Commands;

use App\Models\PalmaresBac;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * Charge le palmarès officiel (CSV nettoyé) dans la table palmares_bac.
 * ATTENTION : le CSV contient des données personnelles d'élèves (dont des mineurs) : il n'est PAS
 * versionné dans git. Le déposer directement sur le serveur dans storage/app/imports/.
 * Idempotent : relançable sans doublons (upsert sur le matricule).
 *
 *   php artisan palmares:import --dry-run
 *   php artisan palmares:import
 */
class ImportPalmaresBac extends Command
{
    protected $signature = 'palmares:import
                            {file=storage/app/imports/palmares_bac_2026.csv : Chemin du CSV (relatif à la racine du projet)}
                            {--dry-run : Valide le fichier sans rien écrire}';

    protected $description = 'Importe le palmarès officiel des meilleurs bacheliers (référentiel de vérification)';

    public function handle(): int
    {
        $path = base_path($this->argument('file'));
        if (!is_readable($path)) {
            $this->error("Fichier introuvable : {$path}");
            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $rows = [];
        $errors = [];
        $line = 1;

        while (($raw = fgetcsv($handle)) !== false) {
            $line++;
            if (count($raw) !== count($header)) {
                $errors[] = "Ligne {$line} : nombre de colonnes incorrect";
                continue;
            }
            $data = array_combine($header, $raw);
            $data['matricule'] = PalmaresBac::normalizeMatricule($data['matricule']);
            $data['mention'] = $data['mention'] === '' ? null : $data['mention'];

            $v = Validator::make($data, [
                'matricule' => 'required|string|max:20',
                'nom' => 'required|string',
                'prenoms' => 'required|string',
                'date_naissance' => 'required|date_format:Y-m-d',
                'drena' => 'required|string',
                'sexe' => 'required|in:M,F',
                'serie' => 'required|string|max:10',
                'mention' => 'nullable|in:ims,passable,assez_bien,bien,tres_bien',
                'points' => 'required|integer|min:0|max:400',
                'rang_drena' => 'required|integer|min:1',
                'annee_bac' => 'required|integer|min:2000|max:2100',
            ]);

            if ($v->fails()) {
                $errors[] = "Ligne {$line} ({$data['matricule']}) : " . implode(' | ', $v->errors()->all());
                continue;
            }

            $now = now();
            $rows[] = $data + ['created_at' => $now, 'updated_at' => $now];
        }
        fclose($handle);

        $this->info(count($rows) . ' lignes valides, ' . count($errors) . ' en erreur.');
        foreach ($errors as $e) {
            $this->line(" - {$e}");
        }

        if ($this->option('dry-run')) {
            $this->comment('Simulation : rien n\'a été écrit.');
            return self::SUCCESS;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            PalmaresBac::upsert(
                $chunk,
                ['matricule'],
                ['nom', 'prenoms', 'date_naissance', 'lieu_naissance', 'drena', 'sexe', 'serie',
                 'mention', 'etablissement', 'points', 'rang_drena', 'annee_bac', 'updated_at']
            );
        }

        $this->info('Import terminé : ' . PalmaresBac::count() . ' bacheliers en base.');
        return self::SUCCESS;
    }
}
