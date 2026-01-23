<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAuthService;
use App\Services\AiExtractionService;
use App\Mail\BachelierCandidatureSubmittedMail;
use App\Mail\AdminNewCandidatureMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SocialAuthController extends Controller
{
    protected SocialAuthService $socialAuthService;
    protected AiExtractionService $aiService;

    public function __construct(SocialAuthService $socialAuthService, AiExtractionService $aiService)
    {
        $this->socialAuthService = $socialAuthService;
        $this->aiService = $aiService;
    }

    /**
     * Rediriger vers le provider social
     */
    public function redirect(Request $request, string $provider)
    {
        try {
            // Sauvegarder l'URL de redirection si fournie
            if ($request->has('redirect_to')) {
                session(['redirect_to' => $request->redirect_to]);
            }

            // Sauvegarder le contexte (login ou register)
            $context = $request->get('context', 'login');
            session(['social_auth_context' => $context]);

            return $this->socialAuthService->redirect($provider);
        } catch (\Exception $e) {
            return redirect()->route('auth.login')
                           ->with('error', $e->getMessage());
        }
    }

    /**
     * Gérer le callback du provider social
     */
    public function callback(string $provider)
    {
        try {
            \Log::info("Social auth callback started", ['provider' => $provider]);

            $result = $this->socialAuthService->handleCallback($provider);
            $user = $result['user'];
            $isNew = $result['is_new'];

            \Log::info("User retrieved from social auth", [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_new' => $isNew,
                'has_bachelier' => $user->bachelier ? 'yes' : 'no'
            ]);

            // Vérifier le statut du compte
            if ($user->isSuspended()) {
                \Log::warning("Suspended user tried to login", ['user_id' => $user->id]);
                return redirect()->route('auth.login')
                               ->with('error', 'Votre compte est suspendu. Contactez l\'administration.');
            }

            // Si c'est un nouvel utilisateur, on doit compléter le profil
            if ($isNew) {
                // Connecter l'utilisateur temporairement pour qu'il puisse accéder au formulaire
                Auth::login($user, true);
                \Log::info("New user redirected to complete profile", ['user_id' => $user->id]);

                // Rediriger vers le formulaire de complétion de profil
                return redirect()->route('auth.complete-profile')
                               ->with('info', 'Veuillez compléter votre profil pour finaliser votre inscription.');
            }

            // Vérifier si le profil bachelier existe
            if ($user->role === 'bachelier' && !$user->bachelier) {
                // Le compte existe mais pas le profil complet
                Auth::login($user, true);
                \Log::info("Existing user without bachelier profile redirected to complete profile", ['user_id' => $user->id]);
                return redirect()->route('auth.complete-profile')
                               ->with('info', 'Veuillez compléter votre profil pour continuer.');
            }

            // Utilisateur existant avec profil complet
            Auth::login($user, true);
            \Log::info("User logged in successfully", ['user_id' => $user->id]);

            // Enregistrer la statistique de connexion
            try {
                $user->statistiquesEngagement()->create([
                    'action' => 'login',
                    'metadonnees' => [
                        'provider' => $provider,
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ],
                    'created_at' => now()
                ]);
            } catch (\Exception $e) {
                \Log::error("Failed to create engagement stats", ['error' => $e->getMessage()]);
            }

            // Récupérer l'URL de redirection
            $redirectTo = session('redirect_to');
            session()->forget(['redirect_to', 'social_auth_context']);

            \Log::info("Redirecting user to dashboard", [
                'user_id' => $user->id,
                'redirect_to' => $redirectTo ?? 'dashboard'
            ]);

            return redirect($redirectTo ?? route('dashboard'))
                         ->with('success', 'Connexion réussie !');

        } catch (\Exception $e) {
            \Log::error("Social auth callback error", [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('auth.login')
                           ->with('error', 'Erreur lors de la connexion : ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de complétion de profil
     */
    public function showCompleteProfile()
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est connecté et a besoin de compléter son profil
        if (!$user || ($user->bachelier && $user->status === 'active')) {
            return redirect()->route('dashboard');
        }

        // Récupérer les données de session si l'utilisateur revient de la preview
        $sessionData = session('profile_data', []);
        
        // Récupérer les établissements de la base de données
        $etablissements = \App\Models\Etablissement::orderBy('etablissement')->get();

        return view('auth.complete-profile', [
            'user' => $user,
            'sessionData' => $sessionData,
            'etablissements' => $etablissements,
        ]);
    }

    /**
     * Afficher la prévisualisation avant soumission finale
     */
    public function showPreview(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->bachelier) {
            return redirect()->route('dashboard');
        }

        // Récupérer les données de session existantes
        $existingData = session('profile_data', []);
        $hasExistingFiles = isset($existingData['piece_identite_file_temp']) && isset($existingData['collante_bac_file_temp']);

        // Validation des données
        // Les fichiers sont optionnels si on a déjà des fichiers en session
        $validated = $request->validate([
            // Informations générales
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'date_naissance' => 'required|date|before_or_equal:2020-12-31|after:1990-01-01',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'piece_identite_type' => 'required|in:carte_scolaire,cni,attestation',
            'piece_identite_file' => ($hasExistingFiles ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png|max:10240',
            'telephone_eleve' => 'required|string|max:20',
            'telephone_parent' => 'required|string|max:20',
            'email_eleve' => 'required|email|max:255',
            'email_parent' => 'required|email|max:255',
            'region' => 'required|string|max:255',
            'commune' => 'required|string|max:255',
            'photo_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            
            // Informations scolaires (note_bac sur 400 points)
            'matricule_bac' => 'required|string|max:50|unique:bacheliers,matricule_bac',
            'serie_bac' => 'required|in:C,E,D,A1,A2,F1,F2,F3,F4,F5,F6,F7,F8,G1,G2,G3,BT,BP',
            'note_bac' => 'required|numeric|min:0|max:400',
            'annee_bac' => 'required|integer|min:2022|max:2025',
            'etablissement_nom' => 'required|string|max:255',
            'etablissement_type' => 'required|in:public,prive_homologue,prive_non_homologue',
            'collante_bac_file' => ($hasExistingFiles ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png|max:10240',
            
            // Situation sociale
            'pensionnaire_internat' => 'required|boolean',
            'bourse_scolaire_lycee' => 'required|boolean',
            'profession_pere' => 'required|in:cadres_professions_intellectuelles,administration_services,employes_bureau,ouvriers_qualifies_artisans,travailleurs_agricoles_pecheurs,travailleurs_non_qualifies,sans_emploi_informel,non_applicable',
            'profession_mere' => 'required|in:cadres_professions_intellectuelles,administration_services,employes_bureau,ouvriers_qualifies_artisans,travailleurs_agricoles_pecheurs,travailleurs_non_qualifies,sans_emploi_informel,non_applicable',
            'situations_particulieres' => 'nullable|array',
            'situations_particulieres.*' => 'in:handicap,orphelin,autre',
            'connexion_internet' => 'required|in:aucune,3g_4g,fibre',
            'possede_ordinateur' => 'required|boolean',
            'acces_smartphone' => 'required|boolean',
            'acces_ia' => 'required|boolean',
            
            // Motivation
            'motivation' => 'required|string|min:100|max:5000',
            
            // Acceptations
            'acceptation_conditions' => 'required|accepted',
            'acceptation_donnees' => 'required|accepted',
        ], [
            // Informations générales
            'nom.required' => 'Le nom est obligatoire.',
            'prenoms.required' => 'Les prénoms sont obligatoires.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.before_or_equal' => 'Vous devez être né(e) avant le 31 décembre 2020.',
            'date_naissance.after' => 'La date de naissance doit être après le 1er janvier 1990.',
            'lieu_naissance.required' => 'Le lieu de naissance est obligatoire.',
            'sexe.required' => 'Veuillez sélectionner votre sexe.',
            'sexe.in' => 'Le sexe doit être Masculin ou Féminin.',
            'piece_identite_type.required' => 'Veuillez sélectionner le type de pièce d\'identité.',
            'piece_identite_file.required' => 'Le scan de votre pièce d\'identité est obligatoire.',
            'piece_identite_file.image' => 'La pièce d\'identité doit être une image.',
            'piece_identite_file.mimes' => 'La pièce d\'identité doit être au format JPG, JPEG ou PNG.',
            'piece_identite_file.max' => 'La pièce d\'identité ne doit pas dépasser 10 MB.',
            'telephone_eleve.required' => 'Votre numéro de téléphone est obligatoire.',
            'telephone_parent.required' => 'Le numéro de téléphone du parent/tuteur est obligatoire.',
            'email_eleve.required' => 'Votre email est obligatoire.',
            'email_eleve.email' => 'Veuillez entrer une adresse email valide.',
            'email_parent.required' => 'L\'email du parent/tuteur est obligatoire.',
            'email_parent.email' => 'Veuillez entrer une adresse email valide pour le parent.',
            'region.required' => 'Veuillez sélectionner votre région.',
            'commune.required' => 'Veuillez indiquer votre commune.',
            'photo_profil.image' => 'La photo de profil doit être une image.',
            'photo_profil.max' => 'La photo de profil ne doit pas dépasser 5 MB.',
            
            // Informations scolaires
            'matricule_bac.required' => 'Le matricule BAC est obligatoire.',
            'matricule_bac.unique' => 'Ce matricule BAC est déjà enregistré dans notre système.',
            'serie_bac.required' => 'La série BAC est obligatoire.',
            'serie_bac.in' => 'Veuillez sélectionner une série BAC valide.',
            'note_bac.required' => 'La note BAC est obligatoire.',
            'note_bac.numeric' => 'La note BAC doit être un nombre.',
            'note_bac.min' => 'La note BAC doit être un nombre positif.',
            'note_bac.max' => 'La note BAC doit être sur 400 points maximum (système ivoirien).',
            'annee_bac.required' => 'L\'année d\'obtention du BAC est obligatoire.',
            'annee_bac.integer' => 'L\'année BAC doit être un nombre entier.',
            'annee_bac.min' => 'L\'année BAC ne peut pas être antérieure à 2022.',
            'annee_bac.max' => 'L\'année BAC ne peut pas être supérieure à 2025.',
            'etablissement_nom.required' => 'Le nom de votre établissement est obligatoire.',
            'etablissement_type.required' => 'Veuillez sélectionner le type d\'établissement.',
            'collante_bac_file.required' => 'Le scan de votre collante BAC est obligatoire.',
            'collante_bac_file.image' => 'La collante BAC doit être une image.',
            'collante_bac_file.mimes' => 'La collante BAC doit être au format JPG, JPEG ou PNG.',
            'collante_bac_file.max' => 'La collante BAC ne doit pas dépasser 10 MB.',
            
            // Situation sociale
            'pensionnaire_internat.required' => 'Veuillez indiquer si vous étiez pensionnaire.',
            'bourse_scolaire_lycee.required' => 'Veuillez indiquer si vous aviez une bourse au lycée.',
            'profession_pere.required' => 'La profession du père est obligatoire.',
            'profession_pere.in' => 'Veuillez sélectionner une catégorie de profession valide pour le père.',
            'profession_mere.required' => 'La profession de la mère est obligatoire.',
            'profession_mere.in' => 'Veuillez sélectionner une catégorie de profession valide pour la mère.',
            'connexion_internet.required' => 'Veuillez indiquer votre type de connexion internet.',
            'possede_ordinateur.required' => 'Veuillez indiquer si vous possédez un ordinateur.',
            'acces_smartphone.required' => 'Veuillez indiquer si vous avez accès à un smartphone.',
            'acces_ia.required' => 'Veuillez indiquer si vous avez accès aux outils IA.',
            
            // Motivation
            'motivation.required' => 'La lettre de motivation est obligatoire.',
            'motivation.min' => 'Votre lettre de motivation doit contenir au moins 100 caractères. Développez davantage vos ambitions et motivations.',
            'motivation.max' => 'La lettre de motivation ne peut pas dépasser 5000 caractères.',
            
            // Acceptations
            'acceptation_conditions.required' => 'Vous devez certifier l\'exactitude de vos informations.',
            'acceptation_conditions.accepted' => 'Vous devez certifier l\'exactitude de vos informations pour continuer.',
            'acceptation_donnees.required' => 'Vous devez accepter la politique de confidentialité.',
            'acceptation_donnees.accepted' => 'Vous devez accepter le traitement de vos données personnelles pour continuer.',
        ]);

        // Calculer la mention automatiquement
        $mention = \App\Models\Bachelier::calculateMention($validated['note_bac']);
        $validated['mention'] = $mention;

        // Stocker temporairement les fichiers et les valider avec l'IA
        $tempData = [];
        $validationErrors = [];
        
        // Utiliser les fichiers existants ou les nouveaux
        if ($request->hasFile('piece_identite_file')) {
            $filePath = $request->file('piece_identite_file')->store('temp', 'public');
            $tempData['piece_identite_file_temp'] = $filePath;
            
            // DÉSACTIVÉ TEMPORAIREMENT - Erreur SSL sur le serveur
            // Valider le document avec l'IA
            // \Log::info('Validation IA de la pièce d\'identité en cours...');
            // $validation = $this->aiService->validateDocument($filePath, 'piece_identite');
            // 
            // if (!$validation['is_valid']) {
            //     $validationErrors['piece_identite_file'] = $validation['reason'];
            //     \Log::warning('Pièce d\'identité rejetée par l\'IA', $validation);
            // }
        } elseif (isset($existingData['piece_identite_file_temp'])) {
            $tempData['piece_identite_file_temp'] = $existingData['piece_identite_file_temp'];
        }
        
        if ($request->hasFile('collante_bac_file')) {
            $filePath = $request->file('collante_bac_file')->store('temp', 'public');
            $tempData['collante_bac_file_temp'] = $filePath;
            
            // DÉSACTIVÉ TEMPORAIREMENT - Erreur SSL sur le serveur
            // Valider le document avec l'IA
            // \Log::info('Validation IA de la collante BAC en cours...');
            // $validation = $this->aiService->validateDocument($filePath, 'collante_bac');
            // 
            // if (!$validation['is_valid']) {
            //     $validationErrors['collante_bac_file'] = $validation['reason'];
            //     \Log::warning('Collante BAC rejetée par l\'IA', $validation);
            // }
        } elseif (isset($existingData['collante_bac_file_temp'])) {
            $tempData['collante_bac_file_temp'] = $existingData['collante_bac_file_temp'];
        }

        if ($request->hasFile('photo_profil')) {
            $tempData['photo_profil_temp'] = $request->file('photo_profil')->store('temp', 'public');
        } elseif (isset($existingData['photo_profil_temp'])) {
            $tempData['photo_profil_temp'] = $existingData['photo_profil_temp'];
        }
        
        // Si des documents sont invalides, rejeter la soumission
        if (!empty($validationErrors)) {
            return back()
                ->withErrors($validationErrors)
                ->withInput()
                ->with('error', 'Certains documents téléchargés ne sont pas valides. Veuillez vérifier et télécharger les bons documents.');
        }

        // Retirer les fichiers de $validated pour éviter l'erreur de sérialisation
        // Les objets UploadedFile ne peuvent pas être stockés en session
        unset($validated['piece_identite_file']);
        unset($validated['collante_bac_file']);
        unset($validated['photo_profil']);

        // Stocker les données en session pour la confirmation
        session(['profile_data' => array_merge($validated, $tempData)]);

        return view('auth.complete-profile-preview', [
            'user' => $user,
            'data' => $validated,
            'tempData' => $tempData,
            'mention' => $mention
        ]);
    }

    /**
     * Compléter le profil après connexion sociale
     */
    public function completeProfile(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->bachelier) {
            return redirect()->route('dashboard');
        }

        // Récupérer les données depuis la session (venant de la preview)
        $validated = session('profile_data');
        
        if (!$validated) {
            return redirect()->route('auth.complete-profile')
                ->with('error', 'Session expirée. Veuillez remplir à nouveau le formulaire.');
        }

        // Si on vient directement (sans preview), faire la validation normale
        if (!isset($validated['piece_identite_file_temp'])) {
        $validated = $request->validate([
            // Informations générales
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'date_naissance' => 'required|date|before_or_equal:2020-12-31|after:1990-01-01',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'piece_identite_type' => 'required|in:carte_scolaire,cni,attestation',
            'piece_identite_file' => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'telephone_eleve' => 'required|string|max:20',
            'telephone_parent' => 'required|string|max:20',
            'email_eleve' => 'required|email|max:255',
            'email_parent' => 'required|email|max:255',
            'region' => 'required|string|max:255',
            'commune' => 'required|string|max:255',
            'photo_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            
            // Informations scolaires (note_bac sur 400 points)
            'matricule_bac' => 'required|string|max:50|unique:bacheliers,matricule_bac',
            'serie_bac' => 'required|in:C,E,D,A1,A2,F1,F2,F3,F4,F5,F6,F7,F8,G1,G2,G3,BT,BP',
            'note_bac' => 'required|numeric|min:0|max:400',
            'annee_bac' => 'required|integer|min:2022|max:2025',
            'etablissement_nom' => 'required|string|max:255',
            'etablissement_type' => 'required|in:public,prive_homologue,prive_non_homologue',
            'collante_bac_file' => 'required|image|mimes:jpg,jpeg,png|max:10240',
            
            // Situation sociale
            'pensionnaire_internat' => 'required|boolean',
            'bourse_scolaire_lycee' => 'required|boolean',
            'profession_pere' => 'required|in:cadres_professions_intellectuelles,administration_services,employes_bureau,ouvriers_qualifies_artisans,travailleurs_agricoles_pecheurs,travailleurs_non_qualifies,sans_emploi_informel,non_applicable',
            'profession_mere' => 'required|in:cadres_professions_intellectuelles,administration_services,employes_bureau,ouvriers_qualifies_artisans,travailleurs_agricoles_pecheurs,travailleurs_non_qualifies,sans_emploi_informel,non_applicable',
            'situations_particulieres' => 'nullable|array',
            'situations_particulieres.*' => 'in:handicap,orphelin,autre',
            'connexion_internet' => 'required|in:aucune,3g_4g,fibre',
            'possede_ordinateur' => 'required|boolean',
            'acces_smartphone' => 'required|boolean',
            'acces_ia' => 'required|boolean',
            
            // Motivation
            'motivation' => 'required|string|min:100|max:5000',
            
            // Acceptations
            'acceptation_conditions' => 'required|accepted',
            'acceptation_donnees' => 'required|accepted',
        ], [
            'date_naissance.before_or_equal' => 'Vous devez être né(e) avant le 31 décembre 2020.',
            'date_naissance.after' => 'Veuillez entrer une date de naissance valide.',
            'note_bac.max' => 'La note BAC doit être sur 400 points maximum.',
            'note_bac.min' => 'La note BAC doit être un nombre positif.',
            'matricule_bac.unique' => 'Ce matricule BAC est déjà enregistré.',
            'motivation.min' => 'La lettre de motivation doit contenir au moins 100 caractères.',
                'piece_identite_file.max' => 'La pièce d\'identité ne doit pas dépasser 10 MB.',
                'collante_bac_file.max' => 'La collante BAC ne doit pas dépasser 10 MB.',
                'photo_profil.max' => 'La photo de profil ne doit pas dépasser 5 MB.',
        ]);
        }

        try {
            // Gérer les uploads de fichiers
            if (isset($validated['piece_identite_file_temp'])) {
                // Déplacer les fichiers temp vers leur emplacement final
                $pieceIdentitePath = \Illuminate\Support\Facades\Storage::disk('public')
                    ->move($validated['piece_identite_file_temp'], 'documents/pieces_identite/' . basename($validated['piece_identite_file_temp']));
                
                $collanteBacPath = \Illuminate\Support\Facades\Storage::disk('public')
                    ->move($validated['collante_bac_file_temp'], 'documents/collantes_bac/' . basename($validated['collante_bac_file_temp']));
                
                $photoProfilPath = isset($validated['photo_profil_temp'])
                    ? \Illuminate\Support\Facades\Storage::disk('public')
                        ->move($validated['photo_profil_temp'], 'photos/profils/' . basename($validated['photo_profil_temp']))
                    : $user->avatar;
            } else {
                // Upload direct (sans preview)
            $pieceIdentitePath = $request->file('piece_identite_file')->store('documents/pieces_identite', 'public');
            $collanteBacPath = $request->file('collante_bac_file')->store('documents/collantes_bac', 'public');
            $photoProfilPath = $request->hasFile('photo_profil') 
                ? $request->file('photo_profil')->store('photos/profils', 'public')
                : $user->avatar;
            }

            // Créer le profil bachelier
            $this->socialAuthService->createBachelierProfile($user, array_merge($validated, [
                'piece_identite_file' => $pieceIdentitePath,
                'collante_bac_file' => $collanteBacPath,
                'photo_profil' => $photoProfilPath,
            ]));

            // Nettoyer la session
            session()->forget('profile_data');

            return redirect()->route('dashboard')
                           ->with('success', 'Votre profil a été complété avec succès ! Bienvenue sur PEUB.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du profil bachelier', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                        ->with('error', 'Erreur lors de la création du profil : ' . $e->getMessage());
        }
    }
}

