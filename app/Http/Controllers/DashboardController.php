<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationService;

class DashboardController extends Controller
{
    protected $navigationService;
    
    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    /**
     * Afficher le dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Navigation pour la sidebar (utilise le NavigationService)
        $navigation = $this->navigationService->getNavigation();
        
        // Statistiques pour le dashboard (utilise le NavigationService)
        $stats = $this->navigationService->getDashboardStats();
        
        // Données pour le dashboard
        $data = [
            'user' => [
                'name' => $user->name,
                'username' => $user->username,
                'role' => $user->role,
                'avatar' => asset('wowdash/images/avatar/user1.png'), // Avatar par défaut
            ],
            'notifications' => [
                [
                    'title' => 'Bienvenue !',
                    'message' => 'Connexion réussie au système',
                    'time' => 'À l\'instant'
                ]
            ],
            'navigation' => $navigation,
            'stats' => $stats
        ];
        
        return view('dashboard', $data);
    }
}
