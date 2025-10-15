<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Connaissement;
use App\Models\Cooperative;
use App\Models\Secteur;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationService;

class CooperativeConnaissementController extends Controller
{
    /**
     * Afficher les connaissements de la coopérative du responsable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer la coopérative du responsable
        $cooperative = Cooperative::find($user->cooperative_id);
        
        if (!$cooperative) {
            return redirect()->route('dashboard')->with('error', 'Aucune coopérative assignée à votre compte.');
        }

        // Récupérer les connaissements de cette coopérative uniquement
        $query = Connaissement::where('cooperative_id', $cooperative->id)
            ->with(['cooperative', 'secteur', 'ticketsPesee']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('numero_livraison', 'like', "%{$search}%");
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        $connaissements = $query->orderBy('created_at', 'desc')->paginate(15);

        // Récupérer les secteurs pour les filtres
        $secteurs = Secteur::orderBy('code')->get();
        $cooperatives = Cooperative::with('secteur')->orderBy('nom')->get();
        $statuts = ['programme' => 'Programmé', 'valide' => 'Validé'];

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return view('cooperative.connaissements.index', compact('connaissements', 'cooperative', 'secteurs', 'cooperatives', 'statuts', 'navigation'));
    }

    /**
     * Afficher les détails d'un connaissement (lecture seule)
     */
    public function show(Connaissement $connaissement)
    {
        $user = Auth::user();
        $cooperative = Cooperative::find($user->cooperative_id);

        // Vérifier que le connaissement appartient à la coopérative du responsable
        if ($connaissement->cooperative_id !== $cooperative->id) {
            return redirect()->route('cooperative.connaissements.index')->with('error', 'Accès refusé.');
        }

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return view('cooperative.connaissements.show', compact('connaissement', 'cooperative', 'navigation'));
    }
}
