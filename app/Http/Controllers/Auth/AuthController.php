<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Bachelier;
use App\Models\Partenaire;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        if ($request->has('redirect_to')) {
            session(['redirect_to' => $request->redirect_to]);
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showVerify()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        if (!session('email')) {
            return redirect()->route('auth.login')->with('error', 'Session expirée. Veuillez recommencer.');
        }

        return view('auth.verify-otp');
    }

    public function sendOtp(Request $request)
    {
        $context = $request->input('context', 'login');
        
        \Log::info('OTP Request', [
            'email' => $request->email,
            'context' => $context
        ]);
        
        // Validation différente selon le contexte
        if ($context === 'register') {
            $request->validate([
                'email' => 'required|email|unique:users,email'
            ], [
                'email.unique' => 'Cet email est déjà enregistré. Utilisez la page de connexion.'
            ]);
            
            // Créer un nouveau compte pour l'inscription via OTP
            // Le profil bachelier sera créé après dans complete-profile
            $user = User::create([
                'email' => $request->email,
                'role' => 'bachelier',
                'status' => 'pending',
            ]);
            
            \Log::info('New user created via OTP', ['user_id' => $user->id]);
        } else {
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ], [
                'email.exists' => 'Aucun compte trouvé avec cet email. Veuillez vous inscrire d\'abord.'
            ]);

            $user = User::where('email', $request->email)->first();

            \Log::info('User found for login', [
                'user_id' => $user->id,
                'status' => $user->status,
                'role' => $user->role,
                'has_bachelier_profile' => $user->bachelier ? true : false
            ]);

            // Vérifier le statut du compte
            // 'pending' est autorisé pour finaliser le profil
            // 'suspended' ou 'banned' sont bloqués
            if (in_array($user->status, ['suspended', 'banned', 'inactive'])) {
                \Log::warning('User account blocked', [
                    'user_id' => $user->id,
                    'status' => $user->status
                ]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Votre compte est ' . $user->status . '. Contactez l\'administration pour plus d\'informations.');
            }
            
            // Si l'utilisateur est 'pending' sans profil bachelier, il peut se connecter pour finaliser
            if ($user->status === 'pending' && !$user->bachelier) {
                \Log::info('User with pending status logging in to complete profile', [
                    'user_id' => $user->id
                ]);
            }
        }

        // Liste des emails de bacheliers du seeder qui bypasses l'OTP
        $bypassEmails = [
            'fatou.diallo@example.com',
            'koffi.kouassi@example.com',
            'alexdegny@gmail.com',
            'kokouaserge3@gmail.com',
            'marckouassi@innoving.io',
            'traore.issa@peub-test.ansut.ci',
            'admin@peub.ansut.ci',
            'ifcorsaire@gmail.com'
        ];

        // Si l'email est dans la liste de bypass, connecter directement
        if (in_array($request->email, $bypassEmails)) {
            // Mettre à jour les informations de connexion
            $user->update([
                'last_login_at' => now(),
                'email_verified_at' => $user->email_verified_at ?? now()
            ]);

            // Connecter l'utilisateur
            Auth::login($user);

            // Enregistrer la statistique de connexion
            $user->statistiquesEngagement()->create([
                'action' => 'login',
                'metadonnees' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'bypass_otp' => true
                ],
                'created_at' => now()
            ]);

            // Récupérer l'URL de redirection depuis la session
            $redirectTo = session('redirect_to');
            
            // Nettoyer la session
            session()->forget('redirect_to');

            // Rediriger vers l'URL prévue ou le dashboard
            return redirect($redirectTo ?? route('dashboard'))->with('success', 'Connexion réussie !');
        }

        // Génération et envoi OTP normal pour les autres utilisateurs
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP with 10 minutes expiry
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new OtpMail($otp, $user));

        // Store email in session for verification
        session(['email' => $user->email]);

        return redirect()->route('auth.verify')->with('success', 'Code OTP envoyé par email.');
    }

    /**
     * Ancienne méthode register - maintenant remplacée par sendOtp avec context='register'
     * Conservée pour compatibilité avec d'anciennes routes si nécessaire
     */
    public function register(Request $request)
    {
        // Rediriger vers la nouvelle méthode d'inscription
        return $this->sendOtp($request->merge(['context' => 'register']));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $email = session('email');
        if (!$email) {
            return redirect()->route('auth.login')->with('error', 'Session expirée.');
        }

        $user = User::where('email', $email)->first();

        // Liste des emails de bacheliers du seeder qui bypasses l'OTP
        $bypassEmails = [
            'fatou.diallo@example.com',
            'koffi.kouassi@example.com',
            'alexdegny@gmail.com',
            'kokouaserge3@gmail.com',
            'marckouassi@innoving.io'
        ];

        // Si l'email est dans la liste de bypass, on accepte n'importe quel OTP
        $otpValid = false;
        if (in_array($email, $bypassEmails)) {
            $otpValid = true; // Bypass pour les emails des bacheliers du seeder
        } else {
            // Vérification normale de l'OTP
            $otpValid = $user && 
                       $user->otp_code && 
                       $user->otp_code === $request->otp &&
                       Carbon::now()->isBefore($user->otp_expires_at);
        }

        if (!$user || !$otpValid) {
            return back()->with('error', 'Code OTP invalide ou expiré.');
        }

        // Clear OTP
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'last_login_at' => now(),
            'email_verified_at' => $user->email_verified_at ?? now()
        ]);

        // Login user
        Auth::login($user);

        // Vérifier si l'utilisateur doit compléter son profil
        if ($user->role === 'bachelier' && !$user->bachelier) {
            // Nouveau bachelier sans profil complet
            return redirect()->route('auth.complete-profile')
                           ->with('info', 'Veuillez compléter votre profil pour finaliser votre inscription.');
        }

        // Track login
        $user->statistiquesEngagement()->create([
            'action' => 'login',
            'metadonnees' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ],
            'created_at' => now()
        ]);

        // Get redirect URL from session
        $redirectTo = session('redirect_to');
        
        // Clear session
        session()->forget(['email', 'redirect_to']);

        // Redirect to intended URL or dashboard
        return redirect($redirectTo ?? route('dashboard'))->with('success', 'Connexion réussie !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Déconnexion réussie.');
    }
} 