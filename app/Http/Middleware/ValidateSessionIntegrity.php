<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Services\AuditService;
use Symfony\Component\HttpFoundation\Response;

class ValidateSessionIntegrity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return $next($request);
        }

        // 1. Vérifier l'intégrité de la session
        if (!$this->validateSessionIntegrity($user, $request)) {
            $this->logSecurityEvent('session_integrity_failed', $user, $request);
            Auth::logout();
            return redirect('/login')->with('error', 'Session compromise détectée. Veuillez vous reconnecter.');
        }

        // 2. Vérifier la cohérence des données de session
        if (!$this->validateSessionData($user, $request)) {
            $this->logSecurityEvent('session_data_inconsistent', $user, $request);
            Auth::logout();
            return redirect('/login')->with('error', 'Données de session incohérentes. Veuillez vous reconnecter.');
        }

        // 3. Mettre à jour le timestamp de dernière activité
        $this->updateLastActivity($user, $request);

        return $next($request);
    }

    /**
     * Valider l'intégrité de la session
     */
    private function validateSessionIntegrity($user, Request $request): bool
    {
        try {
            // Vérifier que la session existe et est valide
            $sessionId = Session::getId();
            if (!$sessionId) {
                return false;
            }

            // Vérifier que l'utilisateur dans la session correspond à l'utilisateur authentifié
            $sessionUserId = Session::get('login_web_' . sha1($user->getAuthIdentifierName()));
            if (!$sessionUserId || $sessionUserId !== $user->getAuthIdentifier()) {
                return false;
            }

            // Vérifier le timestamp de création de session
            $sessionCreated = Session::get('session_created_at');
            if ($sessionCreated && now()->diffInMinutes($sessionCreated) > 480) { // 8 heures max
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur validation intégrité session: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Valider la cohérence des données de session
     */
    private function validateSessionData($user, Request $request): bool
    {
        try {
            // Vérifier que les données critiques sont présentes
            $requiredData = [
                'login_web_' . sha1($user->getAuthIdentifierName()),
                'email_2fa_verified'
            ];

            foreach ($requiredData as $key) {
                if (!Session::has($key)) {
                    return false;
                }
            }

            // Vérifier la cohérence des données utilisateur
            $sessionUserData = Session::get('user_data');
            if ($sessionUserData) {
                if ($sessionUserData['id'] !== $user->id || 
                    $sessionUserData['email'] !== $user->email ||
                    $sessionUserData['role'] !== $user->role) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur validation données session: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour la dernière activité
     */
    private function updateLastActivity($user, Request $request): void
    {
        try {
            Session::put('last_activity', now());
            Session::put('last_ip', $request->ip());
            Session::put('last_user_agent', $request->userAgent());
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour activité: ' . $e->getMessage());
        }
    }

    /**
     * Logger les événements de sécurité
     */
    private function logSecurityEvent(string $event, $user, Request $request): void
    {
        try {
            AuditService::log(
                'SECURITY_EVENT',
                'Sécurité',
                'Session',
                $user->id,
                "Événement de sécurité: {$event} pour {$user->email}",
                $user,
                $request
            );
        } catch (\Exception $e) {
            Log::error('Erreur log sécurité: ' . $e->getMessage());
        }
    }
}
