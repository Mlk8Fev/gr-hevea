<?php

namespace App\Http\Controllers;

use App\Models\TicketPesee;
use App\Services\CalculPrixService;
use App\Services\NavigationService;
use Illuminate\Http\Request;

class EneValidationController extends Controller
{
    protected $navigationService;
    protected $calculPrixService;

    public function __construct(NavigationService $navigationService, CalculPrixService $calculPrixService)
    {
        $this->navigationService = $navigationService;
        $this->calculPrixService = $calculPrixService;
    }

    /**
     * Afficher la liste des tickets à valider par ENE CI
     */
    public function index(Request $request)
    {
        // Récupérer les filtres
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
        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Calculer les prix pour chaque ticket
        $ticketsAvecPrix = [];
        foreach ($tickets as $ticket) {
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

        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.ene-validation.index', compact('ticketsAvecPrix', 'navigation', 'tickets', 'statutEne', 'search'));
    }

    /**
     * Afficher le détail d'un ticket pour validation ENE CI
     */
    public function show($id)
    {
        $ticketPesee = TicketPesee::findOrFail($id);
        
        if ($ticketPesee->statut !== 'valide') {
            return redirect()->route('admin.ene-validation.index')
                ->with('error', 'Ce ticket n\'est pas encore validé pour paiement.');
        }

        // Calculer le prix détaillé
        try {
            $prix = $this->calculPrixService->calculerPrixTicket($ticketPesee);
        } catch (\Exception $e) {
            return redirect()->route('admin.ene-validation.index')
                ->with('error', 'Erreur lors du calcul du prix : ' . $e->getMessage());
        }

        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.ene-validation.show', compact('ticketPesee', 'prix', 'navigation'));
    }

    /**
     * Valider un ticket par ENE CI
     */
    public function validate(Request $request, $id)
    {
        $request->validate([
            'commentaire' => 'nullable|string|max:1000'
        ]);

        $ticketPesee = TicketPesee::findOrFail($id);
        
        if ($ticketPesee->statut !== 'valide') {
            return redirect()->route('admin.ene-validation.index')
                ->with('error', 'Ce ticket n\'est pas encore validé pour paiement.');
        }

        if ($ticketPesee->statut_ene !== 'en_attente') {
            return redirect()->route('admin.ene-validation.index')
                ->with('error', 'Ce ticket a déjà été traité par ENE CI.');
        }

        $ticketPesee->update([
            'statut_ene' => 'valide_par_ene',
            'valide_par_ene' => auth()->id(),
            'date_validation_ene' => now(),
            'commentaire_ene' => $request->commentaire
        ]);

        return redirect()->route('admin.ene-validation.index')
            ->with('success', 'Ticket validé par ENE CI avec succès ! Il est maintenant éligible à la facturation.');
    }

    /**
     * Rejeter un ticket par ENE CI
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'commentaire' => 'required|string|max:1000'
        ]);

        $ticketPesee = TicketPesee::findOrFail($id);
        
        if ($ticketPesee->statut !== 'valide') {
            return redirect()->route('admin.ene-validation.index')
                ->with('error', 'Ce ticket n\'est pas encore validé pour paiement.');
        }

        if ($ticketPesee->statut_ene !== 'en_attente') {
            return redirect()->route('admin.ene-validation.index')
                ->with('error', 'Ce ticket a déjà été traité par ENE CI.');
        }

        $ticketPesee->update([
            'statut_ene' => 'rejete_par_ene',
            'valide_par_ene' => auth()->id(),
            'date_validation_ene' => now(),
            'commentaire_ene' => $request->commentaire
        ]);

        return redirect()->route('admin.ene-validation.index')
            ->with('success', 'Ticket rejeté par ENE CI. Le commentaire a été enregistré.');
    }

    /**
     * Annuler la validation ENE CI (remettre en attente)
     */
    public function cancel($id)
    {
        $ticketPesee = TicketPesee::findOrFail($id);
        
        if (!in_array($ticketPesee->statut_ene, ['valide_par_ene', 'rejete_par_ene'])) {
            return redirect()->route('admin.ene-validation.index')
                ->with('error', 'Ce ticket n\'a pas encore été traité par ENE CI.');
        }

        $ticketPesee->update([
            'statut_ene' => 'en_attente',
            'valide_par_ene' => null,
            'date_validation_ene' => null,
            'commentaire_ene' => null
        ]);

        return redirect()->route('admin.ene-validation.index')
            ->with('success', 'Validation ENE CI annulée. Le ticket est remis en attente.');
    }
}
