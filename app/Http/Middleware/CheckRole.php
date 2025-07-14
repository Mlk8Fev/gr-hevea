<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Si un rôle spécifique est demandé
        if ($role) {
            if ($user->role !== $role && !$user->isSiege()) {
                abort(403, 'Accès non autorisé');
            }
        }

        return $next($request);
    }
}
