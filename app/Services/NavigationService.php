<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class NavigationService
{
    public function getNavigation()
    {
        $user = Auth::user();
        $navigation = [];

        // Menu Dashboard pour tous
        $navigation[] = [
            'title' => 'Dashboard',
            'icon' => 'ri-dashboard-line',
            'url' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
            'type' => 'item'
        ];

        // Navigation basée sur le rôle
        switch ($user->role) {
            case 'superadmin':
                $navigation = array_merge($navigation, $this->getSuperAdminNavigation());
                break;
            case 'admin':
                $navigation = array_merge($navigation, $this->getAdminNavigation());
                break;
            case 'manager':
                $navigation = array_merge($navigation, $this->getManagerNavigation());
                break;
            case 'user':
                $navigation = array_merge($navigation, $this->getUserNavigation());
                break;
        }

        return $navigation;
    }

    private function getSuperAdminNavigation()
    {
        return [
            [
                'title' => 'Administration',
                'type' => 'title'
            ],
            [
                'title' => 'Gestion des Utilisateurs',
                'icon' => 'ri-user-settings-line',
                'url' => route('admin.users.index'),
                'active' => request()->routeIs('admin.users.*'),
                'badge' => \App\Models\User::count(),
                'type' => 'item'
            ],
            [
                'title' => 'Gestion des Secteurs',
                'icon' => 'ri-building-line',
                'url' => route('admin.secteurs.index'),
                'active' => request()->routeIs('admin.secteurs.*'),
                'badge' => \App\Models\Secteur::count(),
                'type' => 'item'
            ],
            [
                'title' => 'Gestion des Rôles',
                'icon' => 'ri-shield-user-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Paramètres Système',
                'icon' => 'ri-settings-3-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Logs Système',
                'icon' => 'ri-file-list-3-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Rapports',
                'type' => 'title'
            ],
            [
                'title' => 'Rapports Globaux',
                'icon' => 'ri-file-chart-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Statistiques',
                'icon' => 'ri-bar-chart-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ]
        ];
    }

    private function getAdminNavigation()
    {
        return [
            [
                'title' => 'Gestion',
                'type' => 'title'
            ],
            [
                'title' => 'Gestion des Utilisateurs',
                'icon' => 'ri-user-settings-line',
                'url' => route('admin.users.index'),
                'active' => request()->routeIs('admin.users.*'),
                'badge' => \App\Models\User::count(),
                'type' => 'item'
            ],
            [
                'title' => 'Gestion des Secteurs',
                'icon' => 'ri-building-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Rapports',
                'type' => 'title'
            ],
            [
                'title' => 'Rapports Secteur',
                'icon' => 'ri-file-chart-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Statistiques',
                'icon' => 'ri-bar-chart-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ]
        ];
    }

    private function getManagerNavigation()
    {
        return [
            [
                'title' => 'Équipe',
                'type' => 'title'
            ],
            [
                'title' => 'Mon Équipe',
                'icon' => 'ri-team-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Gestion des Tâches',
                'icon' => 'ri-task-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Rapports',
                'type' => 'title'
            ],
            [
                'title' => 'Rapports Équipe',
                'icon' => 'ri-file-chart-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Performance',
                'icon' => 'ri-bar-chart-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ]
        ];
    }

    private function getUserNavigation()
    {
        return [
            [
                'title' => 'Mon Espace',
                'type' => 'title'
            ],
            [
                'title' => 'Mon Profil',
                'icon' => 'ri-user-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Mes Tâches',
                'icon' => 'ri-task-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ],
            [
                'title' => 'Mes Rapports',
                'icon' => 'ri-file-chart-line',
                'url' => '#',
                'active' => false,
                'type' => 'item'
            ]
        ];
    }

    public function getDashboardStats()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'superadmin':
                return $this->getSuperAdminStats();
            case 'admin':
                return $this->getAdminStats();
            case 'manager':
                return $this->getManagerStats();
            case 'user':
                return $this->getUserStats();
            default:
                return $this->getDefaultStats();
        }
    }

    private function getSuperAdminStats()
    {
        return [
            'users' => [
                'count' => \App\Models\User::count(),
                'icon' => 'ri-user-line',
                'color' => 'purple',
                'label' => 'Utilisateurs'
            ],
            'sectors' => [
                'count' => 25,
                'icon' => 'ri-building-line',
                'color' => 'blue',
                'label' => 'Secteurs'
            ],
            'reports' => [
                'count' => 183,
                'icon' => 'ri-file-chart-line',
                'color' => 'green',
                'label' => 'Rapports'
            ],
            'system' => [
                'count' => '100%',
                'icon' => 'ri-server-line',
                'color' => 'red',
                'label' => 'Système'
            ]
        ];
    }

    private function getAdminStats()
    {
        return [
            'users' => [
                'count' => \App\Models\User::where('role', '!=', 'superadmin')->count(),
                'icon' => 'ri-user-line',
                'color' => 'purple',
                'label' => 'Utilisateurs'
            ],
            'sectors' => [
                'count' => 15,
                'icon' => 'ri-building-line',
                'color' => 'blue',
                'label' => 'Secteurs'
            ],
            'reports' => [
                'count' => 89,
                'icon' => 'ri-file-chart-line',
                'color' => 'green',
                'label' => 'Rapports'
            ],
            'performance' => [
                'count' => '95%',
                'icon' => 'ri-speed-line',
                'color' => 'red',
                'label' => 'Performance'
            ]
        ];
    }

    private function getManagerStats()
    {
        return [
            'team' => [
                'count' => 8,
                'icon' => 'ri-team-line',
                'color' => 'purple',
                'label' => 'Équipe'
            ],
            'projects' => [
                'count' => 12,
                'icon' => 'ri-folder-line',
                'color' => 'blue',
                'label' => 'Projets'
            ],
            'tasks' => [
                'count' => 45,
                'icon' => 'ri-task-line',
                'color' => 'green',
                'label' => 'Tâches'
            ],
            'performance' => [
                'count' => '87%',
                'icon' => 'ri-speed-line',
                'color' => 'red',
                'label' => 'Performance'
            ]
        ];
    }

    private function getUserStats()
    {
        return [
            'tasks' => [
                'count' => 5,
                'icon' => 'ri-task-line',
                'color' => 'purple',
                'label' => 'Mes Tâches'
            ],
            'completed' => [
                'count' => 12,
                'icon' => 'ri-check-line',
                'color' => 'blue',
                'label' => 'Terminées'
            ],
            'reports' => [
                'count' => 3,
                'icon' => 'ri-file-chart-line',
                'color' => 'green',
                'label' => 'Rapports'
            ],
            'performance' => [
                'count' => '92%',
                'icon' => 'ri-speed-line',
                'color' => 'red',
                'label' => 'Performance'
            ]
        ];
    }

    private function getDefaultStats()
    {
        return [
            'users' => [
                'count' => 0,
                'icon' => 'ri-user-line',
                'color' => 'purple',
                'label' => 'Utilisateurs'
            ],
            'reports' => [
                'count' => 0,
                'icon' => 'ri-file-chart-line',
                'color' => 'blue',
                'label' => 'Rapports'
            ],
            'tasks' => [
                'count' => 0,
                'icon' => 'ri-task-line',
                'color' => 'green',
                'label' => 'Tâches'
            ],
            'performance' => [
                'count' => '0%',
                'icon' => 'ri-speed-line',
                'color' => 'red',
                'label' => 'Performance'
            ]
        ];
    }
} 