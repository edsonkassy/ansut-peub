<?php

namespace App\Services;

use App\Models\User;
use App\Models\Bachelier;
use App\Jobs\ProcessAiExtraction;
use App\Mail\BachelierCandidatureSubmittedMail;
use App\Mail\AdminNewCandidatureMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

class SocialAuthService
{
    /**
     * Providers supportés
     */
    const SUPPORTED_PROVIDERS = ['google', 'facebook', 'microsoft', 'linkedin'];

    /**
     * Rediriger vers le provider social
     */
    public function redirect(string $provider)
    {
        $this->validateProvider($provider);
        
        return Socialite::driver($provider)
            ->with(['prompt' => 'select_account']) // Force la sélection de compte
            ->redirect();
    }

    /**
     * Gérer le callback du provider social
     * 
     * @return array ['user' => User, 'is_new' => bool]
     */
    public function handleCallback(string $provider): array
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
            
            return $this->findOrCreateUser($socialUser, $provider);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la connexion avec {$provider}: " . $e->getMessage());
        }
    }

    /**
     * Trouver ou créer un utilisateur à partir des données sociales
     * 
     * @return array ['user' => User, 'is_new' => bool]
     */
    protected function findOrCreateUser(SocialiteUser $socialUser, string $provider): array
    {
        return DB::transaction(function () use ($socialUser, $provider) {
            // 1. Vérifier si l'utilisateur existe déjà avec ce provider
            $user = User::where('provider', $provider)
                       ->where('provider_id', $socialUser->getId())
                       ->first();

            if ($user) {
                // Mettre à jour l'avatar si changé
                $user->update([
                    'avatar' => $socialUser->getAvatar(),
                    'last_login_at' => now(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
                
                return ['user' => $user, 'is_new' => false];
            }

            // 2. Vérifier si un compte existe avec cet email
            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                // Lier le compte social au compte existant
                $existingUser->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                    'last_login_at' => now(),
                    'email_verified_at' => $existingUser->email_verified_at ?? now(),
                ]);

                return ['user' => $existingUser, 'is_new' => false];
            }

            // 3. Nouvel utilisateur - retourner les données pour compléter l'inscription
            // On crée le compte user mais pas le profil bachelier (incomplet)
            $user = User::create([
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'role' => 'bachelier', // Rôle par défaut
                'status' => 'pending', // En attente de compléter le profil
                'email_verified_at' => now(), // Email vérifié par le provider
                'last_login_at' => now(),
            ]);

            return ['user' => $user, 'is_new' => true];
        });
    }

    /**
     * Créer le profil bachelier à partir des données sociales ET du formulaire
     */
    public function createBachelierProfile(User $user, array $formData): Bachelier
    {
        // Calculer automatiquement la mention basée sur la note BAC (sur 400 points)
        $mention = Bachelier::calculateMention($formData['note_bac']);
        
        $bachelier = Bachelier::create([
            'user_id' => $user->id,
            // Infos générales
            'nom' => $formData['nom'],
            'prenoms' => $formData['prenoms'],
            'date_naissance' => $formData['date_naissance'],
            'lieu_naissance' => $formData['lieu_naissance'],
            'sexe' => $formData['sexe'],
            'piece_identite_type' => $formData['piece_identite_type'],
            'piece_identite_file' => $formData['piece_identite_file'],
            'telephone_eleve' => $formData['telephone_eleve'],
            'telephone_parent' => $formData['telephone_parent'],
            'email_eleve' => $formData['email_eleve'],
            'email_parent' => $formData['email_parent'],
            'region' => $formData['region'],
            'commune' => $formData['commune'],
            'photo_profil' => $formData['photo_profil'] ?? $user->avatar,
            
            // Infos scolaires
            'matricule_bac' => $formData['matricule_bac'],
            'serie_bac' => $formData['serie_bac'],
            'note_bac' => $formData['note_bac'],
            'mention' => $mention, // Calculée automatiquement
            'annee_bac' => $formData['annee_bac'],
            'etablissement_nom' => $formData['etablissement_nom'],
            'etablissement_type' => $formData['etablissement_type'],
            'collante_bac_file' => $formData['collante_bac_file'],
            
            // Situation socio-économique
            'pensionnaire_internat' => (bool) $formData['pensionnaire_internat'],
            'bourse_scolaire_lycee' => (bool) $formData['bourse_scolaire_lycee'],
            'profession_pere' => $formData['profession_pere'],
            'profession_mere' => $formData['profession_mere'],
            'situations_particulieres' => $formData['situations_particulieres'] ?? null,
            'connexion_internet' => $formData['connexion_internet'],
            'possede_ordinateur' => (bool) $formData['possede_ordinateur'],
            'acces_smartphone' => (bool) $formData['acces_smartphone'],
            'acces_ia' => (bool) $formData['acces_ia'],
            
            // Motivation
            'motivation' => $formData['motivation'],
            
            // Statuts
            'status_candidature' => 'en_attente',
            'status_profil' => 'complet',
        ]);

        // Garder le compte en 'pending' en attente de validation admin
        // Le statut passera à 'active' quand l'admin validera le profil
        // $user->update(['status' => 'active']); // ❌ SUPPRIMÉ - validation admin requise

        // Note: Les scores sont calculés automatiquement via BachelierObserver
        // après la création du profil (événement 'created')

        // Dispatch du job d'extraction IA en arrière-plan
        try {
            $aiData = [
                'piece_identite_file' => $formData['piece_identite_file'],
                'collante_bac_file' => $formData['collante_bac_file'],
                'motivation' => $formData['motivation'],
                'region' => $formData['region'],
                'serie_bac' => $formData['serie_bac'],
                'note_bac' => $formData['note_bac'],
                'mention' => $mention,
                'situations_particulieres' => $formData['situations_particulieres'] ?? []
            ];

            ProcessAiExtraction::dispatch($bachelier->id, $aiData);

            Log::info('Job d\'extraction IA dispatché après création de profil social', [
                'bachelier_id' => $bachelier->id,
                'email' => $formData['email_eleve'],
                'provider' => $user->provider
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du dispatch du job d\'extraction IA (social auth)', [
                'bachelier_id' => $bachelier->id,
                'error' => $e->getMessage()
            ]);
            // Ne pas faire échouer la création du profil si le job échoue
        }

        // Envoyer les notifications par email
        try {
            // Email de confirmation au bachelier
            Mail::to($bachelier->email_eleve)->send(
                new BachelierCandidatureSubmittedMail($bachelier, $user)
            );

            // Email de notification aux administrateurs
            $admins = User::where('role', 'admin')->whereNotNull('email')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(
                    new AdminNewCandidatureMail($bachelier, $user)
                );
            }

            Log::info('Notifications email envoyées après création de candidature', [
                'bachelier_id' => $bachelier->id,
                'admins_notified' => $admins->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi des notifications email', [
                'bachelier_id' => $bachelier->id,
                'error' => $e->getMessage()
            ]);
            // Ne pas faire échouer la création même si les emails échouent
        }

        return $bachelier;
    }

    /**
     * Valider le provider
     */
    protected function validateProvider(string $provider): void
    {
        if (!in_array($provider, self::SUPPORTED_PROVIDERS)) {
            throw new \InvalidArgumentException("Provider '{$provider}' non supporté.");
        }
    }

    /**
     * Obtenir les providers actifs
     */
    public function getActiveProviders(): array
    {
        $active = [];
        
        foreach (self::SUPPORTED_PROVIDERS as $provider) {
            if (!empty(config("services.{$provider}.client_id"))) {
                $active[] = $provider;
            }
        }
        
        return $active;
    }
}

