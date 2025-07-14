<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Données pour les statistiques
        $stats = [
            'users' => [
                'count' => 112000,
                'icon' => 'ri-user-line',
                'color' => 'purple',
                'label' => 'Utilisateurs'
            ],
            'reports' => [
                'count' => 183000,
                'icon' => 'ri-file-chart-line',
                'color' => 'blue',
                'label' => 'Rapports'
            ],
            'orders' => [
                'count' => 80000,
                'icon' => 'ri-shopping-cart-line',
                'color' => 'green',
                'label' => 'Commandes'
            ],
            'revenue' => [
                'count' => '$112.000',
                'icon' => 'ri-money-dollar-circle-line',
                'color' => 'red',
                'label' => 'Revenus'
            ]
        ];

        // Navigation dynamique
        $navigation = [
            [
                'title' => 'Menu',
                'type' => 'title'
            ],
            [
                'title' => 'Dashboard',
                'icon' => 'ri-dashboard-line',
                'url' => route('dashboard'),
                'active' => true,
                'type' => 'item'
            ],
            [
                'title' => 'Utilisateurs',
                'icon' => 'ri-user-line',
                'url' => '#',
                'active' => false,
                'badge' => '12',
                'type' => 'item'
            ],
            [
                'title' => 'Paramètres',
                'icon' => 'ri-settings-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Rapports',
                'icon' => 'ri-file-chart-line',
                'url' => '#',
                'active' => false,
                'badge' => '3',
                'type' => 'item'
            ],
            [
                'title' => 'Messages',
                'icon' => 'ri-message-2-line',
                'url' => '#',
                'active' => false,
                'badge' => '5',
                'type' => 'item'
            ],
            [
                'title' => 'Notifications',
                'icon' => 'ri-notification-line',
                'url' => '#',
                'active' => false,
                'badge' => '2',
                'type' => 'item'
            ]
        ];

        // Informations utilisateur
        $user = [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'avatar' => asset('wowdash/images/avatar/avatar1.png'),
            'role' => 'Administrateur',
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
}
