<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckVerifiedPartner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        $user = Auth::user();

        if ($user->role !== 'partenaire') {
            abort(403, 'Accès réservé aux partenaires.');
        }

        $partenaire = $user->partenaire;

        if (!$partenaire || $partenaire->status_verification !== 'verified') {
            return redirect()->route('partenaire.profile')
                ->with('warning', 'Votre organisation doit être vérifiée avant d\'accéder à cette fonctionnalité.');
        }

        return $next($request);
    }
} 