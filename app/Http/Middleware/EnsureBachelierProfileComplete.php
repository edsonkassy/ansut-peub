<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureBachelierProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si l'utilisateur est un bachelier mais n'a pas de profil complet
            if ($user->role === 'bachelier' && !$user->bachelier) {
                // Ne pas rediriger si on est déjà sur la page de complétion de profil
                if (!$request->is('auth/complete-profile*')) {
                    return redirect()->route('auth.complete-profile')
                                   ->with('info', 'Veuillez compléter votre profil pour accéder à cette page.');
                }
            }
        }
        
        return $next($request);
    }
}
