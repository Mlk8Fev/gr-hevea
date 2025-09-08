<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secteur;
use App\Services\NavigationService;

class SecteurController extends Controller
{
    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    /**
     * Afficher la liste des secteurs
     */
    public function index(Request $request)
    {
        $query = Secteur::withCount(['cooperatives', 'producteurs']);

        // Recherche par code ou nom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('nom', 'LIKE', "%{$search}%");
            });
        }

        // Pagination avec nombre d'éléments par page configurable
        $perPage = $request->get('per_page', 10);
        $secteurs = $query->orderBy('code')->paginate($perPage);
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.secteurs.index', compact('secteurs', 'navigation'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $navigation = $this->navigationService->getNavigation();
        return view('admin.secteurs.create', compact('navigation'));
    }

    /**
     * Créer un nouveau secteur
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:secteurs,code',
            'nom' => 'required|string|max:255'
        ]);

        Secteur::create([
            'code' => strtoupper($request->code),
            'nom' => $request->nom
        ]);

        return redirect()->route('admin.secteurs.index')->with('success', 'Secteur créé avec succès !');
    }

    /**
     * Afficher un secteur
     */
    public function show(Secteur $secteur)
    {
        $navigation = $this->navigationService->getNavigation();
        return view('admin.secteurs.show', compact('secteur', 'navigation'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Secteur $secteur)
    {
        $navigation = $this->navigationService->getNavigation();
        return view('admin.secteurs.edit', compact('secteur', 'navigation'));
    }

    /**
     * Mettre à jour un secteur
     */
    public function update(Request $request, Secteur $secteur)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:secteurs,code,' . $secteur->id,
            'nom' => 'required|string|max:255'
        ]);

        $secteur->update([
            'code' => strtoupper($request->code),
            'nom' => $request->nom
        ]);

        return redirect()->route('admin.secteurs.index')->with('success', 'Secteur mis à jour avec succès !');
    }

    /**
     * Supprimer un secteur
     */
    public function destroy(Secteur $secteur)
    {
        // Vérifier si des utilisateurs utilisent ce secteur
        if ($secteur->users()->count() > 0) {
            return redirect()->route('admin.secteurs.index')->with('error', 'Impossible de supprimer ce secteur car il est utilisé par des utilisateurs.');
        }

        $secteur->delete();
        return redirect()->route('admin.secteurs.index')->with('success', 'Secteur supprimé avec succès !');
    }
}
