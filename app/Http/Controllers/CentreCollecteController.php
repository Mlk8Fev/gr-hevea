<?php

namespace App\Http\Controllers;

use App\Models\CentreCollecte;
use App\Services\NavigationService;
use Illuminate\Http\Request;

class CentreCollecteController extends Controller
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
        $centres = CentreCollecte::orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.centres-collecte.index', compact('centres', 'navigation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.centres-collecte.create', compact('navigation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:centres_collecte,code',
            'nom' => 'required|unique:centres_collecte,nom',
            'adresse' => 'required',
            'statut' => 'required|in:actif,inactif'
        ]);

        CentreCollecte::create($request->all());

        return redirect()->route('admin.centres-collecte.index')
            ->with('success', 'Centre de collecte créé avec succès !');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CentreCollecte $centres_collecte)
    {
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.centres-collecte.edit', compact('centres_collecte', 'navigation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CentreCollecte $centres_collecte)
    {
        $request->validate([
            'code' => 'required|unique:centres_collecte,code,' . $centres_collecte->id,
            'nom' => 'required|unique:centres_collecte,nom,' . $centres_collecte->id,
            'adresse' => 'required',
            'statut' => 'required|in:actif,inactif'
        ]);

        $centres_collecte->update($request->all());

        return redirect()->route('admin.centres-collecte.index')
            ->with('success', 'Centre de collecte modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentreCollecte $centres_collecte)
    {
        // Vérifier s'il y a des connaissements liés
        if ($centreCollecte->connaissements()->count() > 0) {
            return redirect()->route('admin.centres-collecte.index')
                ->with('error', 'Impossible de supprimer ce centre de collecte car il est lié à des connaissements.');
        }

        $centreCollecte->delete();

        return redirect()->route('admin.centres-collecte.index')
            ->with('success', 'Centre de collecte supprimé avec succès !');
    }
}
