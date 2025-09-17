<?php

namespace App\Http\Controllers;

use App\Models\TicketPesee;
use App\Models\Connaissement;
use App\Services\NavigationService;
use App\Services\TicketNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketPeseeController extends Controller
{
    protected $navigationService;
    protected $ticketNumberService;

    public function __construct(NavigationService $navigationService, TicketNumberService $ticketNumberService)
    {
        $this->navigationService = $navigationService;
        $this->ticketNumberService = $ticketNumberService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Récupérer les filtres
        $statut = $request->get('statut', 'all');
        $search = $request->get('search', '');
        
        // Construire la requête de base
        $query = TicketPesee::with(['connaissement.cooperative', 'connaissement.centreCollecte', 'connaissement.secteur', 'createdBy']);

        // Scoping par secteur pour AGC
        if (auth()->check() && auth()->user()->role === 'agc' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            $query->whereHas('connaissement.secteur', function($q) use ($userSecteurCode) {
                $q->where('code', $userSecteurCode);
            });
        }
        
        // Appliquer le filtre de statut
        if ($statut !== 'all') {
            $query->where('statut', $statut);
        }
        
        // Appliquer la recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('numero_livraison', 'like', "%{$search}%")
                  ->orWhereHas('connaissement.cooperative', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%{$search}%");
                  })
                  ->orWhereHas('connaissement.secteur', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%{$search}%");
                  })
                  ->orWhereHas('connaissement', function($q2) use ($search) {
                      $q2->where('numero_livraison', 'like', "%{$search}%");
                  });
            });
        }
        
        // Paginer les résultats
        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.tickets-pesee.index', compact('tickets', 'navigation', 'statut', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->check() && auth()->user()->role === 'agc') {
            abort(403);
        }
        // Récupérer seulement les connaissements validés qui n'ont pas encore de ticket de pesée
        $connaissements = Connaissement::where('statut', 'valide')
            ->whereDoesntHave('ticketsPesee') // Exclure ceux qui ont déjà un ticket
            ->with(['cooperative', 'centreCollecte', 'secteur'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.tickets-pesee.create', compact('connaissements', 'navigation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'connaissement_id' => 'required|exists:connaissements,id',
            'campagne' => 'nullable|string|max:255',
            'client' => 'nullable|string|max:255',
            'fournisseur' => 'nullable|string|max:255',
            'origine' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'numero_camion' => 'nullable|string|max:255',
            'transporteur' => 'required|string|max:255',
            'chauffeur' => 'required|string|max:255',
            'poids_entree' => 'required|numeric|min:0.01',
            'poids_sortie' => 'required|numeric|min:0.01',
            'nombre_sacs_bidons_cartons' => 'required|integer|min:1',
            'date_entree' => 'required|date',
            'heure_entree' => 'required|date_format:H:i',
            'date_sortie' => 'required|date',
            'heure_sortie' => 'required|date_format:H:i',
            'nom_peseur' => 'required|string|max:255',
            'poids_100_graines' => 'nullable|numeric|min:0',
            'gp' => 'nullable|numeric|min:0|max:100',
            'ga' => 'nullable|numeric|min:0|max:100',
            'me' => 'nullable|numeric|min:0|max:100',
            'taux_humidite' => 'nullable|numeric|min:0|max:100',
            'taux_impuretes' => 'nullable|numeric|min:0|max:100'
        ]);

        // Vérifier qu'il n'existe pas déjà un ticket pour ce connaissement
        $existingTicket = TicketPesee::where('connaissement_id', $request->connaissement_id)->first();
        if ($existingTicket) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Un ticket de pesée existe déjà pour ce connaissement (N° ' . $existingTicket->numero_ticket . '). Vous pouvez le modifier mais pas en créer un nouveau.');
        }

        // Récupérer le connaissement pour obtenir le numéro de livraison
        $connaissement = Connaissement::findOrFail($request->connaissement_id);

        // Générer le numéro de ticket unique
        $numeroTicket = $this->ticketNumberService->generateNumber($connaissement->numero_livraison);

        // Calculer le poids net automatiquement
        $poidsNet = $request->poids_entree - $request->poids_sortie;
        
        // Calculer le taux d'impuretés automatiquement (GP + GA + ME)
        $tauxImpuretes = ($request->gp ?? 0) + ($request->ga ?? 0) + ($request->me ?? 0);

        $ticketPesee = TicketPesee::create([
            'numero_livraison' => $connaissement->numero_livraison,
            'numero_ticket' => $numeroTicket,
            'connaissement_id' => $request->connaissement_id,
            'campagne' => '2025',
            'client' => 'COTRAF SA',
            'fournisseur' => 'FPH-CI',
            'origine' => $request->origine,
            'destination' => $request->destination,
            'numero_camion' => $request->numero_camion,
            'transporteur' => $request->transporteur,
            'chauffeur' => $request->chauffeur,
            'poids_entree' => $request->poids_entree,
            'poids_sortie' => $request->poids_sortie,
            'poids_net' => $poidsNet,
            'nombre_sacs_bidons_cartons' => $request->nombre_sacs_bidons_cartons,
            'date_entree' => $request->date_entree,
            'heure_entree' => $request->heure_entree,
            'date_sortie' => $request->date_sortie,
            'heure_sortie' => $request->heure_sortie,
            'nom_peseur' => $request->nom_peseur,
            'poids_100_graines' => 100,
            'gp' => $request->gp,
            'ga' => $request->ga,
            'me' => $request->me,
            'taux_humidite' => $request->taux_humidite,
            'taux_impuretes' => $tauxImpuretes,
            'statut' => 'en_attente',
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.tickets-pesee.index')
            ->with('success', "Ticket de pesée créé avec succès ! Numéro de ticket : {$numeroTicket}");
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketPesee $ticketPesee)
    {
        $ticketPesee->load(['connaissement.cooperative', 'connaissement.centreCollecte', 'connaissement.secteur', 'createdBy', 'validatedBy']);
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.tickets-pesee.show', compact('ticketPesee', 'navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketPesee $ticketPesee)
    {
        if (auth()->check() && auth()->user()->role === 'agc') {
            abort(403);
        }
        $connaissements = Connaissement::where('statut', 'valide')
            ->orWhere('id', $ticketPesee->connaissement_id)
            ->with(['cooperative', 'centreCollecte', 'secteur'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.tickets-pesee.edit', compact('ticketPesee', 'connaissements', 'navigation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketPesee $ticketPesee)
    {
        if (auth()->check() && auth()->user()->role === 'agc') {
            abort(403);
        }
        $request->validate([
            'connaissement_id' => 'required|exists:connaissements,id',
            'campagne' => 'nullable|string|max:255',
            'client' => 'nullable|string|max:255',
            'fournisseur' => 'nullable|string|max:255',
            'origine' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'numero_camion' => 'nullable|string|max:255',
            'transporteur' => 'required|string|max:255',
            'chauffeur' => 'required|string|max:255',
            'poids_entree' => 'required|numeric|min:0.01',
            'poids_sortie' => 'required|numeric|min:0.01',
            'nombre_sacs_bidons_cartons' => 'required|integer|min:1',
            'date_entree' => 'required|date',
            'heure_entree' => 'required|date_format:H:i',
            'date_sortie' => 'required|date',
            'heure_sortie' => 'required|date_format:H:i',
            'nom_peseur' => 'required|string|max:255',
            'poids_100_graines' => 'nullable|numeric|min:0',
            'gp' => 'nullable|numeric|min:0|max:100',
            'ga' => 'nullable|numeric|min:0|max:100',
            'me' => 'nullable|numeric|min:0|max:100',
            'taux_humidite' => 'nullable|numeric|min:0|max:100',
            'taux_impuretes' => 'nullable|numeric|min:0|max:100'
        ]);

        // Récupérer le connaissement pour obtenir le numéro de livraison
        $connaissement = Connaissement::findOrFail($request->connaissement_id);

        $ticketPesee->update([
            'numero_livraison' => $connaissement->numero_livraison,
            'connaissement_id' => $request->connaissement_id,
            'campagne' => '2025',
            'client' => 'COTRAF SA',
            'fournisseur' => 'FPH-CI',
            'origine' => $request->origine,
            'destination' => $request->destination,
            'numero_camion' => $request->numero_camion,
            'transporteur' => $request->transporteur,
            'chauffeur' => $request->chauffeur,
            'poids_entree' => $request->poids_entree,
            'poids_sortie' => $request->poids_sortie,
            'nombre_sacs_bidons_cartons' => $request->nombre_sacs_bidons_cartons,
            'date_entree' => $request->date_entree,
            'heure_entree' => $request->heure_entree,
            'date_sortie' => $request->date_sortie,
            'heure_sortie' => $request->heure_sortie,
            'nom_peseur' => $request->nom_peseur,
            'poids_100_graines' => 100,
            'gp' => $request->gp,
            'ga' => $request->ga,
            'me' => $request->me,
            'taux_humidite' => $request->taux_humidite,
            'taux_impuretes' => $request->taux_impuretes,
        ]);

        return redirect()->route('admin.tickets-pesee.index')
            ->with('success', 'Ticket de pesée modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketPesee $ticketPesee)
    {
        if ($ticketPesee->statut === 'valide') {
            return redirect()->route('admin.tickets-pesee.index')
                ->with('error', 'Impossible de supprimer un ticket de pesée validé pour paiement.');
        }

        $ticketPesee->delete();

        return redirect()->route('admin.tickets-pesee.index')
            ->with('success', 'Ticket de pesée supprimé avec succès !');
    }

    /**
     * Valider un ticket de pesée
     */
    public function validate(Request $request, TicketPesee $ticketPesee)
    {
        // Utiliser le poids net déjà calculé
        $poidsNet = $ticketPesee->poids_net ?? ($ticketPesee->poids_entree - $ticketPesee->poids_sortie);

        $ticketPesee->update([
            'statut' => 'valide',
            'poids_net' => $poidsNet,
            'date_validation' => now(),
            'validated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.tickets-pesee.index')
            ->with('success', 'Ticket de pesée validé avec succès !');
    }

    /**
     * Annuler la validation d'un ticket de pesée
     */
    public function cancelValidation(TicketPesee $ticketPesee)
    {
        $ticketPesee->update([
            'statut' => 'en_attente',
            'poids_net' => null,
            'date_validation' => null,
            'validated_by' => null,
        ]);

        return redirect()->route('admin.tickets-pesee.index')
            ->with('success', 'Validation du ticket de pesée annulée avec succès !');
    }
}