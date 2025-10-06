<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\AuditService;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionSecurity
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

        // EXCEPTION : Les super-admin ont des règles de sécurité plus souples
        $isSuperAdmin = $user->role === 'superadmin';
        
        // 1. Vérifier les sessions multiples (sauf pour super-admin)
        if (!$isSuperAdmin && $this->hasMultipleSessions($user)) {
            $this->logSecurityEvent('multiple_sessions', $user, $request);
            Auth::logout();
            return redirect('/login')->with('error', 'Session multiple détectée. Veuillez vous reconnecter.');
        }

        // 2. Vérifier le timeout d'inactivité (60 minutes pour super-admin, 30 pour les autres)
        $timeoutMinutes = $isSuperAdmin ? 60 : 30;
        if ($this->isSessionExpired($user, $timeoutMinutes)) {
            $this->logSecurityEvent('session_timeout', $user, $request);
            Auth::logout();
            return redirect('/login')->with('error', 'Session expirée par inactivité. Veuillez vous reconnecter.');
        }

        // 3. Vérifier la rotation des tokens (30 minutes pour super-admin, 15 pour les autres)
        $rotationMinutes = $isSuperAdmin ? 30 : 15;
        if ($this->shouldRotateToken($user, $rotationMinutes)) {
            $this->rotateSessionToken($user);
        }

        // 4. Vérifier la géolocalisation suspecte (sauf pour super-admin)
        if (!$isSuperAdmin && $this->hasSuspiciousLocation($user, $request)) {
            $this->logSecurityEvent('suspicious_location', $user, $request);
            Auth::logout();
            return redirect('/login')->with('error', 'Géolocalisation suspecte détectée. Veuillez vous reconnecter.');
        }

        // 5. Vérifier la blacklist IP (sauf pour super-admin)
        if (!$isSuperAdmin && $this->isIpBlacklisted($request->ip())) {
            $this->logSecurityEvent('blacklisted_ip', $user, $request);
            Auth::logout();
            return redirect('/login')->with('error', 'Accès refusé depuis cette adresse IP.');
        }

        // 6. Mettre à jour la dernière activité
        $this->updateLastActivity($user, $request);

        return $next($request);
    }

    /**
     * Détecter les sessions multiples
     */
    private function hasMultipleSessions($user): bool
    {
        $currentSessionId = session()->getId();
        $userSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->where('last_activity', '>', now()->subMinutes(30)->timestamp)
            ->count();

        return $userSessions > 0;
    }

    /**
     * Vérifier si la session est expirée
     */
    private function isSessionExpired($user, int $timeoutMinutes = 30): bool
    {
        $lastActivity = Cache::get("user_activity_{$user->id}");
        
        if (!$lastActivity) {
            return false;
        }

        return now()->diffInMinutes($lastActivity) > $timeoutMinutes;
    }

    /**
     * Vérifier si le token doit être roté
     */
    private function shouldRotateToken($user, int $rotationMinutes = 15): bool
    {
        $lastRotation = Cache::get("token_rotation_{$user->id}");
        
        if (!$lastRotation) {
            return true;
        }

        return now()->diffInMinutes($lastRotation) > $rotationMinutes;
    }

    /**
     * Vérifier la géolocalisation suspecte
     */
    private function hasSuspiciousLocation($user, Request $request): bool
    {
        $currentIp = $request->ip();
        $lastKnownIp = Cache::get("user_ip_{$user->id}");
        
        if (!$lastKnownIp) {
            Cache::put("user_ip_{$user->id}", $currentIp, now()->addHours(24));
            return false;
        }

        // Vérifier si l'IP a changé de manière suspecte
        if ($currentIp !== $lastKnownIp) {
            // Pour simplifier, on considère comme suspect si l'IP change
            // Dans un vrai système, on utiliserait une API de géolocalisation
            Cache::put("user_ip_{$user->id}", $currentIp, now()->addHours(24));
            return true;
        }

        return false;
    }

    /**
     * Vérifier si l'IP est blacklistée
     */
    private function isIpBlacklisted(string $ip): bool
    {
        $blacklistedIps = Cache::get('blacklisted_ips', []);
        return in_array($ip, $blacklistedIps);
    }

    /**
     * Roter le token de session
     */
    private function rotateSessionToken($user): void
    {
        session()->regenerate();
        Cache::put("token_rotation_{$user->id}", now(), now()->addHours(24));
        
        Log::info("Token de session roté pour l'utilisateur {$user->id}");
    }

    /**
     * Mettre à jour la dernière activité
     */
    private function updateLastActivity($user, Request $request): void
    {
        Cache::put("user_activity_{$user->id}", now(), now()->addHours(24));
    }

    /**
     * Logger les événements de sécurité
     */
    private function logSecurityEvent(string $event, $user, Request $request): void
    {
        AuditService::log(
            'SECURITY_EVENT',
            'Sécurité',
            'User',
            $user->id,
            "Événement de sécurité: {$event}",
            $user,
            $request,
            null,
            null,
            false,
            null,
            [
                'event_type' => $event,
                'user_role' => $user->role,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString()
            ]
        );

        Log::warning("Événement de sécurité détecté", [
            'event' => $event,
            'user_id' => $user->id,
            'user_role' => $user->role,
            'ip' => $request->ip()
        ]);
    }
}
