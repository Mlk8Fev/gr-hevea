<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\TicketPesee;
use App\Models\Cooperative;
use App\Services\CalculPrixService;
use App\Services\NavigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FactureController extends Controller
{
    protected $calculPrixService;
    protected $navigationService;

    public function __construct(CalculPrixService $calculPrixService, NavigationService $navigationService)
    {
        $this->calculPrixService = $calculPrixService;
        $this->navigationService = $navigationService;
    }

    /**
     * Afficher la liste des factures
     */
    public function index(Request $request)
    {
        // Construire la requête de base avec les relations nécessaires
        $query = Facture::with(['cooperative.secteur', 'createdBy', 'valideePar', 'ticketsPesee']);
        
        // Filtre par secteur
        if ($request->filled('secteur')) {
            $query->whereHas('cooperative', function($q) use ($request) {
                $q->where('secteur_id', $request->secteur);
            });
        }
        
        // Filtre par coopérative
        if ($request->filled('cooperative')) {
            $query->where('cooperative_id', $request->cooperative);
        }
        
        // Filtre par type de facture
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Filtre par statut
        if ($request->filled('statut') && $request->statut !== 'all') {
            $query->where('statut', $request->statut);
        }
        
        // Filtre par date d'émission
        if ($request->filled('date_emission')) {
            $query->whereDate('date_emission', $request->date_emission);
        }
        
        // Recherche par numéro de facture et autres champs
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('numero_facture', 'LIKE', "%{$request->search}%")
                  ->orWhere('numero_livraison', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('cooperative', function($q2) use ($request) {
                      $q2->where('nom', 'LIKE', "%{$request->search}%")
                         ->orWhere('code', 'LIKE', "%{$request->search}%");
                  })
                  ->orWhereHas('cooperative.secteur', function($q2) use ($request) {
                      $q2->where('nom', 'LIKE', "%{$request->search}%")
                         ->orWhere('code', 'LIKE', "%{$request->search}%");
                  });
            });
        }
        
        // Paginer les résultats
        $factures = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10));
        
        // Récupérer les données pour les filtres
        $secteurs = \App\Models\Secteur::orderBy('code')->get();
        $cooperatives = \App\Models\Cooperative::with('secteur')->orderBy('nom')->get();
        $types = ['individuelle' => 'Individuelle', 'globale' => 'Globale'];
        $statuts = ['brouillon' => 'Brouillon', 'validee' => 'Validée', 'payee' => 'Payée', 'annulee' => 'Annulée'];
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.factures.index', compact('factures', 'secteurs', 'cooperatives', 'types', 'statuts', 'navigation'));
    }

    /**
     * Afficher le formulaire de création de facture
     */
    public function create(Request $request)
    {
        // Récupérer le type de facture (individuelle ou globale)
        $type = $request->get('type', 'individuelle');
        
        // Récupérer les tickets éligibles pour facturation
        $ticketsEligibles = TicketPesee::where('statut', 'valide')
            ->where('statut_ene', 'valide_par_ene')
            ->whereDoesntHave('factures') // Pas encore facturés
            ->with(['connaissement.cooperative', 'connaissement.centreCollecte', 'connaissement.secteur'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculer les prix pour chaque ticket
        $ticketsAvecPrix = [];
        foreach ($ticketsEligibles as $ticket) {
            try {
                $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                $ticketsAvecPrix[] = [
                    'ticket' => $ticket,
                    'prix' => $prix
                ];
            } catch (\Exception $e) {
                Log::error('Erreur calcul prix pour facturation', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.factures.create', compact('ticketsAvecPrix', 'type', 'navigation'));
    }

    /**
     * Créer une facture individuelle
     */
    public function store(Request $request)
    {
        $request->validate([
            'tickets_ids' => 'required|array|min:1',
            'tickets_ids.*' => 'exists:tickets_pesee,id',
            'type' => 'required|in:individuelle,globale',
        ]);

        DB::beginTransaction();
        
        try {
            // Récupérer les tickets de pesée
            $ticketsPesee = TicketPesee::with(['connaissement'])->whereIn('id', $request->tickets_ids)->get();
            
            if ($ticketsPesee->isEmpty()) {
                throw new \Exception('Aucun ticket valide sélectionné');
            }
            
            // Vérifier que tous les tickets appartiennent à la même coopérative
            $cooperativeId = $ticketsPesee->first()->connaissement->cooperative_id;
            foreach ($ticketsPesee as $ticket) {
                if ($ticket->connaissement->cooperative_id !== $cooperativeId) {
                    throw new \Exception('Tous les tickets doivent appartenir à la même coopérative');
                }
            }
            
            // Calculer les montants totaux
            $montantHt = 0;
            $montantTva = 0;
            $montantTtc = 0;
            
            foreach ($ticketsPesee as $ticket) {
                try {
                    $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                    $montantTicket = $prix['details']['montant_public'] ?? 0;
                    $montantHt += $montantTicket;
                } catch (\Exception $e) {
                    Log::warning('Erreur calcul prix pour facturation', [
                        'ticket_id' => $ticket->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Pour l'instant, on considère que le montant HT = montant total (pas de TVA)
            $montantTva = 0;
            $montantTtc = $montantHt;
            
            // Générer le numéro de facture
            $numeroFacture = 'FACT-' . date('Y') . '-' . str_pad(Facture::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Créer la facture avec tous les montants
            $facture = Facture::create([
                'numero_facture' => $numeroFacture,
                'numero_livraison' => $ticketsPesee->first()->numero_livraison,
                'type' => $request->type,
                'cooperative_id' => $cooperativeId,
                'statut' => 'brouillon',
                'montant_ht' => $montantHt,
                'montant_tva' => $montantTva,
                'montant_ttc' => $montantTtc,
                'montant_paye' => 0,
                'date_emission' => now()->toDateString(),
                'date_echeance' => now()->addDays(30)->toDateString(),
                'devise' => 'XOF', // 3 caractères max
                'created_by' => auth()->id()
            ]);
            
            // Associer les tickets à la facture avec les montants
            $ticketsData = [];
            foreach ($ticketsPesee as $ticket) {
                try {
                    $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                    $montantTicket = $prix['details']['montant_public'] ?? 0;
                    
                    $ticketsData[$ticket->id] = [
                        'montant_ticket' => $montantTicket,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                } catch (\Exception $e) {
                    Log::warning('Erreur calcul prix pour facturation', [
                        'ticket_id' => $ticket->id,
                        'error' => $e->getMessage()
                    ]);
                    
                    // Montant par défaut en cas d'erreur
                    $ticketsData[$ticket->id] = [
                        'montant_ticket' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            // Associer les tickets à la facture avec les montants
            $facture->ticketsPesee()->sync($ticketsData);
            
            DB::commit();
            
            return redirect()->route('admin.factures.show', $facture)
                ->with('success', "Facture créée avec succès ! Numéro : {$numeroFacture}");
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur création facture', [
                'error' => $e->getMessage(),
                'tickets_ids' => $request->tickets_ids
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la facture : ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'une facture
     */
    public function show(Facture $facture)
    {
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
                Log::error('Erreur calcul prix pour affichage facture', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.factures.show', compact('facture', 'ticketsAvecPrix', 'navigation'));
    }

    /**
     * Aperçu d'une facture
     */
    public function preview(Facture $facture)
    {
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
                Log::error('Erreur calcul prix pour aperçu facture', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $navigation = $this->navigationService->getNavigation();
        
        // Utiliser la bonne vue selon le type de facture
        $viewName = $facture->isGlobale() ? 'admin.factures.preview-globale' : 'admin.factures.preview-individuelle';
        
        return view($viewName, compact('facture', 'ticketsAvecPrix', 'navigation'));
    }

    /**
     * Valider une facture
     */
    public function validate(Request $request, Facture $facture)
    {
        // Pas besoin de validation de montant car il est déjà calculé
        $facture->update([
            'statut' => 'validee',
            'date_validation' => now(),
            'validee_par' => auth()->id(),
        ]);

        return redirect()->route('admin.factures.show', $facture)
            ->with('success', 'Facture validée avec succès !');
    }
    /**
     * Marquer une facture comme payée
     */
    public function markAsPaid(Request $request, Facture $facture)
    {
        $request->validate([
            'montant_paye' => 'required|numeric|min:0|max:' . $facture->montant_ttc,
            'date_paiement' => 'required|date|before_or_equal:today',
        ]);

        if ($facture->statut !== 'validee') {
            return redirect()->back()
                ->with('error', 'Seules les factures validées peuvent être marquées comme payées.');
        }

        $montantPaye = $request->montant_paye;
        $statut = 'payee';

        $facture->update([
            'montant_paye' => $montantPaye,
            'statut' => $statut,
            'date_paiement' => $request->date_paiement,
        ]);

        $message = 'Facture marquée comme payée !';

        return redirect()->route('admin.factures.show', $facture)
            ->with('success', $message);
    }

    /**
     * Annuler une facture
     */
    public function cancel(Facture $facture)
    {
        if ($facture->statut === 'validee') {
            return redirect()->back()
                ->with('error', 'Impossible d\'annuler une facture validée.');
        }

        $facture->update([
            'statut' => 'annulee',
            'date_annulation' => now(),
        ]);

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture annulée avec succès !');
    }

    /**
     * Supprimer une facture
     */
    public function destroy(Facture $facture)
    {
        if ($facture->statut === 'validee') {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une facture validée.');
        }

        $facture->delete();

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture supprimée avec succès !');
    }

    /**
     * Générer le PDF d'une facture
     */
    public function pdf(Facture $facture)
    {
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
                Log::error('Erreur calcul prix pour PDF facture', [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return view('admin.factures.pdf', compact('facture', 'ticketsAvecPrix'));
    }

    /**
     * Générer le PDF global des factures
     */
    public function pdfGlobale(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $factures = Facture::where('statut', 'validee')
            ->whereBetween('date_validation', [$request->date_debut, $request->date_fin])
            ->with(['cooperative', 'ticketsPesee.connaissement.secteur'])
            ->orderBy('date_validation')
            ->get();

        return view('admin.factures.pdf-globale', compact('factures'));
    }
}
