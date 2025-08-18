<?php

namespace App\Http\Controllers;

use App\Models\TicketPesee;
use App\Models\Connaissement;
use App\Services\NavigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketPeseeController extends Controller
{
    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = TicketPesee::with(['connaissement.cooperative', 'connaissement.centreCollecte', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.tickets-pesee.index', compact('tickets', 'navigation'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Debug temporaire - forcer la récupération
        $ticketPesee = TicketPesee::findOrFail($id);
        
        \Log::info('TicketPesee show method', [
            'id' => $ticketPesee->id ?? 'NULL',
            'numero_ticket' => $ticketPesee->numero_ticket ?? 'NULL',
            'exists' => $ticketPesee->exists ?? 'NULL',
            'attributes' => $ticketPesee->getAttributes() ?? []
        ]);
        
        $ticketPesee->load(['connaissement.cooperative', 'connaissement.centreCollecte', 'createdBy', 'validatedBy']);
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.tickets-pesee.show', compact('ticketPesee', 'navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketPesee $ticketPesee)
    {
        $connaissements = Connaissement::where('statut', 'valide')
            ->orWhere('id', $ticketPesee->connaissement_id)
            ->with(['cooperative', 'centreCollecte'])
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
        $request->validate([
            'connaissement_id' => 'required|exists:connaissements,id',
            'campagne' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'fournisseur' => 'required|string|max:255',
            'origine' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'numero_camion' => 'required|string|max:255',
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

        $ticketPesee->update($request->all());

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
    public function validate(TicketPesee $ticketPesee)
    {
        if ($ticketPesee->statut === 'valide') {
            return redirect()->route('admin.tickets-pesee.index')
                ->with('error', 'Ce ticket de pesée est déjà validé pour paiement.');
        }

        $ticketPesee->update([
            'statut' => 'valide',
            'date_validation' => now(),
            'validated_by' => auth()->id()
        ]);

        return redirect()->route('admin.tickets-pesee.index')
            ->with('success', 'Ticket de pesée validé pour paiement avec succès !');
    }

    /**
     * Archiver un ticket de pesée
     */
    public function archive(TicketPesee $ticketPesee)
    {
        if ($ticketPesee->statut !== 'valide') {
            return redirect()->route('admin.tickets-pesee.index')
                ->with('error', 'Seuls les tickets validés pour paiement peuvent être archivés.');
        }

        $ticketPesee->update([
            'statut' => 'archive'
        ]);

        return redirect()->route('admin.tickets-pesee.index')
            ->with('success', 'Ticket de pesée archivé avec succès !');
    }

    /**
     * Générer le PDF du ticket de pesée
     */
    public function generatePdf($id)
    {
        // Debug temporaire - forcer la récupération
        $ticketPesee = TicketPesee::findOrFail($id);
        
        \Log::info('TicketPesee generatePdf method', [
            'id' => $ticketPesee->id ?? 'NULL',
            'numero_ticket' => $ticketPesee->numero_ticket ?? 'NULL',
            'exists' => $ticketPesee->exists ?? 'NULL'
        ]);
        
        $ticketPesee->load(['connaissement.cooperative', 'connaissement.centreCollecte', 'createdBy', 'validatedBy']);
        
        // Ici on utilisera DomPDF pour générer le PDF
        // Pour l'instant, on redirige vers la vue show
        return view('admin.tickets-pesee.pdf', compact('ticketPesee'));
    }

    public function create()
    {
        // Récupérer seulement les connaissements validés pour ticket de pesée avec toutes les informations nécessaires
        $connaissements = Connaissement::where('statut', 'valide')
            ->whereDoesntHave('ticketPesee') // Éviter les doublons
            ->with(['cooperative', 'centreCollecte'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($connaissement) {
                // Ajouter des informations calculées ou par défaut
                $connaissement->date_reception_formatted = $connaissement->date_reception ? $connaissement->date_reception->format('Y-m-d') : null;
                return $connaissement;
            });
        
        $navigation = $this->navigationService->getNavigation();
        return view('admin.tickets-pesee.create', compact('connaissements', 'navigation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'connaissement_id' => 'required|exists:connaissements,id',
            'campagne' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'fournisseur' => 'required|string|max:255',
            'origine' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'numero_camion' => 'required|string|max:255',
            'equipe_chargement' => 'nullable|string|max:255',
            'equipe_dechargement' => 'nullable|string|max:255',
            'poids_entree' => 'required|numeric|min:0.01',
            'poids_sortie' => 'required|numeric|min:0.01',
            'nombre_sacs_bidons_cartons' => 'required|integer|min:1',
            'date_entree' => 'required|date',
            'heure_entree' => 'required',
            'date_sortie' => 'required|date',
            'heure_sortie' => 'required',
            'nom_peseur' => 'required|string|max:255',
            'poids_100_graines' => 'nullable|numeric|min:0',
            'gp' => 'nullable|numeric|min:0|max:100',
            'ga' => 'nullable|numeric|min:0|max:100',
            'me' => 'nullable|numeric|min:0|max:100',
            'taux_humidite' => 'nullable|numeric|min:0|max:100',
        ]);

        // Vérifier que le connaissement est validé pour ticket de pesée
        $connaissement = Connaissement::findOrFail($request->connaissement_id);
        if ($connaissement->statut !== 'valide') {
            return redirect()->back()->withErrors(['connaissement_id' => 'Le connaissement doit être validé pour ticket de pesée pour créer un ticket de pesée.']);
        }

        // Vérifier qu'il n'y a pas déjà un ticket pour ce connaissement
        if ($connaissement->ticketPesee) {
            return redirect()->back()->withErrors(['connaissement_id' => 'Un ticket de pesée existe déjà pour ce connaissement.']);
        }

        // Générer le numéro de ticket unique
        $numeroTicket = 'TP' . date('Y') . str_pad(TicketPesee::count() + 1, 4, '0', STR_PAD_LEFT);

        // Créer le ticket de pesée
        $ticketPesee = TicketPesee::create([
            'connaissement_id' => $request->connaissement_id,
            'numero_ticket' => $numeroTicket,
            'campagne' => $request->campagne,
            'client' => $request->client,
            'fournisseur' => $request->fournisseur,
            'numero_bl' => $connaissement->numero,
            'origine' => $request->origine,
            'destination' => $request->destination,
            'produit' => 'GRAINE DE HEVEA',
            'numero_camion' => $request->numero_camion,
            'transporteur' => $connaissement->transporteur_nom,
            'chauffeur' => $connaissement->chauffeur_nom,
            'equipe_chargement' => $request->equipe_chargement,
            'equipe_dechargement' => $request->equipe_dechargement,
            'poids_entree' => $request->poids_entree,
            'poids_sortie' => $request->poids_sortie,
            'nombre_sacs_bidons_cartons' => $request->nombre_sacs_bidons_cartons,
            'poids_100_graines' => $request->poids_100_graines,
            'gp' => $request->gp,
            'ga' => $request->ga,
            'me' => $request->me,
            'taux_humidite' => $request->taux_humidite,
            'taux_impuretes' => ($request->gp ?? 0) + ($request->ga ?? 0) + ($request->me ?? 0),
            'date_entree' => $request->date_entree,
            'heure_entree' => $request->heure_entree,
            'date_sortie' => $request->date_sortie,
            'heure_sortie' => $request->heure_sortie,
            'nom_peseur' => $request->nom_peseur,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.tickets-pesee.index')
            ->with('success', 'Ticket de pesée créé avec succès !');
    }
}
