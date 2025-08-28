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
        // Récupérer les filtres
        $type = $request->get('type', 'all');
        $statut = $request->get('statut', 'all');
        $cooperative = $request->get('cooperative', 'all');
        $search = $request->get('search', '');
        
        // Construire la requête de base
        $query = Facture::with(['cooperative', 'createdBy', 'valideePar', 'ticketsPesee']);
        
        // Appliquer les filtres
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        if ($statut !== 'all') {
            $query->where('statut', $statut);
        }
        
        if ($cooperative !== 'all') {
            $query->where('cooperative_id', $cooperative);
        }
        
        // Appliquer la recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                  ->orWhereHas('cooperative', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%{$search}%");
                  });
            });
        }
        
        // Paginer les résultats
        $factures = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Récupérer les coopératives pour le filtre
        $cooperatives = Cooperative::orderBy('nom')->get();
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.factures.index', compact('factures', 'cooperatives', 'navigation', 'type', 'statut', 'cooperative', 'search'));
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
            ->with(['connaissement.cooperative', 'connaissement.centreCollecte'])
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
                    'erreur' => $e->getMessage()
                ]);
            }
        }
        
        // Grouper par coopérative
        $ticketsParCooperative = collect($ticketsAvecPrix)->groupBy('ticket.connaissement.cooperative_id');
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.factures.create', compact('type', 'ticketsAvecPrix', 'ticketsParCooperative', 'navigation'));
    }

    /**
     * Créer une nouvelle facture
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:individuelle,globale',
            'tickets_ids' => 'required|array|min:1',
            'tickets_ids.*' => 'exists:tickets_pesee,id'
        ]);

        try {
            DB::beginTransaction();
            
            // Récupérer les tickets sélectionnés
            $tickets = TicketPesee::whereIn('id', $request->tickets_ids)
                ->where('statut', 'valide')
                ->where('statut_ene', 'valide_par_ene')
                ->whereDoesntHave('factures')
                ->with('connaissement.cooperative')
                ->get();
            
            if ($tickets->isEmpty()) {
                return back()->with('error', 'Aucun ticket éligible trouvé pour la facturation.');
            }
            
            // Vérifier que tous les tickets appartiennent à la même coopérative
            $cooperativeIds = $tickets->pluck('connaissement.cooperative_id')->unique();
            if ($cooperativeIds->count() > 1) {
                return back()->with('error', 'Tous les tickets sélectionnés doivent appartenir à la même coopérative.');
            }
            
            $cooperativeId = $cooperativeIds->first();
            
            // Calculer les totaux
            $montantHt = 0;
            $montantTtc = 0;
            $ticketsMontants = [];
            
            foreach ($tickets as $ticket) {
                try {
                    // Récupérer le prix calculé depuis le service Finance
                    $prix = $this->calculPrixService->calculerPrixTicket($ticket);
                    $montantTicket = $prix['details']['montant_public'];
                    
                    $montantHt += $montantTicket;
                    $montantTtc += $montantTicket; // Pas de TVA pour l'instant
                    
                    $ticketsMontants[$ticket->id] = $montantTicket;
                } catch (\Exception $e) {
                    Log::error('Erreur calcul prix pour facturation', [
                        'ticket_id' => $ticket->id,
                        'erreur' => $e->getMessage()
                    ]);
                    return back()->with('error', 'Erreur lors du calcul des prix pour la facturation.');
                }
            }
            
            // Créer la facture
            $facture = Facture::create([
                'numero_facture' => Facture::generateNumeroFacture(),
                'type' => $request->type,
                'statut' => 'brouillon',
                'cooperative_id' => $cooperativeId,
                'montant_ht' => $montantHt,
                'montant_tva' => 0, // Pas de TVA pour l'instant
                'montant_ttc' => $montantTtc,
                'montant_paye' => 0,
                'date_emission' => now(),
                'date_echeance' => now()->addDays(30), // Date d'échéance par défaut
                'conditions_paiement' => 'Paiement à 30 jours par virement bancaire',
                'notes' => 'Facture générée automatiquement',
                'devise' => 'XOF',
                'created_by' => auth()->id()
            ]);
            
            // Lier les tickets à la facture
            foreach ($ticketsMontants as $ticketId => $montant) {
                $facture->ticketsPesee()->attach($ticketId, [
                    'montant_ticket' => $montant
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.factures.show', $facture)
                ->with('success', 'Facture créée avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création facture', [
                'erreur' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors de la création de la facture : ' . $e->getMessage());
        }
    }

    /**
     * Afficher une facture
     */
    public function show(Facture $facture)
    {
        $facture->load([
            'cooperative',
            'createdBy',
            'valideePar',
            'ticketsPesee.connaissement.cooperative',
            'ticketsPesee.connaissement.centreCollecte',
            'factureTicketsPesee.ticketPesee'
        ]);
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.factures.show', compact('facture', 'navigation'));
    }

    /**
     * Valider une facture
     */
    public function validate(Request $request, Facture $facture)
    {
        if (!$facture->canBeValidated()) {
            return back()->with('error', 'Cette facture ne peut pas être validée.');
        }
        
        $facture->update([
            'statut' => 'validee',
            'validee_par' => auth()->id(),
            'date_validation' => now()
        ]);
        
        return back()->with('success', 'Facture validée avec succès !');
    }

    /**
     * Marquer une facture comme payée
     */
    public function markAsPaid(Request $request, Facture $facture)
    {
        if (!$facture->canBePaid()) {
            return back()->with('error', 'Cette facture ne peut pas être marquée comme payée.');
        }
        
        $request->validate([
            'montant_paye' => 'required|numeric|min:0.01|max:' . $facture->montant_ttc
        ]);
        
        $facture->update([
            'montant_paye' => $request->montant_paye,
            'date_paiement' => now()
        ]);
        
        // Si le montant payé est égal au montant TTC, marquer comme payée
        if ($facture->montant_paye >= $facture->montant_ttc) {
            $facture->update(['statut' => 'payee']);
        }
        
        return back()->with('success', 'Paiement enregistré avec succès !');
    }

    /**
     * Afficher la preview de la facture
     */
    public function preview(Facture $facture)
    {
        // Charger les relations nécessaires
        $facture->load([
            'cooperative',
            'factureTicketsPesee.ticketPesee.connaissement.centreCollecte',
            'createdBy',
            'valideePar'
        ]);
        
        // Choisir la vue de preview selon le type de facture
        $view = $facture->type === 'globale' ? 'admin.factures.preview-globale' : 'admin.factures.preview-individuelle';
        
        return view($view, compact('facture'));
    }

    /**
     * Générer le PDF de la facture
     */
    public function generatePdf(Facture $facture)
    {
        try {
            // Charger les relations nécessaires
            $facture->load([
                'cooperative',
                'factureTicketsPesee.ticketPesee.connaissement.centreCollecte',
                'createdBy',
                'valideePar'
            ]);
            
            // Choisir la vue PDF selon le type de facture
            $view = $facture->type === 'globale' ? 'admin.factures.pdf-globale' : 'admin.factures.pdf-individuelle';
            
            // Générer le PDF
            $pdf = \PDF::loadView($view, compact('facture'));
            
            // Configuration du PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'chroot' => public_path()
            ]);
            
            // Nom du fichier
            $filename = 'Facture_' . $facture->numero_facture . '_' . $facture->cooperative->nom . '.pdf';
            
            // Retourner le PDF pour téléchargement
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du PDF', [
                'facture_id' => $facture->id,
                'erreur' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une facture (seulement si en brouillon)
     */
    public function destroy(Facture $facture)
    {
        if ($facture->statut !== 'brouillon') {
            return back()->with('error', 'Impossible de supprimer une facture qui n\'est pas en brouillon.');
        }
        
        // Supprimer les liens avec les tickets
        $facture->ticketsPesee()->detach();
        
        // Supprimer la facture
        $facture->delete();
        
        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture supprimée avec succès !');
    }
}
