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
        
        // Dashboard spécifique selon le rôle
        if ($user->role === 'rcoop') {
            return $this->cooperativeDashboard($user, $navigation);
        }
        
        if ($user->role === 'agc') {
            return $this->agcDashboard($user, $navigation);
        }
        
        // Statistiques pour le dashboard (utilise le NavigationService)
        $stats = $this->navigationService->getDashboardStats();
        
        $extras = null;
        if ($user->role === 'agc') {
            // Récupération des extras spécifiques AGC
            if (method_exists($this->navigationService, 'getAgcDashboardExtras')) {
                $extras = $this->navigationService->getAgcDashboardExtras();
            }
        }
        
        // Données pour le dashboard
        return view('dashboard', [
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
            'stats' => $stats,
            'extras' => $extras,
        ]);
    }

    /**
     * Dashboard spécifique pour les responsables de coopératives
     */
    private function cooperativeDashboard($user, $navigation)
    {
        // Récupérer la coopérative de l'utilisateur
        $cooperative = $user->cooperative;
        
        if (!$cooperative) {
            return redirect()->route('logout')->with('error', 'Aucune coopérative associée à votre compte.');
        }

        // Statistiques spécifiques à la coopérative
        $stats = [
            'total_producteurs' => $cooperative->producteurs()->count(),
            'total_tickets' => $cooperative->ticketsPesee()->count(),
            'total_connaissements' => $cooperative->connaissements()->count(),
            'total_factures' => $cooperative->factures()->count(),
            'poids_total_mois' => \App\Models\TicketPesee::whereHas('connaissement', function($query) use ($cooperative) {
                $query->where('cooperative_id', $cooperative->id);
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('poids_net'),
            'montant_total_mois' => $cooperative->factures()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('montant_ttc'),
        ];

        // Dernières activités
        $recentTickets = \App\Models\TicketPesee::whereHas('connaissement', function($query) use ($cooperative) {
                $query->where('cooperative_id', $cooperative->id);
            })
            ->with(['connaissement.secteur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentFactures = $cooperative->factures()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('cooperative.dashboard', compact('user', 'navigation', 'cooperative', 'stats', 'recentTickets', 'recentFactures'));
    }

    /**
     * Dashboard spécifique pour les AGC
     */
    private function agcDashboard($user, $navigation)
    {
        // Récupérer le secteur de l'AGC
        $secteur = \App\Models\Secteur::where('code', $user->secteur)->first();
        if (!$secteur) {
            return redirect()->route('logout')->with('error', 'Aucun secteur associé à votre compte.');
        }

        // Statistiques de base
        $totalCooperatives = \App\Models\Cooperative::where('secteur_id', $secteur->id)->count();
        $totalProducteurs = \App\Models\Producteur::whereHas('secteur', function($query) use ($secteur) {
            $query->where('code', $secteur->code);
        })->count();

        // Progression des documents de coopératives
        $cooperativesAvecDocuments = \App\Models\Cooperative::where('secteur_id', $secteur->id)
            ->whereHas('documents')
            ->count();
        $progressionCooperatives = $totalCooperatives > 0 ? round(($cooperativesAvecDocuments / $totalCooperatives) * 100, 1) : 0;

        // Progression des documents de traçabilité des producteurs
        $producteursAvecDocuments = \App\Models\Producteur::whereHas('secteur', function($query) use ($secteur) {
            $query->where('code', $secteur->code);
        })->whereHas('documents', function($query) {
            $query->whereIn('type', ['fiche_enquete', 'lettre_engagement', 'self_declaration']);
        })->count();
        $progressionProducteurs = $totalProducteurs > 0 ? round(($producteursAvecDocuments / $totalProducteurs) * 100, 1) : 0;

        // Progression des farmer lists (via connaissements)
        $totalConnaissements = \App\Models\Connaissement::where('secteur_id', $secteur->id)->count();
        $connaissementsAvecFarmerList = \App\Models\Connaissement::where('secteur_id', $secteur->id)
            ->whereHas('farmerList')
            ->count();
        $progressionFarmerLists = $totalConnaissements > 0 ? round(($connaissementsAvecFarmerList / $totalConnaissements) * 100, 1) : 0;

        // Détails des documents manquants par type
        $cooperativesAvecDetails = \App\Models\Cooperative::where('secteur_id', $secteur->id)
            ->with(['documents'])
            ->get(['id', 'nom', 'code']);

        $producteursAvecDetails = \App\Models\Producteur::whereHas('secteur', function($query) use ($secteur) {
            $query->where('code', $secteur->code);
        })->with(['documents' => function($query) {
            $query->whereIn('type', ['fiche_enquete', 'lettre_engagement', 'self_declaration']);
        }])->get(['id', 'nom', 'prenom']);

        // Analyser les documents manquants par type
        $documentTypes = [
            'statuts' => 'Statuts',
            'dfe' => 'DFE',
            'registre_commerce' => 'Registre de Commerce',
            'fiche_enquete' => 'Fiche d\'Enquête',
            'contrat_bail' => 'Contrat de Bail',
            'delegation_pouvoir' => 'Délégation de Pouvoir',
            'journal_officiel' => 'Journal Officiel',
            'protocole_fph_ci' => 'Protocole FPH-CI',
            'fiche_etalonnage' => 'Fiche d\'Étalonnage',
            'liste_formation' => 'Liste de Formation'
        ];

        $producteurDocumentTypes = [
            'fiche_enquete' => 'Fiche d\'Enquête',
            'lettre_engagement' => 'Lettre d\'Engagement',
            'self_declaration' => 'Self Declaration'
        ];

        // Analyser les coopératives
        $cooperativesDetails = [];
        foreach ($cooperativesAvecDetails as $coop) {
            $documentsExistants = $coop->documents->pluck('type')->toArray();
            $documentsManquants = [];
            
            foreach ($documentTypes as $type => $label) {
                if (!in_array($type, $documentsExistants)) {
                    $documentsManquants[] = $label;
                }
            }
            
            if (!empty($documentsManquants)) {
                $cooperativesDetails[] = [
                    'cooperative' => $coop,
                    'documents_manquants' => $documentsManquants,
                    'total_manquants' => count($documentsManquants)
                ];
            }
        }

        // Analyser les producteurs
        $producteursDetails = [];
        foreach ($producteursAvecDetails as $prod) {
            $documentsExistants = $prod->documents->pluck('type')->toArray();
            $documentsManquants = [];
            
            foreach ($producteurDocumentTypes as $type => $label) {
                if (!in_array($type, $documentsExistants)) {
                    $documentsManquants[] = $label;
                }
            }
            
            if (!empty($documentsManquants)) {
                $producteursDetails[] = [
                    'producteur' => $prod,
                    'documents_manquants' => $documentsManquants,
                    'total_manquants' => count($documentsManquants)
                ];
            }
        }

        $connaissementsSansFarmerList = \App\Models\Connaissement::where('secteur_id', $secteur->id)
            ->whereDoesntHave('farmerList')
            ->with(['cooperative'])
            ->get(['id', 'numero_livraison', 'cooperative_id']);

        // Activités récentes
        $recentesActivites = collect();
        
        // Coopératives récemment mises à jour
        $cooperativesRecentes = \App\Models\Cooperative::where('secteur_id', $secteur->id)
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get(['nom', 'updated_at']);

        foreach ($cooperativesRecentes as $coop) {
            $recentesActivites->push([
                'type' => 'cooperative',
                'message' => "Coopérative {$coop->nom} mise à jour",
                'date' => $coop->updated_at,
                'icon' => 'ri-group-line',
                'color' => 'primary'
            ]);
        }

        // Producteurs récemment mis à jour
        $producteursRecents = \App\Models\Producteur::whereHas('secteur', function($query) use ($secteur) {
            $query->where('code', $secteur->code);
        })->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get(['nom', 'prenom', 'updated_at']);

        foreach ($producteursRecents as $prod) {
            $recentesActivites->push([
                'type' => 'producteur',
                'message' => "Producteur {$prod->nom} {$prod->prenom} mis à jour",
                'date' => $prod->updated_at,
                'icon' => 'ri-user-3-line',
                'color' => 'success'
            ]);
        }

        $recentesActivites = $recentesActivites->sortByDesc('date')->take(10);

        $stats = [
            'total_cooperatives' => $totalCooperatives,
            'total_producteurs' => $totalProducteurs,
            'total_connaissements' => $totalConnaissements,
            'progression_cooperatives' => $progressionCooperatives,
            'progression_producteurs' => $progressionProducteurs,
            'progression_farmer_lists' => $progressionFarmerLists,
            'cooperatives_avec_documents' => $cooperativesAvecDocuments,
            'producteurs_avec_documents' => $producteursAvecDocuments,
            'connaissements_avec_farmer_list' => $connaissementsAvecFarmerList,
        ];

        return view('agc.dashboard', compact(
            'user', 
            'navigation', 
            'secteur', 
            'stats', 
            'cooperativesDetails', 
            'producteursDetails', 
            'connaissementsSansFarmerList',
            'recentesActivites',
            'documentTypes',
            'producteurDocumentTypes'
        ));
    }
}
