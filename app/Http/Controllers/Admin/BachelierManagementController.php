<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bachelier;
use App\Models\User;
use App\Models\DotationInventaire;
use App\Models\Dotation;
use App\Mail\BachelierCandidatureApprovedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class BachelierManagementController extends Controller
{
    /**
     * Afficher la liste des bacheliers
     */
    public function index(Request $request)
    {
        $query = Bachelier::with('user');
        
        // Filtres
        if ($request->filled('status_profil')) {
            $query->where('status_profil', $request->status_profil);
        }
        
        if ($request->filled('boursier_peub')) {
            $query->where('boursier_peub', $request->boursier_peub == '1');
        }
        
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        
        if ($request->filled('serie_bac')) {
            $query->where('serie_bac', $request->serie_bac);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nom) like ?', ["%".strtolower($search)."%"])
                  ->orWhereRaw('LOWER(prenoms) like ?', ["%".strtolower($search)."%"])
                  ->orWhereRaw('LOWER(email_eleve) like ?', ["%".strtolower($search)."%"]);
            });
        }
        
        if ($request->filled('annee_bac')) {
            $query->where('annee_bac', $request->annee_bac);
        }
        
        // Tri par score PEUB (du plus haut au plus bas)
        $query->orderByRaw('score_final_peub IS NULL') // NULL en dernier
              ->orderBy('score_final_peub', 'desc')
              ->orderBy('created_at', 'desc'); // Tri secondaire par date d'inscription
        
        $bacheliers = $query->paginate(20);
        
        // Calculer le rang global original pour chaque bachelier (sans filtres)
        // Ce rang sera affiché même lors du filtrage pour montrer la position originale
        if ($bacheliers->count() > 0) {
            // Récupérer tous les bacheliers triés par score (sans filtres) pour calculer les rangs globaux
            $allBacheliers = Bachelier::orderByRaw('score_final_peub IS NULL')
                                      ->orderBy('score_final_peub', 'desc')
                                      ->orderBy('created_at', 'desc')
                                      ->pluck('id', 'id');
            
            // Créer un mapping id => rang global
            $globalRanks = [];
            $rank = 1;
            foreach ($allBacheliers as $id) {
                $globalRanks[$id] = $rank;
                $rank++;
            }
            
            // Assigner le rang global à chaque bachelier
            foreach ($bacheliers as $bachelier) {
                if ($bachelier->score_final_peub !== null) {
                    // Afficher le rang global original
                    $bachelier->rang_temporaire = $globalRanks[$bachelier->id] ?? null;
                } else {
                    $bachelier->rang_temporaire = null;
                }
            }
        }
        
        // Statistiques globales (sans filtres)
        $statsGlobal = [
            'total' => Bachelier::count(),
            'boursiers' => Bachelier::where('boursier_peub', true)->count(),
            'verifies' => Bachelier::where('status_profil', 'verifie')->count(),
            'en_attente' => Bachelier::where('status_profil', 'en_attente')->count(),
        ];
        
        // Statistiques filtrées (avec les filtres appliqués)
        $queryStats = Bachelier::query();
        
        // Appliquer les mêmes filtres
        if ($request->filled('status_profil')) {
            $queryStats->where('status_profil', $request->status_profil);
        }
        
        if ($request->filled('boursier_peub')) {
            $queryStats->where('boursier_peub', $request->boursier_peub == '1');
        }
        
        if ($request->filled('region')) {
            $queryStats->where('region', $request->region);
        }
        
        if ($request->filled('serie_bac')) {
            $queryStats->where('serie_bac', $request->serie_bac);
        }
        
        if ($request->filled('annee_bac')) {
            $queryStats->where('annee_bac', $request->annee_bac);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $queryStats->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nom) like ?', ["%".strtolower($search)."%"])
                  ->orWhereRaw('LOWER(prenoms) like ?', ["%".strtolower($search)."%"])
                  ->orWhereRaw('LOWER(email_eleve) like ?', ["%".strtolower($search)."%"]);
            });
        }
        
        $stats = [
            'total' => $queryStats->count(),
            'boursiers' => (clone $queryStats)->where('boursier_peub', true)->count(),
            'verifies' => (clone $queryStats)->where('status_profil', 'verifie')->count(),
            'en_attente' => (clone $queryStats)->where('status_profil', 'en_attente')->count(),
            'total_global' => $statsGlobal['total'],
            'boursiers_global' => $statsGlobal['boursiers'],
            'verifies_global' => $statsGlobal['verifies'],
            'en_attente_global' => $statsGlobal['en_attente'],
            'has_filters' => $request->filled('status_profil') || $request->filled('boursier_peub') || 
                            $request->filled('region') || $request->filled('serie_bac') || 
                            $request->filled('annee_bac') || $request->filled('search'),
        ];
        
        // Options complètes pour les filtres
        $regions = [
            'Abidjan', 'Yamoussoukro', 'Agnéby‑Tiassa', 'Bafing', 'Bagoué', 'Bélier', 'Béré', 'Bounkani', 'Cavally', 'Folon',
            'Gbêkê', 'Gbôklé', 'Gôh', 'Gontougo', 'Grands‑Ponts', 'Guémon', 'Hambol', 'Haut‑Sassandra', 'Iffou', 'Indénié‑Djuablin',
            'Kabadougou', 'La Mé', 'LôhDjiboua', 'Marahoué', 'Moronou', 'Nawa', 'Nzi', 'Poro', 'San‑Pédro', 'Sud‑Comoé',
            'Tchologo', 'Tonkpi', 'Worodougou'
        ];
        
        $series = [
            'A1' => 'A1 (Lettres-Langues)',
            'A2' => 'A2 (Lettres-Philo)',
            'B' => 'B (Économie-Gestion)',
            'C' => 'C (Maths-Sciences Physiques)',
            'D' => 'D (Maths-Sciences Naturelles)',
            'E' => 'E (Mathématiques-Technique)',
            'F1' => 'F1 (Électrotechnique)',
            'F2' => 'F2 (Mécanique Générale)',
            'F3' => 'F3 (Électronique)',
            'F4' => 'F4 (Génie Civil)',
            'G1' => 'G1 (Secrétariat)',
            'G2' => 'G2 (Comptabilité)',
            'G3' => 'G3 (Commerce-Vente)'
        ];
        
        $status_profils = [
            'en_attente' => 'En attente de vérification',
            'verifie' => 'Profil vérifié',
            'incomplet' => 'Profil incomplet',
            'rejete' => 'Profil rejeté'
        ];
        
        $mentions = [
            'passable' => 'Passable',
            'assez_bien' => 'Assez Bien',
            'bien' => 'Bien',
            'tres_bien' => 'Très Bien',
            'excellent' => 'Excellent'
        ];
        
        $etablissement_types = [
            'public' => 'Public',
            'prive_homologue' => 'Privé Homologué',
            'prive_non_homologue' => 'Privé Non Homologué'
        ];
        
        $professions = [
            '1' => '1 - Cadres, professions intellectuelles supérieures (Ingénieurs, médecins)',
            '2' => '2 - Intermédiaires de l\'administration/services (Instituteurs, infirmiers)',
            '3' => '3 - Employés de bureau (Secrétaires, guichetiers)',
            '4' => '4 - Ouvriers qualifiés/artisans (Mécaniciens, menuisiers)',
            '5' => '5 - Travailleurs agricoles, pêcheurs (Paysans, éleveurs)',
            '6' => '6 - Travailleurs non qualifiés (Aides ménagers, journaliers)',
            '7' => '7 - Personnes sans emploi ou informel non déclaré (Marchands ambulants, sans activité)',
            'non_applicable' => 'Non applicable'
        ];
        
        $annees_bac = Bachelier::whereNotNull('annee_bac')
                               ->distinct()
                               ->orderBy('annee_bac', 'desc')
                               ->pluck('annee_bac')
                               ->mapWithKeys(fn($year) => [$year => $year])
                               ->toArray();
        
        $piece_identite_types = [
            'carte_scolaire' => 'Carte Scolaire',
            'cni' => 'CNI',
            'attestation' => 'Attestation'
        ];
        
        return view('admin.bacheliers.index', compact(
            'bacheliers', 
            'stats', 
            'regions', 
            'series', 
            'status_profils', 
            'mentions', 
            'etablissement_types', 
            'professions',
            'annees_bac',
            'piece_identite_types'
        ));
    }
    
    /**
     * Afficher les détails d'un bachelier
     */
    public function show(Bachelier $bachelier)
    {
        $bachelier->load([
            'user', 
            'candidatures.opportunite', 
            'favoris.opportunite',
            'parcoursUniversitaires'
        ]);
        
        // Calculer le rang temporaire si le bachelier a un score
        if ($bachelier->score_final_peub !== null) {
            // Compter combien de bacheliers ont un score supérieur
            $rang_temporaire = Bachelier::whereNotNull('score_final_peub')
                ->where('score_final_peub', '>', $bachelier->score_final_peub)
                ->count() + 1;
            
            $bachelier->rang_temporaire = $rang_temporaire;
        } else {
            $bachelier->rang_temporaire = null;
        }
        
        return view('admin.bacheliers.show', compact('bachelier'));
    }
    
    /**
     * Éditer un bachelier
     */
    public function edit(Bachelier $bachelier)
    {
        return view('admin.bacheliers.edit', compact('bachelier'));
    }
    
    /**
     * Mettre à jour un bachelier
     */
    public function update(Request $request, Bachelier $bachelier)
    {
        $request->validate([
            'status_profil' => 'required|in:en_attente,verifie,incomplet',
            'boursier_peub' => 'boolean',
            'moyenne_bac' => 'nullable|numeric|min:0|max:20',
            'mention' => 'nullable|in:passable,assez_bien,bien,tres_bien,excellent',
            'notes_admin' => 'nullable|string',
        ]);
        
        $bachelier->update($request->only([
            'status_profil', 'boursier_peub', 'moyenne_bac', 'mention', 'notes_admin'
        ]));
        
        return redirect()->route('admin.bacheliers.show', $bachelier)
                        ->with('success', 'Profil bachelier mis à jour avec succès.');
    }
    
    /**
     * Supprimer un bachelier
     */
    public function destroy(Bachelier $bachelier)
    {
        DB::beginTransaction();
        
        try {
            // Supprimer l'utilisateur associé (cascade)
            $bachelier->user->delete();
            
            DB::commit();
            
            return redirect()->route('admin.bacheliers.index')
                            ->with('success', 'Bachelier supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->with('error', 'Erreur lors de la suppression du bachelier.');
        }
    }
    
    /**
     * Validation en masse des profils
     */
    public function bulkValidate(Request $request)
    {
        $request->validate([
            'bachelier_ids' => 'required|array',
            'bachelier_ids.*' => 'exists:bacheliers,id',
            'action' => 'required|in:valider,rejeter',
        ]);
        
        $count = Bachelier::whereIn('id', $request->bachelier_ids)
                         ->update([
                             'status_profil' => $request->action === 'valider' ? 'verifie' : 'incomplet'
                         ]);
        
        $message = $request->action === 'valider' 
                 ? "profil(s) validé(s) avec succès" 
                 : "profil(s) marqué(s) comme incomplet(s)";
        
        return back()->with('success', "{$count} {$message}.");
    }
    
    /**
     * Attribution bourse PEUB
     */
    public function toggleBoursier(Bachelier $bachelier)
    {
        $bachelier->update([
            'boursier_peub' => !$bachelier->boursier_peub
        ]);
        
        $status = $bachelier->boursier_peub ? 'ajouté au' : 'retiré du';
        
        return back()->with('success', "Bachelier {$status} programme PEUB avec succès.");
    }
    
    /**
     * Export des données bacheliers
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        // TODO: Implémenter l'export selon le format demandé
        return back()->with('info', 'Fonctionnalité d\'export en cours de développement.');
    }
    
    /**
     * Statistiques détaillées
     */
    public function analytics()
    {
        $analytics = [
            'repartition_regions' => Bachelier::select('region', DB::raw('count(*) as total'))
                                             ->groupBy('region')
                                             ->orderBy('total', 'desc')
                                             ->get(),
            'repartition_series' => Bachelier::select('serie_bac', DB::raw('count(*) as total'))
                                            ->groupBy('serie_bac')
                                            ->get(),
            'repartition_mentions' => Bachelier::select('mention', DB::raw('count(*) as total'))
                                              ->groupBy('mention')
                                              ->get(),
            'evolution_mensuelle' => Bachelier::select(
                                                DB::raw('YEAR(created_at) as annee'),
                                                DB::raw('MONTH(created_at) as mois'),
                                                DB::raw('count(*) as total')
                                              )
                                              ->groupBy('annee', 'mois')
                                              ->orderBy('annee', 'desc')
                                              ->orderBy('mois', 'desc')
                                              ->limit(12)
                                              ->get(),
        ];
        
        return view('admin.bacheliers.analytics', compact('analytics'));
    }
    
    /**
     * Valider un bachelier (activer son compte)
     */
    public function validateBachelier($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->role !== 'bachelier') {
                return back()->with('error', 'Cet utilisateur n\'est pas un bachelier.');
            }

            $bachelier = $user->bachelier;
            if (!$bachelier) {
                return back()->with('error', 'Aucun profil bachelier trouvé pour cet utilisateur.');
            }

            // Activer le compte
            $user->activate();
            
            // Mettre à jour le statut de candidature
            $bachelier->update([
                'status_candidature' => 'accepte',
                'status_profil' => 'verifie',
                'date_verification' => now(),
            ]);
            
            // Envoyer l'email de félicitations
            try {
                $admin = Auth::user();
                Mail::to($bachelier->email_eleve)->send(
                    new BachelierCandidatureApprovedMail($bachelier, $user, $admin)
                );
                
                Log::info('Email de félicitations envoyé au bachelier', [
                    'bachelier_id' => $bachelier->id,
                    'email' => $bachelier->email_eleve,
                    'approved_by' => $admin->id
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'email de félicitations', [
                    'bachelier_id' => $bachelier->id,
                    'error' => $e->getMessage()
                ]);
                // Ne pas bloquer la validation si l'email échoue
            }
            
            return back()->with('success', 'Le bachelier a été validé avec succès. Un email de félicitations lui a été envoyé.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation du bachelier', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de la validation du bachelier.');
        }
    }

    /**
     * Suspendre un bachelier
     */
    public function suspendBachelier($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->role !== 'bachelier') {
                return back()->with('error', 'Cet utilisateur n\'est pas un bachelier.');
            }

            $user->suspend();
            
            return back()->with('success', 'Le bachelier a été suspendu avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suspension du bachelier', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de la suspension du bachelier.');
        }
    }

    /**
     * Valider plusieurs bacheliers en masse
     */
    public function validateMultiple(Request $request)
    {
        $request->validate([
            'bacheliers' => 'required|array',
            'bacheliers.*' => 'exists:users,id'
        ]);

        try {
            $validatedCount = 0;
            
            foreach ($request->bacheliers as $userId) {
                $user = User::find($userId);
                if ($user && $user->role === 'bachelier' && $user->isPending()) {
                    $user->activate();
                    $validatedCount++;
                }
            }
            
            return back()->with('success', "{$validatedCount} bacheliers ont été validés avec succès.");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation en masse des bacheliers', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de la validation en masse.');
        }
    }

    /**
     * Exporter les bacheliers en Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $query = Bachelier::with('user');
            
            // Appliquer les mêmes filtres que la liste
            if ($request->filled('status_profil')) {
                $query->where('status_profil', $request->status_profil);
            }
            
            if ($request->filled('boursier_peub')) {
                $query->where('boursier_peub', $request->boursier_peub == '1');
            }
            
            if ($request->filled('region')) {
                $query->where('region', $request->region);
            }
            
            if ($request->filled('serie_bac')) {
                $query->where('serie_bac', $request->serie_bac);
            }
            
            if ($request->filled('annee_bac')) {
                $query->where('annee_bac', $request->annee_bac);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereRaw('LOWER(nom) like ?', ["%".strtolower($search)."%"])
                      ->orWhereRaw('LOWER(prenoms) like ?', ["%".strtolower($search)."%"])
                      ->orWhereRaw('LOWER(email_eleve) like ?', ["%".strtolower($search)."%"]);
                });
            }
            
            // Récupérer les données
            $bacheliers = $query->orderByDesc('score_final_peub')->get();
            
            // Créer le fichier CSV
            $filename = 'bacheliers_' . date('Y-m-d_H-i-s') . '.csv';
            $handle = fopen('php://memory', 'r+');
            
            // En-têtes du CSV avec BOM pour UTF-8
            $headers = [
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Région',
                'Série Bac',
                'Année Bac',
                'Note Bac',
                'Mention',
                'Moyenne PEUB',
                'Score PEUB',
                'Rang Temporaire',
                'Statut Profil',
                'Boursier PEUB',
                'Statut Utilisateur'
            ];
            
            // Ajouter BOM pour UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers, ';');
            
            // Ajouter les données
            foreach ($bacheliers as $bachelier) {
                // Calculer la moyenne sur 20
                $moyenne = $bachelier->note_bac ? number_format(($bachelier->note_bac / 400) * 20, 2) : 'N/A';
                
                $row = [
                    $bachelier->nom,
                    $bachelier->prenoms,
                    $bachelier->email_eleve,
                    $bachelier->telephone_eleve,
                    $bachelier->region,
                    $bachelier->serie_bac ?? 'N/A',
                    $bachelier->annee_bac ?? 'N/A',
                    $bachelier->note_bac ? number_format($bachelier->note_bac, 2) : 'N/A',
                    $bachelier->mention ? ucfirst(str_replace('_', ' ', $bachelier->mention)) : 'N/A',
                    $moyenne,
                    $bachelier->score_final_peub ? number_format($bachelier->score_final_peub, 2) . '/100' : 'N/A',
                    $bachelier->rang_temporaire ?? 'N/A',
                    $bachelier->status_profil ?? 'N/A',
                    $bachelier->boursier_peub ? 'Oui' : 'Non',
                    $bachelier->user ? ucfirst($bachelier->user->status) : 'N/A'
                ];
                fputcsv($handle, $row, ';');
            }
            
            // Récupérer le contenu
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);
            
            // Retourner le fichier
            return response($csv, 200)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', "attachment; filename=\"$filename\"")
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'export Excel des bacheliers', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de l\'export.');
        }
    }
    
    /**
     * Afficher le barème du scoring
     */
    public function bareme()
    {
        return view('admin.bacheliers.bareme');
    }
    
    /**
     * Afficher les bacheliers par année
     */
    public function byYear($year, Request $request)
    {
        $query = Bachelier::with('user')->where('annee_bac', $year);
        
        // Filtres
        if ($request->filled('status_profil')) {
            $query->where('status_profil', $request->status_profil);
        }
        
        if ($request->filled('boursier_peub')) {
            $query->where('boursier_peub', $request->boursier_peub == '1');
        }
        
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        
        if ($request->filled('serie_bac')) {
            $query->where('serie_bac', $request->serie_bac);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nom) like ?', ["%".strtolower($search)."%"])
                  ->orWhereRaw('LOWER(prenoms) like ?', ["%".strtolower($search)."%"])
                  ->orWhereRaw('LOWER(email_eleve) like ?', ["%".strtolower($search)."%"]);
            });
        }
        
        // Tri par score PEUB
        $query->orderByRaw('score_final_peub IS NULL')
              ->orderBy('score_final_peub', 'desc')
              ->orderBy('created_at', 'desc');
        
        $bacheliers = $query->paginate(20);
        
        // Calculer le rang global original pour chaque bachelier
        if ($bacheliers->count() > 0) {
            $allBacheliers = Bachelier::where('annee_bac', $year)
                                      ->orderByRaw('score_final_peub IS NULL')
                                      ->orderBy('score_final_peub', 'desc')
                                      ->orderBy('created_at', 'desc')
                                      ->pluck('id', 'id');
            
            $globalRanks = [];
            $rank = 1;
            foreach ($allBacheliers as $id) {
                $globalRanks[$id] = $rank;
                $rank++;
            }
            
            foreach ($bacheliers as $bachelier) {
                if ($bachelier->score_final_peub !== null) {
                    $bachelier->rang_temporaire = $globalRanks[$bachelier->id] ?? null;
                } else {
                    $bachelier->rang_temporaire = null;
                }
            }
        }
        
        // Statistiques filtrées
        $queryStats = Bachelier::where('annee_bac', $year);
        
        if ($request->filled('status_profil')) {
            $queryStats->where('status_profil', $request->status_profil);
        }
        
        if ($request->filled('boursier_peub')) {
            $queryStats->where('boursier_peub', $request->boursier_peub == '1');
        }
        
        if ($request->filled('region')) {
            $queryStats->where('region', $request->region);
        }
        
        if ($request->filled('serie_bac')) {
            $queryStats->where('serie_bac', $request->serie_bac);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $queryStats->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nom) like ?', ["%".strtolower($search)."%"])
                  ->orWhereRaw('LOWER(prenoms) like ?', ["%".strtolower($search)."%"])
                  ->orWhereRaw('LOWER(email_eleve) like ?', ["%".strtolower($search)."%"]);
            });
        }
        
        $stats = [
            'total' => $queryStats->count(),
            'boursiers' => (clone $queryStats)->where('boursier_peub', true)->count(),
            'verifies' => (clone $queryStats)->where('status_profil', 'verifie')->count(),
            'en_attente' => (clone $queryStats)->where('status_profil', 'en_attente')->count(),
            'total_global' => Bachelier::where('annee_bac', $year)->count(),
            'boursiers_global' => Bachelier::where('annee_bac', $year)->where('boursier_peub', true)->count(),
            'verifies_global' => Bachelier::where('annee_bac', $year)->where('status_profil', 'verifie')->count(),
            'en_attente_global' => Bachelier::where('annee_bac', $year)->where('status_profil', 'en_attente')->count(),
            'has_filters' => $request->filled('status_profil') || $request->filled('boursier_peub') || 
                            $request->filled('region') || $request->filled('serie_bac') || 
                            $request->filled('search'),
        ];
        
        // Options pour les filtres
        $regions = [
            'Abidjan', 'Yamoussoukro', 'Agnéby‑Tiassa', 'Bafing', 'Bagoué', 'Bélier', 'Béré', 'Bounkani', 'Cavally', 'Folon',
            'Gbêkê', 'Gbôklé', 'Gôh', 'Gontougo', 'Grands‑Ponts', 'Guémon', 'Hambol', 'Haut‑Sassandra', 'Iffou', 'Indénié‑Djuablin',
            'Kabadougou', 'La Mé', 'LôhDjiboua', 'Marahoué', 'Moronou', 'Nawa', 'Nzi', 'Poro', 'San‑Pédro', 'Sud‑Comoé',
            'Tchologo', 'Tonkpi', 'Worodougou'
        ];
        
        $series = [
            'A1' => 'A1 (Lettres-Langues)',
            'A2' => 'A2 (Lettres-Philosophie)',
            'C' => 'C (Maths-Physique)',
            'D' => 'D (Maths-SVT)',
            'E' => 'E (Maths-Technique)',
            'F1' => 'F1 (Français-Anglais)',
            'F2' => 'F2 (Français-Allemand)',
            'F3' => 'F3 (Français-Espagnol)',
            'F4' => 'F4 (Français-Arabe)',
            'G' => 'G (Gestion)',
            'H' => 'H (Hôtellerie)',
            'L' => 'L (Lettres)',
            'M' => 'M (Maths)',
            'S' => 'S (Sciences)',
            'T' => 'T (Technique)',
        ];
        
        return view('admin.bacheliers.by-year', compact('bacheliers', 'stats', 'regions', 'series', 'year'));
    }
}
