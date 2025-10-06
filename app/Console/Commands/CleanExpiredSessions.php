<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CleanExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sessions:clean {--force : Force le nettoyage sans confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Nettoie les sessions expirées et les données de cache associées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Début du nettoyage des sessions expirées...');

        try {
            // 1. Nettoyer les sessions expirées en base
            $deletedSessions = $this->cleanDatabaseSessions();
            
            // 2. Nettoyer le cache des sessions
            $cleanedCache = $this->cleanSessionCache();
            
            // 3. Nettoyer les données de sécurité expirées
            $cleanedSecurity = $this->cleanSecurityData();

            $this->info("✅ Nettoyage terminé :");
            $this->line("   - Sessions supprimées : {$deletedSessions}");
            $this->line("   - Cache nettoyé : {$cleanedCache}");
            $this->line("   - Données sécurité : {$cleanedSecurity}");

            Log::info("Nettoyage sessions terminé", [
                'sessions_deleted' => $deletedSessions,
                'cache_cleaned' => $cleanedCache,
                'security_cleaned' => $cleanedSecurity
            ]);

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors du nettoyage : " . $e->getMessage());
            Log::error("Erreur nettoyage sessions", ['error' => $e->getMessage()]);
            return 1;
        }

        return 0;
    }

    /**
     * Nettoyer les sessions expirées en base de données
     */
    private function cleanDatabaseSessions(): int
    {
        $this->line('🗄️  Nettoyage des sessions en base...');
        
        $deletedCount = DB::table('sessions')
            ->where('last_activity', '<', now()->subMinutes(30)->timestamp)
            ->delete();

        return $deletedCount;
    }

    /**
     * Nettoyer le cache des sessions
     */
    private function cleanSessionCache(): int
    {
        $this->line('💾 Nettoyage du cache des sessions...');
        
        $cleanedCount = 0;
        
        // Nettoyer les clés de cache liées aux sessions
        $sessionKeys = Cache::get('session_keys', []);
        foreach ($sessionKeys as $key) {
            if (Cache::has($key)) {
                Cache::forget($key);
                $cleanedCount++;
            }
        }
        
        // Nettoyer les données de sécurité expirées
        Cache::forget('session_keys');
        
        return $cleanedCount;
    }

    /**
     * Nettoyer les données de sécurité expirées
     */
    private function cleanSecurityData(): int
    {
        $this->line('🛡️  Nettoyage des données de sécurité...');
        
        $cleanedCount = 0;
        
        // Nettoyer les tentatives de connexion expirées
        $loginAttempts = Cache::get('login_attempts', []);
        $cleanedAttempts = 0;
        
        foreach ($loginAttempts as $ip => $attempts) {
            $validAttempts = array_filter($attempts, function($attempt) {
                return now()->diffInMinutes($attempt) < 15;
            });
            
            if (count($validAttempts) !== count($attempts)) {
                Cache::put("login_attempts_{$ip}", $validAttempts, now()->addMinutes(15));
                $cleanedAttempts++;
            }
        }
        
        // Nettoyer les IPs blacklistées expirées
        $blacklistedIps = Cache::get('blacklisted_ips', []);
        $validBlacklist = array_filter($blacklistedIps, function($ip) {
            return Cache::has("blacklisted_ip_{$ip}");
        });
        
        if (count($validBlacklist) !== count($blacklistedIps)) {
            Cache::put('blacklisted_ips', $validBlacklist, now()->addHours(24));
            $cleanedCount++;
        }
        
        return $cleanedCount + $cleanedAttempts;
    }
}
