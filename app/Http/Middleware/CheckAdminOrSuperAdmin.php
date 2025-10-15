<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Bloquer complètement les utilisateurs rcoop
        if ($user->role === 'rcoop') {
            return redirect()->route('dashboard')->with('error', 'Accès refusé. Cette section est réservée aux administrateurs.');
        }
        
        // Autoriser les admin, super-admin ET agc
        if ($user->role === 'admin' || $user->role === 'superadmin' || $user->role === 'agc') {
            return $next($request);
        }

        // Rediriger les autres rôles vers le dashboard avec un message d'erreur
        return redirect()->route('dashboard')->with('error', 'Accès refusé. Cette section est réservée aux administrateurs.');
    }
}
