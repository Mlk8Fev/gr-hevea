<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAgcAccess
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
        
        // Si c'est un AGC, vérifier les routes autorisées
        if ($user->role === 'agc') {
            $allowedRoutes = [
                'admin.producteurs.index',
                'admin.producteurs.show',
                'admin.connaissements.index',
                'admin.connaissements.show',
                'admin.tickets-pesee.index',
                'admin.tickets-pesee.show',
                'admin.farmer-lists.index',
                'admin.farmer-lists.show',
                'admin.statistiques.index',
            ];
            
            $currentRoute = $request->route()->getName();
            
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('dashboard')->with('error', 'Accès refusé. Cette section n\'est pas autorisée pour votre rôle.');
            }
        }
        
        return $next($request);
    }
}
