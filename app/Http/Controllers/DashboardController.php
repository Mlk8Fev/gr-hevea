<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NavigationService;

class DashboardController extends Controller
{
    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    public function index()
    {
        // Utiliser le service de navigation
        $navigation = $this->navigationService->getNavigation();
        $stats = $this->navigationService->getDashboardStats();

        // Informations utilisateur
        $user = [
            'name' => auth()->user()->full_name,
            'email' => auth()->user()->email,
            'avatar' => asset('wowdash/images/avatar/avatar1.png'),
            'role' => ucfirst(auth()->user()->role),
            'lastLogin' => 'Il y a 2 heures'
        ];

        // Données pour l'activité récente
        $recentActivity = [
            [
                'action' => 'Connexion',
                'user' => 'Jean Dupont',
                'time' => 'Il y a 2 min',
                'icon' => 'ri-login-circle-line',
                'color' => 'success'
            ],
            [
                'action' => 'Nouveau rapport',
                'user' => 'Marie Martin',
                'time' => 'Il y a 5 min',
                'icon' => 'ri-file-add-line',
                'color' => 'primary'
            ],
            [
                'action' => 'Mise à jour',
                'user' => 'Pierre Durand',
                'time' => 'Il y a 10 min',
                'icon' => 'ri-refresh-line',
                'color' => 'warning'
            ],
            [
                'action' => 'Suppression',
                'user' => 'Sophie Bernard',
                'time' => 'Il y a 15 min',
                'icon' => 'ri-delete-bin-line',
                'color' => 'danger'
            ]
        ];

        // Données pour les tâches
        $tasks = [
            [
                'name' => 'Mise à jour système',
                'priority' => 'danger',
                'status' => 'warning',
                'progress' => 75,
                'assignee' => 'Jean Dupont',
                'deadline' => 'Aujourd\'hui'
            ],
            [
                'name' => 'Rapport mensuel',
                'priority' => 'warning',
                'status' => 'info',
                'progress' => 30,
                'assignee' => 'Marie Martin',
                'deadline' => 'Demain'
            ],
            [
                'name' => 'Maintenance',
                'priority' => 'success',
                'status' => 'success',
                'progress' => 100,
                'assignee' => 'Pierre Durand',
                'deadline' => 'Terminé'
            ],
            [
                'name' => 'Migration données',
                'priority' => 'info',
                'status' => 'warning',
                'progress' => 45,
                'assignee' => 'Sophie Bernard',
                'deadline' => 'Cette semaine'
            ]
        ];

        // Notifications
        $notifications = [
            [
                'title' => 'Nouveau message',
                'message' => 'Vous avez reçu un nouveau message',
                'time' => 'Il y a 5 min',
                'type' => 'message'
            ],
            [
                'title' => 'Mise à jour disponible',
                'message' => 'Une nouvelle version est disponible',
                'time' => 'Il y a 1 heure',
                'type' => 'update'
            ],
            [
                'title' => 'Tâche terminée',
                'message' => 'La tâche "Maintenance" a été terminée',
                'time' => 'Il y a 2 heures',
                'type' => 'success'
            ]
        ];

        return view('dashboard', compact('stats', 'navigation', 'user', 'recentActivity', 'tasks', 'notifications'));
    }

    // Dashboard Admin - Accès complet
    public function adminDashboard()
    {
        $user = auth()->user();
        $navigation = $this->navigationService->getNavigation();
        $stats = $this->navigationService->getDashboardStats();

        return view('dashboards.admin', compact('stats', 'user', 'navigation'));
    }

    // Dashboard Manager - Accès limité
    public function managerDashboard()
    {
        $user = auth()->user();
        $navigation = $this->navigationService->getNavigation();
        $stats = $this->navigationService->getDashboardStats();

        return view('dashboards.manager', compact('stats', 'user', 'navigation'));
    }

    // Dashboard User - Accès basique
    public function userDashboard()
    {
        $user = auth()->user();
        $navigation = $this->navigationService->getNavigation();
        $stats = $this->navigationService->getDashboardStats();

        return view('dashboards.user', compact('stats', 'user', 'navigation'));
    }
}
