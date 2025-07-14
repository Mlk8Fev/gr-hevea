<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Vérifier si l'utilisateur est superadmin
        if (!$user->isSuperAdmin()) {
            abort(403, 'Accès réservé aux super administrateurs');
        }

        return $next($request);
    }
}
