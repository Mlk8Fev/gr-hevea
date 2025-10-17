<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Cooperative;
use App\Models\Secteur;
use App\Services\CalculPrixService;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationService;

class CsFactureController extends Controller
{
    protected $calculPrixService;

    public function __construct(CalculPrixService $calculPrixService)
    {
        $this->calculPrixService = $calculPrixService;
    }

    /**
     * Afficher les factures du secteur du CS/AGC
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Filtrer par secteur pour les CS et AGC
        $secteurCode = $user->secteur;
        if (!$secteurCode) {
            return redirect()->route('dashboard')->with('error', 'Aucun secteur assigné à votre compte.');
        }
        
        // Récupérer les factures des coopératives de ce secteur uniquement
        $query = Facture::whereHas('cooperative', function($q) use ($secteurCode) {
            $q->whereHas('secteur', function($sq) use ($secteurCode) {
                $sq->where('code', $secteurCode);
            });
        })->with(['cooperative', 'ticketsPesee']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('numero_facture', 'like', "%{$search}%");
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        $factures = $query->orderBy('created_at', 'desc')->paginate(15);

        // Récupérer les secteurs pour les filtres
        $secteurs = Secteur::orderBy('code')->get();
        $cooperatives = Cooperative::with('secteur')->orderBy('nom')->get();
        $types = ['individuelle' => 'Individuelle', 'globale' => 'Globale'];
        $statuts = ['brouillon' => 'Brouillon', 'validee' => 'Validée', 'payee' => 'Payée', 'annulee' => 'Annulée'];

        // Récupérer la navigation pour l'utilisateur CS
        $navigationService = app(\App\Services\NavigationService::class);
        $navigation = $navigationService->getNavigation();

        return view('cs.factures.index', compact('factures', 'secteurs', 'cooperatives', 'types', 'statuts', 'navigation'));
    }

    /**
     * Afficher le formulaire de création de facture (lecture seule pour les CS)
     */
    public function create()
    {
        return redirect()->route('cs.factures.index')->with('info', 'La création de factures est réservée aux administrateurs.');
    }

    /**
     * Afficher les détails d'une facture (lecture seule)
     */
    public function show(Facture $facture)
    {
        $user = Auth::user();
        
        // Vérifier que la facture appartient au secteur du CS/AGC
        $secteurCode = $user->secteur;
        if (!$secteurCode) {
            return redirect()->route('dashboard')->with('error', 'Aucun secteur assigné à votre compte.');
        }
        
        // Vérifier que la facture appartient à une coopérative de ce secteur
        if (!$facture->cooperative || $facture->cooperative->secteur->code !== $secteurCode) {
            return redirect()->route('cs.factures.index')->with('error', 'Accès refusé.');
        }

        // Charger les relations nécessaires
        $facture->load(['cooperative', 'ticketsPesee.connaissement.secteur', 'createdBy', 'valideePar']);
        
        // Calculer les prix pour chaque ticket
        $ticketsAvecPrix = [];
        foreach ($facture->ticketsPesee as $ticket) {
            try {
                $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                $ticketsAvecPrix[] = [
                    'ticket' => $ticket,
                    'prix' => $prix
                ];
            } catch (\Exception $e) {
                \Log::error('Erreur calcul prix pour affichage facture CS', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Récupérer la navigation pour l'utilisateur CS
        $navigationService = app(\App\Services\NavigationService::class);
        $navigation = $navigationService->getNavigation();

        return view('cs.factures.show', compact('facture', 'ticketsAvecPrix', 'navigation'));
    }

    /**
     * Aperçu d'une facture (lecture seule)
     */
    public function preview(Facture $facture)
    {
        $user = Auth::user();
        
        // Vérifier que la facture appartient au secteur du CS/AGC
        $secteurCode = $user->secteur;
        if (!$secteurCode) {
            return redirect()->route('dashboard')->with('error', 'Aucun secteur assigné à votre compte.');
        }
        
        // Vérifier que la facture appartient à une coopérative de ce secteur
        if (!$facture->cooperative || $facture->cooperative->secteur->code !== $secteurCode) {
            return redirect()->route('cs.factures.index')->with('error', 'Accès refusé.');
        }

        $facture->load(['cooperative', 'ticketsPesee.connaissement.secteur', 'createdBy', 'valideePar']);
        
        // Calculer les prix pour chaque ticket (montant public uniquement)
        $ticketsAvecPrix = [];
        foreach ($facture->ticketsPesee as $ticket) {
            try {
                $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                $ticketsAvecPrix[] = [
                    'ticket' => $ticket,
                    'prix' => [
                        'prix_final_public' => $prix['prix_final_public'],
                        'montant_public' => $prix['prix_final_public']
                    ]
                ];
            } catch (\Exception $e) {
                \Log::error('Erreur calcul prix pour preview facture CS', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Récupérer la navigation pour l'utilisateur CS
        $navigationService = app(\App\Services\NavigationService::class);
        $navigation = $navigationService->getNavigation();
        
        // Utiliser la vue preview individuelle
        return view('cs.factures.preview-individuelle', compact('facture', 'ticketsAvecPrix', 'navigation'));
    }

    /**
     * Imprimer une facture (PDF)
     */
    public function pdf(Facture $facture)
    {
        $user = Auth::user();
        
        // Vérifier que la facture appartient au secteur du CS/AGC
        $secteurCode = $user->secteur;
        if (!$secteurCode) {
            return redirect()->route('dashboard')->with('error', 'Aucun secteur assigné à votre compte.');
        }
        
        // Vérifier que la facture appartient à une coopérative de ce secteur
        if (!$facture->cooperative || $facture->cooperative->secteur->code !== $secteurCode) {
            return redirect()->route('cs.factures.index')->with('error', 'Accès refusé.');
        }

        $facture->load(['cooperative', 'ticketsPesee.connaissement.secteur', 'createdBy', 'valideePar']);
        
        // Calculer les prix pour chaque ticket (montant public uniquement)
        $ticketsAvecPrix = [];
        foreach ($facture->ticketsPesee as $ticket) {
            try {
                $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                $ticketsAvecPrix[] = [
                    'ticket' => $ticket,
                    'prix' => [
                        'prix_final_public' => $prix['prix_final_public'],
                        'montant_public' => $prix['prix_final_public']
                    ]
                ];
            } catch (\Exception $e) {
                \Log::error('Erreur calcul prix pour PDF facture CS', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Utiliser la vue compacte pour le PDF
        $pdf = \PDF::loadView('cs.factures.pdf-compact', compact('facture', 'ticketsAvecPrix'));
        
        return $pdf->download('facture-' . $facture->numero_facture . '.pdf');
    }
}
