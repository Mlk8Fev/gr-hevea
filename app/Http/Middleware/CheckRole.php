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

        // Si un ou plusieurs rôles sont demandés
        if ($role) {
            $allowedRoles = explode(',', $role);
            $allowedRoles = array_map('trim', $allowedRoles);
            
            if (!in_array($user->role, $allowedRoles) && !$user->isSiege()) {
                abort(403, 'Accès non autorisé');
            }
        }

        return $next($request);
    }
}
