<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Models\Secteur;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationService;

class CooperativeProducteurController extends Controller
{
    /**
     * Afficher les producteurs de la coopérative du responsable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer la coopérative du responsable
        $cooperative = Cooperative::find($user->cooperative_id);
        
        if (!$cooperative) {
            return redirect()->route('dashboard')->with('error', 'Aucune coopérative assignée à votre compte.');
        }

        // Récupérer les producteurs de cette coopérative uniquement
        $query = Producteur::whereHas('cooperatives', function($q) use ($cooperative) {
            $q->where('cooperatives.id', $cooperative->id);
        })->with(['cooperatives', 'secteur']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('code_producteur', 'like', "%{$search}%");
            });
        }

        $producteurs = $query->orderBy('nom')->paginate(15);

        // Récupérer les secteurs pour les filtres
        $secteurs = Secteur::orderBy('code')->get();
        $cooperatives = Cooperative::orderBy('code')->get();
        $statuts = ['en_attente' => 'En attente', 'valide' => 'Validé', 'annule' => 'Annulé'];

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return view('cooperative.producteurs.index', compact('producteurs', 'cooperative', 'secteurs', 'cooperatives', 'statuts', 'navigation'));
    }

    /**
     * Afficher les détails d'un producteur (lecture seule)
     */
    public function show(Producteur $producteur)
    {
        $user = Auth::user();
        $cooperative = Cooperative::find($user->cooperative_id);

        // Vérifier que le producteur appartient à la coopérative du responsable
        if (!$producteur->cooperatives->contains($cooperative->id)) {
            return redirect()->route('cooperative.producteurs.index')->with('error', 'Accès refusé.');
        }

        // Navigation pour les responsables de coopératives
        $navigationService = new NavigationService();
        $navigation = $navigationService->getNavigation($user);

        return view('cooperative.producteurs.show', compact('producteur', 'cooperative', 'navigation'));
    }
}
