<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckBoursier
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

        if ($user->role !== 'bachelier') {
            abort(403, 'Accès réservé aux bacheliers.');
        }

        $bachelier = $user->bachelier;

        if (!$bachelier || !$bachelier->boursier_peub) {
            abort(403, 'Accès réservé aux boursiers PEUB.');
        }

        return $next($request);
    }
} 