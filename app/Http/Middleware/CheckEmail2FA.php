<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Email2FAService;
use Symfony\Component\HttpFoundation\Response;

class CheckEmail2FA
{
    protected $email2FAService;

    public function __construct(Email2FAService $email2FAService)
    {
        $this->email2FAService = $email2FAService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return $next($request);
        }

        // EXCEPTION TEMPORAIRE : Permettre l'accès aux routes de test et 2FA
        if ($request->is('test-*') || $request->is('debug-*') || $request->is('clean-*') || $request->is('2fa/*')) {
            return $next($request);
        }

        // Vérifier si la session 2FA est vérifiée
        if (!session('email_2fa_verified')) {
            // Rediriger vers la page de vérification 2FA
            return redirect()->route('2fa.verify')->with('info', 'Vérification 2FA requise pour continuer.');
        }

        return $next($request);
    }
}
