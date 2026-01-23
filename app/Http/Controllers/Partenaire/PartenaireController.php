<?php

namespace App\Http\Controllers\Partenaire;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Partenaire;
use App\Models\Opportunite;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartenaireRegistrationConfirmation;
use App\Services\LogoService;

class PartenaireController extends Controller
{
    protected $imageService;
    protected $logoService;

    public function __construct(ImageOptimizationService $imageService, LogoService $logoService)
    {
        $this->imageService = $imageService;
        $this->logoService = $logoService;
    }

    /**
     * Affiche le dashboard partenaire
     */
    public function dashboard()
    {
        $partenaire = auth()->user()->partenaire;
        
        // Statistiques du partenaire
        $stats = [
            'opportunites_count' => Opportunite::where('partenaire_id', $partenaire->id)->count(),
            'opportunites_actives' => Opportunite::where('partenaire_id', $partenaire->id)
                ->where('status', 'published')
                ->where('date_limite_candidature', '>', now())
                ->count(),
            'candidatures_total' => Candidature::whereHas('opportunite', function($query) use ($partenaire) {
                $query->where('partenaire_id', $partenaire->id);
            })->count(),
            'candidatures_en_attente' => Candidature::whereHas('opportunite', function($query) use ($partenaire) {
                $query->where('partenaire_id', $partenaire->id);
            })->where('status', 'pending')->count(),
        ];

        // Opportunités récentes
        $opportunites_recentes = Opportunite::where('partenaire_id', $partenaire->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Candidatures récentes
        $candidatures_recentes = Candidature::whereHas('opportunite', function($query) use ($partenaire) {
            $query->where('partenaire_id', $partenaire->id);
        })
        ->with(['bachelier', 'opportunite'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('partenaire.dashboard', compact('stats', 'opportunites_recentes', 'candidatures_recentes'));
    }

    /**
     * Affiche le profil du partenaire
     */
    public function profile()
    {
        $partenaire = auth()->user()->partenaire;
        return view('partenaire.profile', compact('partenaire'));
    }

    /**
     * Met à jour le profil du partenaire
     */
    public function updateProfile(Request $request)
    {
        $partenaire = auth()->user()->partenaire;
        
        $request->validate([
            'nom_organisation' => 'required|string|max:255',
            'type_organisation' => 'required|in:entreprise,institution_academique,ong,gouvernement',
            'secteur_activite' => 'nullable|string|max:255',
            'region' => 'required|string|max:255',
            'commune' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5120', // 5MB
            'personne_contact_nom' => 'required|string|max:255',
            'personne_contact_fonction' => 'required|string|max:255',
            'personne_contact_telephone' => 'required|string|max:20',
        ]);

        // Mise à jour des informations de base
        $partenaire->update($request->only([
            'nom_organisation',
            'type_organisation',
            'secteur_activite',
            'region',
            'commune',
            'adresse',
            'telephone',
            'site_web',
            'description',
            'personne_contact_nom',
            'personne_contact_fonction',
            'personne_contact_telephone',
        ]));

        // Traitement du logo avec optimisation
            if ($request->hasFile('logo')) {
            $logoPath = $this->imageService->optimizeAndStore(
                $request->file('logo'), 
                'logos_partenaires'
            );
            $partenaire->update(['logo' => $logoPath]);
        }

        return redirect()->route('partenaire.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Affiche le formulaire d'inscription partenaire
     */
    public function create()
    {
        return view('partenaire.register');
    }

    /**
     * Traite l'inscription d'un nouveau partenaire
     */
    public function store(Request $request)
    {
        // Vérifier si le logo est un objet vide et le retirer de la requête
        if (!$request->hasFile('logo') || (is_array($request->logo) && empty($request->logo))) {
            $request->request->remove('logo');
        }

        $request->validate([
            // Informations organisation
            'nom_organisation' => 'required|string|max:255',
            'type_organisation' => 'required|in:entreprise,institution_academique,ong,gouvernement',
            'secteur_activite' => 'nullable|string|max:255',
            'region' => 'required|string|max:255',
            'commune' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'site_web' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB max
            
            // Personne de contact
            'personne_contact_nom' => 'required|string|max:255',
            'personne_contact_fonction' => 'required|string|max:255',
            'personne_contact_telephone' => 'required|string|max:20',
            'personne_contact_email' => 'required|email|max:255|unique:users,email',
            
            // Acceptation des conditions
            'accepter_conditions' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Créer l'utilisateur avec l'email de la personne de contact
            $user = User::create([
                'email' => $request->personne_contact_email,
                'role' => 'partenaire',
                'status' => 'active', // Activer directement le compte
                'email_verified_at' => now(), // Email considéré comme vérifié
            ]);

            // Générer un code OTP pour la première connexion
            $otp = rand(100000, 999999);
            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addHours(24)
            ]);

            // Gérer l'upload du logo
            $logoPath = null;
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $result = $this->logoService->processAndStoreLogo($request->file('logo'));
                
                if (!$result['success']) {
                    throw new \Exception($result['error']);
                }
                
                $logoPath = $result['path'];
            }

            // Créer le partenaire avec l'utilisateur associé
            $partenaire = Partenaire::create([
                'user_id' => $user->id,
                'nom_organisation' => $request->nom_organisation,
                'type_organisation' => $request->type_organisation,
                'secteur_activite' => $request->secteur_activite,
                'region' => $request->region,
                'commune' => $request->commune,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'site_web' => $request->site_web,
                'description' => $request->description,
                'logo' => $logoPath,
                'personne_contact_nom' => $request->personne_contact_nom,
                'personne_contact_fonction' => $request->personne_contact_fonction,
                'personne_contact_telephone' => $request->personne_contact_telephone,
                'personne_contact_email' => $request->personne_contact_email,
                'status_verification' => 'pending',
            ]);

            DB::commit();

            // Envoyer un email de confirmation avec le code OTP
            Mail::to($request->personne_contact_email)->send(new PartenaireRegistrationConfirmation($partenaire, $otp));

            return redirect()->route('partenaire.register.success')
                ->with('success', 'Votre compte partenaire a été créé avec succès ! Veuillez vérifier votre email pour obtenir votre code de connexion.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Erreur lors de l\'inscription du partenaire', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
        }
    }

    /**
     * Affiche la page de succès après inscription
     */
    public function success()
    {
        return view('partenaire.register-success');
    }
}
