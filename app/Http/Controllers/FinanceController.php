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
        // Récupérer le filtre de statut ENE CI
        $statutEne = $request->get('statut_ene', 'all');
        $search = $request->get('search', '');
        
        // Construire la requête de base
        $query = TicketPesee::where('statut', 'valide')
            ->with(['connaissement.cooperative', 'connaissement.centreCollecte', 'createdBy', 'valideParEne']);
        
        // Appliquer le filtre de statut ENE CI
        if ($statutEne !== 'all') {
            $query->where('statut_ene', $statutEne);
        }
        
        // Appliquer la recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('numero_ticket', 'like', "%{$search}%")
                  ->orWhereHas('connaissement.cooperative', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%{$search}%");
                  });
            });
        }
        
        // Paginer les résultats
        $ticketsValides = $query->orderBy('created_at', 'desc')->paginate(10);
        
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

        // Récupérer la matrice de prix active
        $matriceActive = MatricePrix::getActiveForYear();

        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.finance.index', compact('ticketsAvecPrix', 'matriceActive', 'navigation', 'ticketsValides', 'statutEne', 'search'));
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
