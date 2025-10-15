<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketPesee;
use App\Models\Cooperative;
use App\Models\Secteur;
use App\Models\MatricePrix;
use App\Services\CalculPrixService;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationService;

class CooperativeFinanceController extends Controller
{
    protected $calculPrixService;
    protected $navigationService;

    public function __construct(CalculPrixService $calculPrixService, NavigationService $navigationService)
    {
        $this->calculPrixService = $calculPrixService;
        $this->navigationService = $navigationService;
    }

    /**
     * Afficher les calculs financiers de la coopérative (montant public uniquement)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer la coopérative du responsable
        $cooperative = Cooperative::find($user->cooperative_id);
        
        if (!$cooperative) {
            return redirect()->route('dashboard')->with('error', 'Aucune coopérative assignée à votre compte.');
        }

        // Construire la requête de base - seulement les tickets de cette coopérative
        $query = TicketPesee::where('statut', 'valide')
            ->whereHas('connaissement', function($q) use ($cooperative) {
                $q->where('cooperative_id', $cooperative->id);
            })
            ->with(['connaissement.cooperative.secteur', 'connaissement.secteur', 'connaissement.centreCollecte', 'createdBy', 'valideParEne']);
        
        // Filtre par secteur
        if ($request->filled('secteur')) {
            $query->whereHas('connaissement', function($q) use ($request) {
                $q->where('secteur_id', $request->secteur);
            });
        }
        
        // Filtre par statut ENE CI
        if ($request->filled('statut_ene') && $request->statut_ene !== 'all') {
            $query->where('statut_ene', $request->statut_ene);
        }
        
        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Recherche par numéro de ticket et autres champs
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('numero_ticket', 'LIKE', "%{$request->search}%")
                  ->orWhere('numero_livraison', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('connaissement.cooperative', function($q2) use ($request) {
                      $q2->where('nom', 'LIKE', "%{$request->search}%")
                         ->orWhere('code', 'LIKE', "%{$request->search}%");
                  })
                  ->orWhereHas('connaissement.secteur', function($q2) use ($request) {
                      $q2->where('nom', 'LIKE', "%{$request->search}%")
                         ->orWhere('code', 'LIKE', "%{$request->search}%");
                  });
            });
        }
        
        // Paginer les résultats
        $ticketsValides = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10));
        
        // Calculer les prix pour chaque ticket (montant public uniquement)
        $ticketsAvecPrix = [];
        $totalPublic = 0;

        foreach ($ticketsValides as $ticket) {
            try {
                $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                
                if ($prix && !isset($prix['erreur'])) {
                    $montantPublic = $prix['prix_final_public'];
                    $totalPublic += $montantPublic;
                    
                    $ticketsAvecPrix[] = [
                        'ticket' => $ticket,
                        'prix' => [
                            'prix_final_public' => $prix['prix_final_public'],
                            'montant_public' => $montantPublic
                        ]
                    ];
                } else {
                    $ticketsAvecPrix[] = [
                        'ticket' => $ticket,
                        'prix' => ['erreur' => 'Erreur de calcul']
                    ];
                }
            } catch (\Exception $e) {
                // En cas d'erreur, on ajoute le ticket sans prix
                $ticketsAvecPrix[] = [
                    'ticket' => $ticket,
                    'prix' => null,
                    'erreur' => $e->getMessage()
                ];
            }
        }

        // Récupérer les données pour les filtres
        $secteurs = Secteur::orderBy('code')->get();
        $cooperatives = Cooperative::with('secteur')->orderBy('nom')->get();
        $statutsEne = ['en_attente' => 'En attente', 'valide_par_ene' => 'Validé par ENE', 'rejete_par_ene' => 'Rejeté par ENE'];
        
        // Récupérer la matrice de prix active
        $matriceActive = MatricePrix::getActiveForYear();

        // Navigation pour les responsables de coopératives
        $navigation = $this->navigationService->getNavigation($user);
        
        return view('cooperative.finance.index', compact('ticketsAvecPrix', 'matriceActive', 'navigation', 'ticketsValides', 'secteurs', 'cooperatives', 'statutsEne', 'cooperative', 'totalPublic'));
    }

    /**
     * Afficher les détails de calcul pour un ticket (lecture seule)
     */
    public function showCalcul($id)
    {
        $user = Auth::user();
        $cooperative = Cooperative::find($user->cooperative_id);
        
        if (!$cooperative) {
            return redirect()->route('dashboard')->with('error', 'Aucune coopérative assignée à votre compte.');
        }

        $ticketPesee = TicketPesee::findOrFail($id);
        
        // Vérifier que le ticket appartient à la coopérative du responsable
        if ($ticketPesee->connaissement->cooperative_id !== $cooperative->id) {
            return redirect()->route('cooperative.finance.index')->with('error', 'Accès refusé.');
        }
        
        if ($ticketPesee->statut !== 'valide') {
            return redirect()->route('cooperative.finance.index')
                ->with('error', 'Ce ticket n\'est pas encore validé pour paiement.');
        }

        try {
            $prix = $this->calculPrixService->calculerPrixTicket($ticketPesee);
        } catch (\Exception $e) {
            return redirect()->route('cooperative.finance.index')
                ->with('error', 'Erreur lors du calcul du prix : ' . $e->getMessage());
        }

        // Navigation pour les responsables de coopératives
        $navigation = $this->navigationService->getNavigation($user);
        
        return view('cooperative.finance.show-calcul', compact('ticketPesee', 'prix', 'navigation', 'cooperative'));
    }
}