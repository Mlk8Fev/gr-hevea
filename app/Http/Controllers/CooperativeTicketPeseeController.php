<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketPesee;
use App\Models\Cooperative;
use App\Models\Secteur;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationService;

class CooperativeTicketPeseeController extends Controller
{
    /**
     * Afficher les tickets de pesée de la coopérative du responsable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer la coopérative du responsable
        $cooperative = Cooperative::find($user->cooperative_id);
        
        if (!$cooperative) {
            return redirect()->route('dashboard')->with('error', 'Aucune coopérative assignée à votre compte.');
        }

        // Récupérer les tickets de pesée de cette coopérative uniquement
        $query = TicketPesee::whereHas('connaissement', function($q) use ($cooperative) {
            $q->where('cooperative_id', $cooperative->id);
        })->with(['connaissement.cooperative', 'connaissement.secteur']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('numero_ticket', 'like', "%{$search}%");
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        // Récupérer les secteurs pour les filtres
        $secteurs = Secteur::orderBy('code')->get();
        $cooperatives = Cooperative::with('secteur')->orderBy('nom')->get();
        $statuts = ['en_attente' => 'En attente', 'valide' => 'Validé', 'annule' => 'Annulé'];

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return view('cooperative.tickets-pesee.index', compact('tickets', 'cooperative', 'secteurs', 'cooperatives', 'statuts', 'navigation'));
    }

    /**
     * Afficher les détails d'un ticket de pesée (lecture seule)
     */
    public function show(TicketPesee $ticketPesee)
    {
        $user = Auth::user();
        $cooperative = Cooperative::find($user->cooperative_id);

        // Vérifier que le ticket appartient à la coopérative du responsable
        if ($ticketPesee->connaissement->cooperative_id !== $cooperative->id) {
            return redirect()->route('cooperative.tickets-pesee.index')->with('error', 'Accès refusé.');
        }

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return view('cooperative.tickets-pesee.show', compact('ticketPesee', 'cooperative', 'navigation'));
    }
}
