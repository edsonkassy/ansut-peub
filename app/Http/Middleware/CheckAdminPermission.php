<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur est admin
        if (!$user->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        // Vérifier si l'utilisateur a la permission requise
        if (!$user->hasAdminPermission($permission)) {
            abort(403, 'Vous n\'avez pas la permission d\'accéder à cette fonctionnalité.');
        }

        return $next($request);
    }
} 