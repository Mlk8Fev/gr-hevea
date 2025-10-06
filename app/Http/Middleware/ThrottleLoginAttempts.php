<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\AuditService;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginAttempts
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = "login_attempts_{$ip}";
        
        $attempts = Cache::get($key, []);
        $now = now();
        
        // Nettoyer les tentatives anciennes (plus de 15 minutes)
        $attempts = array_filter($attempts, function($timestamp) use ($now) {
            return $now->diffInMinutes($timestamp) <= 15;
        });
        
        // Vérifier si l'IP a dépassé la limite (5 tentatives en 15 minutes)
        if (count($attempts) >= 5) {
            $this->logFailedAttempt($request, 'rate_limit_exceeded');
            
            // Ajouter l'IP à la blacklist temporaire
            $this->addToBlacklist($ip);
            
            // CORRECTION : Calculer le temps restant correctement
            $oldestAttempt = min($attempts);
            $blockedUntil = $oldestAttempt->addMinutes(15);
            $retryAfter = max(0, $now->diffInSeconds($blockedUntil, false));
            
            // Si le temps est écoulé, nettoyer le cache et permettre la connexion
            if ($retryAfter <= 0) {
                Cache::forget($key);
                return $next($request);
            }
            
            // Rediriger vers la page de login avec un message d'erreur
            return redirect()->route('login')->with([
                'error' => 'Trop de tentatives de connexion. Veuillez réessayer dans 15 minutes.',
                'retry_after' => $retryAfter,
                'blocked_until' => $blockedUntil->toISOString()
            ]);
        }
        
        // Ajouter cette tentative
        $attempts[] = $now;
        Cache::put($key, $attempts, now()->addMinutes(15));
        
        return $next($request);
    }

    /**
     * Logger les tentatives échouées
     */
    private function logFailedAttempt(Request $request, string $reason): void
    {
        AuditService::log(
            'LOGIN_FAILED',
            'Authentification',
            'User',
            null,
            "Tentative de connexion échouée: {$reason}",
            null,
            $request,
            null,
            null,
            false,
            null,
            [
                'reason' => $reason,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString()
            ]
        );

        Log::warning("Tentative de connexion échouée", [
            'reason' => $reason,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'username' => $request->input('username')
        ]);
    }

    /**
     * Ajouter une IP à la blacklist
     */
    private function addToBlacklist(string $ip): void
    {
        $blacklistedIps = Cache::get('blacklisted_ips', []);
        
        if (!in_array($ip, $blacklistedIps)) {
            $blacklistedIps[] = $ip;
            Cache::put('blacklisted_ips', $blacklistedIps, now()->addHours(24));
        }
    }
}
