<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Bachelier;
use App\Models\Partenaire;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    /**
     * Envoyer un code OTP pour la connexion
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte n\'est pas actif. Contactez l\'administration.'
            ], 403);
        }

        // Génération OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Stocker l'OTP avec expiration de 10 minutes
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        // Envoyer l'OTP par email
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code OTP envoyé par email.',
            'data' => [
                'email' => $user->email,
                'expires_in' => 600 // 10 minutes en secondes
            ]
        ], 200);
    }

    /**
     * Vérifier le code OTP et connecter l'utilisateur
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // Vérification de l'OTP
        $otpValid = $user->otp_code && 
                   $user->otp_code === $request->otp &&
                   Carbon::now()->isBefore($user->otp_expires_at);

        if (!$otpValid) {
            return response()->json([
                'success' => false,
                'message' => 'Code OTP invalide ou expiré.'
            ], 401);
        }

        // Clear OTP
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'last_login_at' => now(),
            'email_verified_at' => $user->email_verified_at ?? now()
        ]);

        // Créer un token d'authentification
        $token = $user->createToken('api-token', ['*'], now()->addDays(30))->plainTextToken;

        // Charger les relations selon le rôle
        $userData = $user->toArray();
        if ($user->role === 'bachelier') {
            $user->load('bachelier');
            $userData['bachelier'] = $user->bachelier;
        } elseif ($user->role === 'partenaire') {
            $user->load('partenaire');
            $userData['partenaire'] = $user->partenaire;
        }

        // Enregistrer la statistique de connexion
        $user->statistiquesEngagement()->create([
            'action' => 'login',
            'metadonnees' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'platform' => 'api'
            ],
            'created_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 2592000, // 30 jours en secondes
                'user' => $userData
            ]
        ], 200);
    }

    /**
     * Inscription d'un nouvel utilisateur
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:bachelier,partenaire',
            'terms' => 'accepted'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Créer l'utilisateur
        $user = User::create([
            'email' => $request->email,
            'role' => $request->role,
            'status' => 'pending', // Sera activé après complétion du profil
        ]);

        // Générer un OTP pour la première connexion
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        // Envoyer l'email de bienvenue avec OTP
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user, true));
        } catch (\Exception $e) {
            // Log l'erreur mais continuer l'inscription
        }

        // Créer le profil selon le rôle
        if ($request->role === 'bachelier') {
            Bachelier::create(['user_id' => $user->id]);
        } else {
            Partenaire::create(['user_id' => $user->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Compte créé avec succès. Vérifiez votre email pour le code OTP.',
            'data' => [
                'email' => $user->email,
                'role' => $user->role,
                'expires_in' => 600
            ]
        ], 201);
    }

    /**
     * Déconnexion de l'utilisateur
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Révoquer le token actuel
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ], 200);
    }

    /**
     * Obtenir les informations de l'utilisateur connecté
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = $request->user();
        
        // Charger les relations selon le rôle
        if ($user->role === 'bachelier') {
            $user->load('bachelier.parcoursUniversitaires');
        } elseif ($user->role === 'partenaire') {
            $user->load('partenaire');
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    /**
     * Rafraîchir le token d'authentification
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        
        // Révoquer l'ancien token
        $request->user()->currentAccessToken()->delete();
        
        // Créer un nouveau token
        $token = $user->createToken('api-token', ['*'], now()->addDays(30))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token rafraîchi avec succès',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 2592000
            ]
        ], 200);
    }

    /**
     * Renvoyer le code OTP
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        return $this->sendOtp($request);
    }
}








