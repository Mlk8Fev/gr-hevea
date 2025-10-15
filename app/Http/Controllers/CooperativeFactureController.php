<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Cooperative;
use App\Models\Secteur;
use App\Services\CalculPrixService;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationService;

class CooperativeFactureController extends Controller
{
    protected $calculPrixService;

    public function __construct(CalculPrixService $calculPrixService)
    {
        $this->calculPrixService = $calculPrixService;
    }
    /**
     * Afficher les factures de la coopérative du responsable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer la coopérative du responsable
        $cooperative = Cooperative::find($user->cooperative_id);
        
        if (!$cooperative) {
            return redirect()->route('dashboard')->with('error', 'Aucune coopérative assignée à votre compte.');
        }

        // Récupérer les factures de cette coopérative uniquement
        $query = Facture::where('cooperative_id', $cooperative->id)
            ->with(['cooperative', 'ticketsPesee']);

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

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return view('cooperative.factures.index', compact('factures', 'cooperative', 'secteurs', 'cooperatives', 'types', 'statuts', 'navigation'));
    }

    /**
     * Afficher le formulaire de création de facture (lecture seule pour les responsables)
     */
    public function create()
    {
        $user = Auth::user();
        
        // Récupérer la coopérative du responsable
        $cooperative = Cooperative::find($user->cooperative_id);
        
        if (!$cooperative) {
            return redirect()->route('dashboard')->with('error', 'Aucune coopérative assignée à votre compte.');
        }

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return redirect()->route('cooperative.factures.index')->with('info', 'La création de factures est réservée aux administrateurs.');
    }

    /**
     * Afficher les détails d'une facture (lecture seule)
     */
    public function show(Facture $facture)
    {
        $user = Auth::user();
        $cooperative = Cooperative::find($user->cooperative_id);

        // Vérifier que la facture appartient à la coopérative du responsable
        if ($facture->cooperative_id !== $cooperative->id) {
            return redirect()->route('cooperative.factures.index')->with('error', 'Accès refusé.');
        }

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return view('cooperative.factures.show', compact('facture', 'cooperative', 'navigation'));
    }

    /**
     * Aperçu d'une facture (lecture seule)
     */
    public function preview(Facture $facture)
    {
        $user = Auth::user();
        $cooperative = Cooperative::find($user->cooperative_id);

        // Vérifier que la facture appartient à la coopérative du responsable
        if ($facture->cooperative_id !== $cooperative->id) {
            return redirect()->route('cooperative.factures.index')->with('error', 'Accès refusé.');
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
                \Log::error('Erreur calcul prix pour preview facture', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);
        
        // Utiliser la vue preview individuelle
        return view('cooperative.factures.preview-individuelle', compact('facture', 'ticketsAvecPrix', 'navigation'));
    }

    /**
     * Imprimer une facture (PDF)
     */
    public function pdf(Facture $facture)
    {
        $user = Auth::user();
        $cooperative = Cooperative::find($user->cooperative_id);

        // Vérifier que la facture appartient à la coopérative du responsable
        if ($facture->cooperative_id !== $cooperative->id) {
            return redirect()->route('cooperative.factures.index')->with('error', 'Accès refusé.');
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
                \Log::error('Erreur calcul prix pour PDF facture', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Utiliser la vue compacte pour le PDF
        $pdf = \PDF::loadView('cooperative.factures.pdf-compact', compact('facture', 'ticketsAvecPrix'));
        
        return $pdf->download('facture-' . $facture->numero_facture . '.pdf');
    }
}
