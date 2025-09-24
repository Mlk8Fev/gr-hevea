<?php

namespace App\Http\Controllers;

use App\Models\Connaissement;
use App\Models\Cooperative;
use App\Models\CentreCollecte;
use App\Models\User;
use App\Models\Secteur;
use App\Services\NavigationService;
use App\Services\LivraisonNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConnaissementController extends Controller
{
    protected $navigationService;
    protected $livraisonNumberService;

    public function __construct(NavigationService $navigationService, LivraisonNumberService $livraisonNumberService)
    {
        $this->navigationService = $navigationService;
        $this->livraisonNumberService = $livraisonNumberService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Construire la requête de base avec les relations nécessaires
        $query = Connaissement::with(['cooperative.secteur', 'centreCollecte', 'createdBy', 'secteur']);
        
        // Scoping par secteur pour AGC
        if (auth()->check() && auth()->user()->role === 'agc' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            $query->whereHas('secteur', function($q) use ($userSecteurCode) {
                $q->where('code', $userSecteurCode);
            });
        }
        
        // Filtre par secteur
        if ($request->filled('secteur')) {
            $query->where('secteur_id', $request->secteur);
        }
        
        // Filtre par coopérative
        if ($request->filled('cooperative')) {
            $query->where('cooperative_id', $request->cooperative);
        }
        
        // Filtre par statut
        if ($request->filled('statut') && $request->statut !== 'all') {
            $query->where('statut', $request->statut);
        }
        
        // Filtre par date d'arrivée
        if ($request->filled('date_arrivee')) {
            $query->whereDate('date_reception', $request->date_arrivee);
        }
        
        // Filtre par heure d'arrivée
        if ($request->filled('heure_arrivee')) {
            $query->whereTime('heure_arrivee', $request->heure_arrivee);
        }
        
        // Recherche par numéro de livraison et autres champs
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('numero_livraison', 'LIKE', "%{$request->search}%")
                  ->orWhere('lieu_depart', 'LIKE', "%{$request->search}%")
                  ->orWhere('transporteur_nom', 'LIKE', "%{$request->search}%")
                  ->orWhere('transporteur_immatriculation', 'LIKE', "%{$request->search}%")
                  ->orWhere('chauffeur_nom', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('cooperative', function($q2) use ($request) {
                      $q2->where('nom', 'LIKE', "%{$request->search}%")
                         ->orWhere('code', 'LIKE', "%{$request->search}%");
                  })
                  ->orWhereHas('secteur', function($q2) use ($request) {
                      $q2->where('nom', 'LIKE', "%{$request->search}%")
                         ->orWhere('code', 'LIKE', "%{$request->search}%");
                  });
            });
        }
        
        // Paginer les résultats
        $connaissements = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10));
        
        // Récupérer les données pour les filtres
        $secteurs = Secteur::orderBy('code')->get();
        $cooperatives = Cooperative::with('secteur')->orderBy('nom')->get();
        $statuts = ['programme' => 'Programmé', 'valide' => 'Validé'];
        
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.index', compact('connaissements', 'secteurs', 'cooperatives', 'statuts', 'navigation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cooperatives = Cooperative::orderBy('nom')->get();
        $centresCollecte = CentreCollecte::orderBy('nom')->get();
        $secteurs = Secteur::orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.create', compact('cooperatives', 'centresCollecte', 'secteurs', 'navigation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'secteur_id' => 'required|exists:secteurs,id',
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

        // Récupérer le secteur pour obtenir son code
        $secteur = Secteur::findOrFail($request->secteur_id);
        
        // Générer le numéro de livraison automatiquement
        $numeroLivraison = $this->livraisonNumberService->generateNumber($secteur->code);

        $connaissement = Connaissement::create([
            'numero_livraison' => $numeroLivraison,
            'secteur_id' => $request->secteur_id,
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
            ->with('success', "Connaissement créé avec succès ! Numéro de livraison : {$numeroLivraison}");
    }

    /**
     * Display the specified resource.
     */
    public function show(Connaissement $connaissement)
    {
        $connaissement->load(['cooperative', 'centreCollecte', 'createdBy', 'validatedBy', 'secteur']);
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.show', compact('connaissement', 'navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Connaissement $connaissement)
    {
        $cooperatives = Cooperative::orderBy('nom')->get();
        $centresCollecte = CentreCollecte::orderBy('nom')->get();
        $secteurs = Secteur::orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.edit', compact('connaissement', 'cooperatives', 'centresCollecte', 'secteurs', 'navigation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Connaissement $connaissement)
    {
        $request->validate([
            'secteur_id' => 'required|exists:secteurs,id',
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

        $connaissement->update([
            'secteur_id' => $request->secteur_id,
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
        ]);

        return redirect()->route('admin.connaissements.index')
            ->with('success', 'Connaissement mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Connaissement $connaissement)
    {
        $connaissement->delete();
        
        return redirect()->route('admin.connaissements.index')
            ->with('success', 'Connaissement supprimé avec succès !');
    }

    /**
     * Show the form for programming a connaissement.
     */
    public function program(Connaissement $connaissement)
    {
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.program', compact('connaissement', 'navigation'));
    }

    /**
     * Store the programming of a connaissement.
     */
    public function storeProgram(Request $request, Connaissement $connaissement)
    {
        $request->validate([
            'date_reception' => 'required|date',
            'heure_arrivee' => 'required|date_format:H:i',
        ]);

        $connaissement->update([
            'date_reception' => $request->date_reception,
            'heure_arrivee' => $request->heure_arrivee,
            'programmed_by' => auth()->id(),
            'date_programmation' => now(),
        ]);

        return redirect()->route('admin.connaissements.show', $connaissement)
            ->with('success', 'Connaissement programmé avec succès !');
    }

    /**
     * Show the form for validating a connaissement.
     */
    public function validate(Connaissement $connaissement)
    {
        $navigation = $this->navigationService->getNavigation();
        
        return view('admin.connaissements.validate', compact('connaissement', 'navigation'));
    }

    /**
     * Store the validation of a connaissement.
     */
    public function storeValidation(Request $request, Connaissement $connaissement)
    {
        $request->validate([
            'poids_net_reel' => 'required|numeric|min:0.01',
        ]);

        $connaissement->update([
            'statut' => 'valide',
            'poids_net_reel' => $request->poids_net_reel,
            'date_validation_reelle' => now(),
            'validated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.connaissements.show', $connaissement)
            ->with('success', 'Connaissement validé avec succès !');
    }

    /**
     * Récupérer les données d'un connaissement pour AJAX
     */
    public function getData($id)
    {
        $connaissement = Connaissement::with('centreCollecte')->findOrFail($id);
        
        return response()->json([
            'lieu_depart' => $connaissement->lieu_depart,
            'centre_collecte_nom' => $connaissement->centreCollecte->nom ?? '',
            'transporteur_immatriculation' => $connaissement->transporteur_immatriculation
        ]);
    }
}
