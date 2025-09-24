<?php

namespace App\Http\Controllers;

use App\Models\TicketPesee;
use App\Models\MatricePrix;
use App\Services\CalculPrixService;
use App\Services\NavigationService;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    protected $navigationService;
    protected $calculPrixService;

    public function __construct(NavigationService $navigationService, CalculPrixService $calculPrixService)
    {
        $this->navigationService = $navigationService;
        $this->calculPrixService = $calculPrixService;
    }

    /**
     * Afficher la page principale de finance
     */
    public function index(Request $request)
    {
        // Construire la requête de base
        $query = TicketPesee::where('statut', 'valide')
            ->with(['connaissement.cooperative.secteur', 'connaissement.secteur', 'connaissement.centreCollecte', 'createdBy', 'valideParEne']);
        
        // Filtre par secteur
        if ($request->filled('secteur')) {
            $query->whereHas('connaissement', function($q) use ($request) {
                $q->where('secteur_id', $request->secteur);
            });
        }
        
        // Filtre par coopérative
        if ($request->filled('cooperative')) {
            $query->whereHas('connaissement', function($q) use ($request) {
                $q->where('cooperative_id', $request->cooperative);
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
        
        // Calculer les prix pour chaque ticket
        $ticketsAvecPrix = [];
        foreach ($ticketsValides as $ticket) {
            try {
                $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                $ticketsAvecPrix[] = [
                    'ticket' => $ticket,
                    'prix' => $prix
                ];
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
        $secteurs = \App\Models\Secteur::orderBy('code')->get();
        $cooperatives = \App\Models\Cooperative::with('secteur')->orderBy('nom')->get();
        $statutsEne = ['en_attente' => 'En attente', 'valide_par_ene' => 'Validé par ENE', 'rejete_par_ene' => 'Rejeté par ENE'];
        
        // Récupérer la matrice de prix active
        $matriceActive = MatricePrix::getActiveForYear();

        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.finance.index', compact('ticketsAvecPrix', 'matriceActive', 'navigation', 'ticketsValides', 'secteurs', 'cooperatives', 'statutsEne'));
    }

    /**
     * Afficher les détails de calcul pour un ticket
     */
    public function showCalcul($id)
    {
        $ticketPesee = TicketPesee::findOrFail($id);
        
        if ($ticketPesee->statut !== 'valide') {
            return redirect()->route('admin.finance.index')
                ->with('error', 'Ce ticket n\'est pas encore validé pour paiement.');
        }

        try {
            $prix = $this->calculPrixService->calculerPrixTicket($ticketPesee);
        } catch (\Exception $e) {
            return redirect()->route('admin.finance.index')
                ->with('error', 'Erreur lors du calcul du prix : ' . $e->getMessage());
        }

        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.finance.show-calcul', compact('ticketPesee', 'prix', 'navigation'));
    }

    /**
     * Gérer la matrice de prix
     */
    public function matricePrix()
    {
        $matrices = MatricePrix::orderBy('annee', 'desc')->get();
        $matriceActive = MatricePrix::getActiveForYear();
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.finance.matrice-prix', compact('matrices', 'matriceActive', 'navigation'));
    }

    /**
     * Créer une nouvelle matrice de prix
     */
    public function storeMatricePrix(Request $request)
    {
        $request->validate([
            'annee' => 'required|string|size:4|unique:matrice_prix,annee',
            'prix_bord_champs' => 'required|numeric|min:0',
            'transport_base' => 'required|numeric|min:0',
            'cooperatives' => 'required|numeric|min:0',
            'stockage' => 'required|numeric|min:0',
            'chargeur_dechargeur' => 'required|numeric|min:0',
            'soutien_sechoirs' => 'required|numeric|min:0',
            'prodev' => 'required|numeric|min:0',
            'sac' => 'required|numeric|min:0',
            'sechage' => 'required|numeric|min:0',
            'certification' => 'required|numeric|min:0',
            'fphci' => 'required|numeric|min:0',
            'moyenne_transfert' => 'required|numeric|min:0',
        ]);

        // Désactiver toutes les autres matrices
        MatricePrix::where('active', true)->update(['active' => false]);

        // Créer la nouvelle matrice
        MatricePrix::create($request->all() + ['active' => true]);

        return redirect()->route('admin.finance.matrice-prix')
            ->with('success', 'Matrice de prix créée avec succès !');
    }

    /**
     * Activer une matrice de prix
     */
    public function activerMatricePrix($id)
    {
        // Désactiver toutes les matrices
        MatricePrix::where('active', true)->update(['active' => false]);
        
        // Activer la matrice sélectionnée
        $matrice = MatricePrix::findOrFail($id);
        $matrice->update(['active' => true]);

        return redirect()->route('admin.finance.matrice-prix')
            ->with('success', 'Matrice de prix activée avec succès !');
    }
}
