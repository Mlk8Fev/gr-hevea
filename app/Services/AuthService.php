<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Authentifier un utilisateur par username
     */
    public function authenticateByUsername(string $username, string $password): array
    {
        // Rechercher l'utilisateur par username
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            return ['success' => false, 'error' => 'username_not_found'];
        }
        
        // Vérifier le mot de passe
        if (!Hash::check($password, $user->password)) {
            return ['success' => false, 'error' => 'invalid_password'];
        }
        
        // Vérifier que l'utilisateur est actif
        if ($user->status !== 'active') {
            return ['success' => false, 'error' => 'account_inactive', 'user' => $user];
        }
        
        return ['success' => true, 'user' => $user];
    }
    
    /**
     * Connecter un utilisateur
     */
    public function login(User $user, bool $remember = false): bool
    {
        // Utiliser le guard standard de Laravel
        Auth::login($user, $remember);
        
        // Vérifier que l'utilisateur est bien connecté
        return Auth::check() && Auth::id() === $user->id;
    }
    
    /**
     * Déconnecter l'utilisateur
     */
    public function logout(): void
    {
        Auth::logout();
    }
} 