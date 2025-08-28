<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $authService;
    
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        // Validation des données
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Authentification par username
        $result = $this->authService->authenticateByUsername(
            $request->username,
            $request->password
        );

        if (!$result['success']) {
            switch ($result['error']) {
                case 'username_not_found':
                    throw ValidationException::withMessages([
                        'username' => ['Ce nom d\'utilisateur n\'existe pas.'],
                    ]);
                case 'invalid_password':
                    throw ValidationException::withMessages([
                        'password' => ['Le mot de passe est incorrect.'],
                    ]);
                case 'account_inactive':
                    throw ValidationException::withMessages([
                        'username' => ['Votre compte est inactif. Veuillez contacter l\'administrateur.'],
                    ]);
                default:
                    throw ValidationException::withMessages([
                        'username' => ['Une erreur est survenue lors de l\'authentification.'],
                    ]);
            }
        }

        $user = $result['user'];

        // Connexion de l'utilisateur
        $this->authService->login($user, $request->boolean('remember'));
        
        // Régénérer la session
        $request->session()->regenerate();

        // Redirection vers le dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $this->authService->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
