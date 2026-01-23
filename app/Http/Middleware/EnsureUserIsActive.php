<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        // Si l'utilisateur est pending
        if ($user->status === 'pending') {
            // Vérifier s'il a un profil bachelier complet
            if ($user->role === 'bachelier' && $user->bachelier) {
                // Profil complet mais en attente de validation admin
                return redirect()->route('auth.pending-validation')
                    ->with('info', 'Votre profil est en cours d\'examen par notre équipe.');
            }
            
            // Profil incomplet, rediriger pour compléter
            return redirect()->route('auth.complete-profile')
                ->with('info', 'Veuillez compléter votre profil pour continuer.');
        }
        
        // Si l'utilisateur est suspendu, banni ou inactif
        if (in_array($user->status, ['suspended', 'banned', 'inactive'])) {
            Auth::logout();
            
            $messages = [
                'suspended' => 'Votre compte a été suspendu. Contactez l\'administration pour plus d\'informations.',
                'banned' => 'Votre compte a été banni. Contactez l\'administration si vous pensez qu\'il s\'agit d\'une erreur.',
                'inactive' => 'Votre compte est inactif. Contactez l\'administration pour le réactiver.',
            ];
            
            return redirect()->route('login')
                ->with('error', $messages[$user->status] ?? 'Votre compte n\'est pas actif.');
        }
        
        // Status 'active' : laisser passer
        return $next($request);
    }
}

