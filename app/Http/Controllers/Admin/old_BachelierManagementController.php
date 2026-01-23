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
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('email_eleve', 'like', "%{$search}%");
            });
        }
        
        // Tri par score PEUB (du plus haut au plus bas)
        $query->orderByRaw('score_final_peub IS NULL') // NULL en dernier
              ->orderBy('score_final_peub', 'desc')
              ->orderBy('created_at', 'desc'); // Tri secondaire par date d'inscription
        
        $bacheliers = $query->paginate(20);
        
        // Calculer le rang temporaire pour chaque bachelier sur la page actuelle
        // en tenant compte de la pagination
        $baseRank = ($bacheliers->currentPage() - 1) * $bacheliers->perPage();
        foreach ($bacheliers as $index => $bachelier) {
            if ($bachelier->score_final_peub !== null) {
                $bachelier->rang_temporaire = $baseRank + $index + 1;
            } else {
                $bachelier->rang_temporaire = null;
            }
        }
        
        // Statistiques pour les filtres
        $stats = [
            'total' => Bachelier::count(),
            'boursiers' => Bachelier::where('boursier_peub', true)->count(),
            'verifies' => Bachelier::where('status_profil', 'verifie')->count(),
            'en_attente' => Bachelier::where('status_profil', 'en_attente')->count(),
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
        
        $annees_bac = [
            '2025' => '2025'
        ];
        
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
}
