<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\Email2FAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\AuditService;

class LoginController extends Controller
{
    protected $authService;
    protected $email2FAService;
    
    public function __construct(AuthService $authService, Email2FAService $email2FAService)
    {
        $this->authService = $authService;
        $this->email2FAService = $email2FAService;
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
        
        // Log de la connexion
        AuditService::logLogin($user, $request);
        
        // Régénérer la session
        $request->session()->regenerate();

        // **NOUVEAU : Envoyer automatiquement le code 2FA**
        $codeSent = $this->email2FAService->sendCode($user, 'login');
        
        if (!$codeSent) {
            return back()->with('error', 'Erreur lors de l\'envoi du code 2FA. Veuillez réessayer.');
        }

        // Redirection vers la page de saisie du code 2FA
        return redirect()->route('2fa.verify')->with('success', 'Un code de vérification a été envoyé à votre adresse email.');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log de la déconnexion AVANT la déconnexion
        if ($user) {
            AuditService::logLogout($user, $request);
        }
        
        $this->authService->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
