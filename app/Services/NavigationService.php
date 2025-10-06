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
            case 'agc':
                $navigation = array_merge($navigation, $this->getAgcNavigation());
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
                'title' => 'Gestion des Coopératives',
                'icon' => 'ri-group-line',
                'url' => route('admin.cooperatives.index'),
                'active' => request()->routeIs('admin.cooperatives.*'),
                'badge' => \App\Models\Cooperative::count(),
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
                'title' => 'Gestion des Producteurs',
                'icon' => 'ri-user-3-line',
                'url' => route('admin.producteurs.index'),
                'active' => request()->routeIs('admin.producteurs.*'),
                'badge' => \App\Models\Producteur::count(),
                'type' => 'item'
            ],
            [
                'title' => 'Gestion Logistique',
                'type' => 'title'
            ],
            [
                'title' => 'Centres de Collecte',
                'icon' => 'ri-map-pin-line',
                'url' => route('admin.centres-collecte.index'),
                'active' => request()->routeIs('admin.centres-collecte.*'),
                'badge' => \App\Models\CentreCollecte::count(),
                'type' => 'item'
            ],
                   [
           'title' => 'Connaissements',
           'icon' => 'ri-file-list-line',
           'url' => route('admin.connaissements.index'),
           'active' => request()->routeIs('admin.connaissements.*'),
           'badge' => \App\Models\Connaissement::count(),
           'type' => 'item'
       ],
                   [
                'title' => 'Tickets de Pesée',
                'icon' => 'ri-scales-line',
                'url' => route('admin.tickets-pesee.index'),
                'active' => request()->routeIs('admin.tickets-pesee.*'),
                'badge' => \App\Models\TicketPesee::count(),
                'type' => 'item'
            ],
            [
                'title' => 'Finance',
                'icon' => 'ri-money-dollar-circle-line',
                'url' => route('admin.finance.index'),
                'active' => request()->routeIs('admin.finance.*'),
                'badge' => \App\Models\TicketPesee::where('statut', 'valide')->count(),
                'type' => 'item'
            ],
            [
                'title' => 'Validation ENE CI',
                'icon' => 'ri-shield-check-line',
                'url' => route('admin.ene-validation.index'),
                'active' => request()->routeIs('admin.ene-validation.*'),
                'badge' => \App\Models\TicketPesee::where('statut', 'valide')->where('statut_ene', 'en_attente')->count(),
                'type' => 'item'
            ],
            [
                'title' => 'Gestion des Factures',
                'icon' => 'ri-file-list-3-line',
                'url' => route('admin.factures.index'),
                'active' => request()->routeIs('admin.factures.*'),
                'badge' => \App\Models\Facture::where('statut', 'brouillon')->count(),
                'type' => 'item'
            ],
            [
                'title' => 'Farmer Lists',
                'icon' => 'ri-file-list-line',
                'url' => route('admin.farmer-lists.index'),
                'active' => request()->routeIs('admin.farmer-lists.*'),
                'badge' => \App\Models\Connaissement::whereHas('ticketsPesee', function($q) {
                    $q->where('statut', 'valide');
                })->count(),
                'type' => 'item'
            ],
            [
                'title' => 'Statistiques',
                'icon' => 'ri-bar-chart-2-line',
                'url' => route('admin.statistiques.index'),
                'active' => request()->routeIs('admin.statistiques.*'),
                'type' => 'item'
            ],
            [
                'title' => 'Statistiques Avancées',
                'icon' => 'ri-line-chart-line',
                'url' => route('admin.statistiques.avancees'),
                'active' => request()->routeIs('admin.statistiques.avancees'),
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
                'url' => route('admin.audit-logs.index'),
                'active' => request()->routeIs('admin.audit-logs.*'),
                'badge' => \App\Models\AuditLog::where('created_at', '>=', now()->subDays(7))->count(),
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

    private function getAgcNavigation()
    {
        return [
            [
                'title' => 'Mon Secteur',
                'type' => 'title'
            ],
            [
                'title' => 'Producteurs',
                'icon' => 'ri-user-3-line',
                'url' => route('admin.producteurs.index'),
                'active' => request()->routeIs('admin.producteurs.*'),
                'type' => 'item'
            ],
            [
                'title' => 'Connaissements',
                'icon' => 'ri-file-list-line',
                'url' => route('admin.connaissements.index'),
                'active' => request()->routeIs('admin.connaissements.*'),
                'type' => 'item'
            ],
            [
                'title' => 'Tickets de Pesée',
                'icon' => 'ri-scales-line',
                'url' => route('admin.tickets-pesee.index'),
                'active' => request()->routeIs('admin.tickets-pesee.*'),
                'type' => 'item'
            ],
            [
                'title' => 'Farmer Lists',
                'icon' => 'ri-file-list-line',
                'url' => route('admin.farmer-lists.index'),
                'active' => request()->routeIs('admin.farmer-lists.*'),
                'type' => 'item'
            ],
            [
                'title' => 'Statistiques',
                'icon' => 'ri-bar-chart-2-line',
                'url' => route('admin.statistiques.index'),
                'active' => request()->routeIs('admin.statistiques.*'),
                'type' => 'item'
            ],
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
            case 'agc':
                return $this->getAgcStats(); // <- AJOUT
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
            'cooperatives' => [
                'count' => \App\Models\Cooperative::count(),
                'icon' => 'ri-group-line',
                'color' => 'orange',
                'label' => 'Coopératives'
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

    private function getAgcStats()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $secteurCode = $user->secteur;

        // Si l’AGC n’a pas de secteur assigné, retourner des compteurs vides
        if (!$secteurCode) {
            return [
                'producteurs' => ['count' => 0, 'icon' => 'ri-user-3-line', 'color' => 'green', 'label' => 'Producteurs (secteur)'],
                'parcelles' => ['count' => 0, 'icon' => 'ri-map-pin-line', 'color' => 'blue', 'label' => 'Parcelles (secteur)'],
                'livraisons' => ['count' => 0, 'icon' => 'ri-truck-line', 'color' => 'orange', 'label' => 'Livraisons (secteur)'],
                'farmerlists' => ['count' => 0, 'icon' => 'ri-file-list-line', 'color' => 'purple', 'label' => 'Farmer Lists (secteur)'],
                'docs_manquants' => ['count' => 0, 'icon' => 'ri-file-warning-line', 'color' => 'red', 'label' => 'Docs traçabilité manquants'],
            ];
        }

        // Comptes par secteur
        $producteursCount = \App\Models\Producteur::whereHas('secteur', function($q) use ($secteurCode) {
            $q->where('code', $secteurCode);
        })->count();

        $parcellesCount = \App\Models\Parcelle::whereHas('producteur.secteur', function($q) use ($secteurCode) {
            $q->where('code', $secteurCode);
        })->count();

        $livraisonsCount = \App\Models\Connaissement::whereHas('secteur', function($q) use ($secteurCode) {
            $q->where('code', $secteurCode);
        })->count();

        $farmerListsCount = \App\Models\FarmerList::whereHas('connaissement.secteur', function($q) use ($secteurCode) {
            $q->where('code', $secteurCode);
        })->count();

        // Producteurs avec documents manquants (fiche_enquete, lettre_engagement, self_declaration)
        $docsManquantsCount = \App\Models\Producteur::whereHas('secteur', function($q) use ($secteurCode) {
            $q->where('code', $secteurCode);
        })->where(function($q) {
            $q->whereDoesntHave('documents', function($d) {
                $d->where('type', 'fiche_enquete');
            })->orWhereDoesntHave('documents', function($d) {
                $d->where('type', 'lettre_engagement');
            })->orWhereDoesntHave('documents', function($d) {
                $d->where('type', 'self_declaration');
            });
        })->count();

        return [
            'producteurs' => [
                'count' => $producteursCount,
                'icon' => 'ri-user-3-line',
                'color' => 'green',
                'label' => 'Producteurs (secteur)'
            ],
            'parcelles' => [
                'count' => $parcellesCount,
                'icon' => 'ri-map-pin-line',
                'color' => 'blue',
                'label' => 'Parcelles (secteur)'
            ],
            'livraisons' => [
                'count' => $livraisonsCount,
                'icon' => 'ri-truck-line',
                'color' => 'orange',
                'label' => 'Livraisons (secteur)'
            ],
            'farmerlists' => [
                'count' => $farmerListsCount,
                'icon' => 'ri-file-list-line',
                'color' => 'purple',
                'label' => 'Farmer Lists (secteur)'
            ],
            'docs_manquants' => [
                'count' => $docsManquantsCount,
                'icon' => 'ri-file-warning-line',
                'color' => 'red',
                'label' => 'Docs traçabilité manquants'
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

    public function getAgcDashboardExtras(): array
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $secteurCode = $user->secteur;

        if (!$secteurCode) {
            return [
                'charts' => [
                    'livraisons30j' => [],
                    'docsCompletion' => ['complete' => 0, 'missing' => 0],
                    'parcellesByCoop' => ['categories' => [], 'data' => []],
                ],
                'tables' => [
                    'topCoops' => [],
                    'recentLivraisons' => [],
                ],
                'kpis' => [
                    'completionRate' => 0,
                ],
            ];
        }

        // Livraisons dernières 30j (poids net par jour)
        $from = now()->subDays(29)->startOfDay();
        $to = now()->endOfDay();
        $livraisons30j = \App\Models\Connaissement::whereHas('secteur', function($q) use ($secteurCode) {
                $q->where('code', $secteurCode);
            })
            ->whereBetween('created_at', [$from, $to])
            ->with(['ticketsPesee' => function($q) { $q->select('id','connaissement_id','poids_net'); }])
            ->get()
            ->groupBy(fn($c) => \Carbon\Carbon::parse($c->created_at)->format('Y-m-d'))
            ->map(fn($group) => (float) ($group->flatMap->ticketsPesee->sum('poids_net') ?: 0))
            ->all();

        // Docs complétude (producteurs du secteur avec 3 docs requis)
        $prodQuery = \App\Models\Producteur::whereHas('secteur', fn($q) => $q->where('code', $secteurCode));
        $totalProd = (clone $prodQuery)->count();

        $completeDocs = (clone $prodQuery)->whereDoesntHave('documents', function($d) {
                $d->whereIn('type', ['fiche_enquete','lettre_engagement','self_declaration']);
            })
            ->whereHas('documents', fn($d) => $d->where('type','fiche_enquete'))
            ->whereHas('documents', fn($d) => $d->where('type','lettre_engagement'))
            ->whereHas('documents', fn($d) => $d->where('type','self_declaration'))
            ->count();

        $missingDocs = max($totalProd - $completeDocs, 0);

        // Parcelles par coop (dans le secteur)
        $parcellesAgg = \App\Models\Cooperative::select('cooperatives.nom')
            ->join('cooperative_producteur', 'cooperatives.id', '=', 'cooperative_producteur.cooperative_id')
            ->join('producteurs', 'cooperative_producteur.producteur_id', '=', 'producteurs.id')
            ->leftJoin('parcelles', 'parcelles.producteur_id', '=', 'producteurs.id')
            ->join('secteurs', 'producteurs.secteur_id', '=', 'secteurs.id')
            ->where('secteurs.code', $secteurCode)
            ->groupBy('cooperatives.id', 'cooperatives.nom')
            ->selectRaw('COUNT(parcelles.id) as parcelles_count')
            ->orderByDesc('parcelles_count')
            ->limit(8)
            ->get();

        $parcellesCategories = $parcellesAgg->pluck('nom')->all();
        $parcellesData = $parcellesAgg->pluck('parcelles_count')->map(fn($v)=>(int)$v)->all();

        // Top 5 coops par poids net
        $topCoops = \App\Models\Cooperative::select('cooperatives.nom')
            ->join('connaissements', 'connaissements.cooperative_id', '=', 'cooperatives.id')
            ->join('secteurs', 'connaissements.secteur_id', '=', 'secteurs.id')
            ->leftJoin('tickets_pesee', 'tickets_pesee.connaissement_id', '=', 'connaissements.id')
            ->where('secteurs.code', $secteurCode)
            ->groupBy('cooperatives.id','cooperatives.nom')
            ->selectRaw('COALESCE(SUM(tickets_pesee.poids_net),0) as poids_net_total, COUNT(DISTINCT connaissements.id) as livraisons')
            ->orderByDesc('poids_net_total')
            ->limit(5)
            ->get()
            ->map(fn($r)=>[
                'cooperative' => $r->nom,
                'livraisons' => (int)$r->livraisons,
                'poids_net' => (float)$r->poids_net_total,
            ])->all();

        // 10 dernières livraisons (numéro + poids)
        $recentLivraisons = \App\Models\Connaissement::whereHas('secteur', fn($q) => $q->where('code', $secteurCode))
            ->latest('created_at')->with(['ticketsPesee','cooperative'])
            ->take(10)->get()
            ->map(fn($c)=>[
                'numero' => $c->numero_livraison,
                'coop' => optional($c->cooperative)->nom,
                'poids_net' => (float)($c->ticketsPesee->first()->poids_net ?? 0),
                'date' => \Carbon\Carbon::parse($c->created_at)->format('d/m/Y'),
            ])->all();

        // Completion rate Farmer Lists: poids total vs poids net
        $poidsNetSecteur = \App\Models\Connaissement::whereHas('secteur', fn($q) => $q->where('code', $secteurCode))
            ->with('ticketsPesee')->get()->sum(fn($c)=> (float) ($c->ticketsPesee->first()->poids_net ?? 0));
        $poidsFarmerLists = \App\Models\FarmerList::whereHas('connaissement.secteur', fn($q)=>$q->where('code',$secteurCode))
            ->sum('quantite_livree');
        $completionRate = $poidsNetSecteur > 0 ? round(($poidsFarmerLists / $poidsNetSecteur) * 100, 1) : 0;

        // Normaliser série 30j (toutes dates)
        $series = [];
        for ($i=0; $i<30; $i++) {
            $d = now()->subDays(29 - $i)->format('Y-m-d');
            $series[] = ['x' => $d, 'y' => (float)($livraisons30j[$d] ?? 0)];
        }

        return [
            'charts' => [
                'livraisons30j' => $series,
                'docsCompletion' => ['complete' => $completeDocs, 'missing' => $missingDocs],
                'parcellesByCoop' => ['categories' => $parcellesCategories, 'data' => $parcellesData],
            ],
            'tables' => [
                'topCoops' => $topCoops,
                'recentLivraisons' => $recentLivraisons,
            ],
            'kpis' => [
                'completionRate' => $completionRate,
            ],
        ];
    }
} 