<?php

namespace App\Http\Controllers;

use App\Models\Connaissement;
use App\Models\Cooperative;
use App\Models\CentreCollecte;
use App\Models\User;
use App\Services\NavigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConnaissementController extends Controller
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
        $connaissements = Connaissement::with(['cooperative', 'centreCollecte', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.index', compact('connaissements', 'navigation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cooperatives = Cooperative::orderBy('nom')->get();
        $centresCollecte = CentreCollecte::where('statut', 'actif')->orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.create', compact('cooperatives', 'centresCollecte', 'navigation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cooperative_id' => 'required|exists:cooperatives,id',
            'centre_collecte_id' => 'required|exists:centres_collecte,id',
            'lieu_depart' => 'required|string|max:255',
            'sous_prefecture' => 'required|string|max:255',
            'transporteur_nom' => 'required|string|max:255',
            'transporteur_immatriculation' => 'required|string|max:50',
            'chauffeur_nom' => 'required|string|max:255',
            'destinataire_type' => 'required|in:entrepot,cooperative,acheteur',
            'destinataire_id' => 'nullable|integer',
            'nombre_sacs' => 'required|integer|min:1',
            'poids_brut_estime' => 'required|numeric|min:0.01',
            'signature_cooperative' => 'nullable|string'
        ]);

        // Générer le numéro de connaissement
        $numero = 'CONN-' . date('Y') . '-' . str_pad(Connaissement::count() + 1, 4, '0', STR_PAD_LEFT);

        $connaissement = Connaissement::create([
            'numero' => $numero,
            'statut' => 'programme',
            'cooperative_id' => $request->cooperative_id,
            'centre_collecte_id' => $request->centre_collecte_id,
            'lieu_depart' => $request->lieu_depart,
            'sous_prefecture' => $request->sous_prefecture,
            'transporteur_nom' => $request->transporteur_nom,
            'transporteur_immatriculation' => $request->transporteur_immatriculation,
            'chauffeur_nom' => $request->chauffeur_nom,
            'destinataire_type' => $request->destinataire_type,
            'destinataire_id' => $request->destinataire_id,
            'nombre_sacs' => $request->nombre_sacs,
            'poids_brut_estime' => $request->poids_brut_estime,
            'signature_cooperative' => $request->signature_cooperative,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.connaissements.index')
            ->with('success', 'Connaissement créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Connaissement $connaissement)
    {
        $connaissement->load(['cooperative', 'centreCollecte', 'createdBy', 'validatedBy']);
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.show', compact('connaissement', 'navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Connaissement $connaissement)
    {
        $cooperatives = Cooperative::orderBy('nom')->get();
        $centresCollecte = CentreCollecte::where('statut', 'actif')->orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.edit', compact('connaissement', 'cooperatives', 'centresCollecte', 'navigation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Connaissement $connaissement)
    {
        $request->validate([
            'cooperative_id' => 'required|exists:cooperatives,id',
            'centre_collecte_id' => 'required|exists:centres_collecte,id',
            'lieu_depart' => 'required|string|max:255',
            'sous_prefecture' => 'required|string|max:255',
            'transporteur_nom' => 'required|string|max:255',
            'transporteur_immatriculation' => 'required|string|max:50',
            'chauffeur_nom' => 'required|string|max:255',
            'destinataire_type' => 'required|in:entrepot,cooperative,acheteur',
            'destinataire_id' => 'nullable|integer',
            'nombre_sacs' => 'required|integer|min:1',
            'poids_brut_estime' => 'required|numeric|min:0.01',
            'signature_cooperative' => 'nullable|string'
        ]);

        $connaissement->update($request->all());

        return redirect()->route('admin.connaissements.index')
            ->with('success', 'Connaissement modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Connaissement $connaissement)
    {
        if ($connaissement->statut === 'valide') {
            return redirect()->route('admin.connaissements.index')
                ->with('error', 'Impossible de supprimer un connaissement validé pour ticket de pesée.');
        }

        $connaissement->delete();

        return redirect()->route('admin.connaissements.index')
            ->with('success', 'Connaissement supprimé avec succès !');
    }

    /**
     * Programmer un connaissement
     */
    public function program(Connaissement $connaissement)
    {
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.program', compact('connaissement', 'navigation'));
    }

    /**
     * Sauvegarder la programmation
     */
    public function storeProgram(Request $request, Connaissement $connaissement)
    {
        $request->validate([
            'date_reception' => 'required|date|after_or_equal:today',
            'heure_arrivee' => 'required|date_format:H:i'
        ]);

        $connaissement->update([
            'date_reception' => $request->date_reception,
            'heure_arrivee' => $request->heure_arrivee,
            'programmed_by' => auth()->id(),
            'date_programmation' => now()
        ]);

        return redirect()->route('admin.connaissements.index')
            ->with('success', 'Livraison programmée avec succès !');
    }

    /**
     * Valider un connaissement
     */
    public function validate(Connaissement $connaissement)
    {
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.validate', compact('connaissement', 'navigation'));
    }

    /**
     * Sauvegarder la validation
     */
    public function storeValidation(Request $request, Connaissement $connaissement)
    {
        $request->validate([
            'poids_net_reel' => 'required|numeric|min:0.01',
            'signature_fphci' => 'nullable|string'
        ]);

        if ($connaissement->statut === 'valide') {
            return redirect()->route('admin.connaissements.index')
                ->with('error', 'Ce connaissement est déjà validé pour ticket de pesée.');
        }

        $connaissement->update([
            'statut' => 'valide',
            'poids_net_reel' => $request->poids_net_reel,
            'date_validation_reelle' => now(),
            'validated_by' => auth()->id(),
            'signature_fphci' => $request->signature_fphci
        ]);

        return redirect()->route('admin.connaissements.index')
            ->with('success', 'Connaissement validé pour ticket de pesée avec succès !');
    }
}
