<?php

namespace App\Http\Controllers;

use App\Models\FarmerList;
use App\Models\Connaissement;
use App\Models\Producteur;
use App\Services\NavigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmerListController extends Controller
{
    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    /**
     * Afficher la liste des livraisons avec état de la farmer list
     */
    public function index(Request $request)
    {
        $query = Connaissement::with(['cooperative', 'centreCollecte', 'ticketsPesee'])
            ->whereHas('ticketsPesee', function($q) {
                $q->where('statut', 'valide');
            });

        // Filtrage par coopérative
        if ($request->filled('cooperative_id')) {
            $query->where('cooperative_id', $request->cooperative_id);
        }

        // Filtrage par centre de collecte
        if ($request->filled('centre_collecte_id')) {
            $query->where('centre_collecte_id', $request->centre_collecte_id);
        }

        // Recherche par numéro de livraison
        if ($request->filled('search')) {
            $query->where('numero_livraison', 'like', '%' . $request->search . '%');
        }

        $livraisons = $query->orderBy('created_at', 'desc')->paginate(15);

        // Ajouter les informations de farmer list pour chaque livraison
        foreach ($livraisons as $livraison) {
            $farmerLists = FarmerList::where('connaissement_id', $livraison->id)->get();
            $livraison->farmer_lists = $farmerLists;
            $livraison->poids_total_farmer_list = $farmerLists->sum('quantite_livree');
            $livraison->poids_net = $livraison->ticketsPesee->first()->poids_net ?? 0;
            $livraison->farmer_list_complete = abs($livraison->poids_total_farmer_list - $livraison->poids_net) < 0.01;
        }

        $cooperatives = \App\Models\Cooperative::orderBy('nom')->get();
        $centresCollecte = \App\Models\CentreCollecte::orderBy('nom')->get();
        $navigation = $this->navigationService->getNavigation();

        return view('admin.farmer-lists.index', compact(
            'livraisons', 
            'cooperatives', 
            'centresCollecte', 
            'navigation'
        ));
    }

    /**
     * Afficher la farmer list d'une livraison
     */
    public function show(Connaissement $connaissement)
    {
        $farmerLists = FarmerList::where('connaissement_id', $connaissement->id)
            ->with(['producteur.secteur']) // Charger la relation producteur et son secteur
            ->orderBy('created_at', 'desc')
            ->get();

        $poidsTotal = FarmerList::getPoidsTotal($connaissement->id);
        $poidsNet = $connaissement->ticketsPesee->first()->poids_net ?? 0;
        $poidsRestant = $poidsNet - $poidsTotal;

        // Calculer les sacs restants
        $sacsTotal = FarmerList::getSacsTotal($connaissement->id);
        $sacsNet = $connaissement->ticketsPesee->first()->nombre_sacs_bidons_cartons ?? 0;
        $sacsRestant = $sacsNet - $sacsTotal;

        // Calculer si la farmer list est complète
        $isComplete = $poidsRestant <= 0;
        
        $navigation = $this->navigationService->getNavigation();

        return view('admin.farmer-lists.show', compact(
            'connaissement', 
            'farmerLists', 
            'poidsTotal', 
            'poidsNet', 
            'poidsRestant',
            'sacsTotal',
            'sacsNet', 
            'sacsRestant',
            'isComplete',
            'navigation'
        ));
    }

    /**
     * Afficher le formulaire d'ajout de producteur
     */
    public function create(Connaissement $connaissement)
    {
        $farmerLists = FarmerList::where('connaissement_id', $connaissement->id)
            ->with(['producteur.secteur'])
            ->orderBy('created_at', 'desc')
            ->get();

        $poidsTotal = FarmerList::getPoidsTotal($connaissement->id);
        $poidsNet = $connaissement->ticketsPesee->first()->poids_net ?? 0;
        $poidsRestant = $poidsNet - $poidsTotal;

        // Calculer les sacs restants
        $sacsTotal = FarmerList::getSacsTotal($connaissement->id);
        $sacsNet = $connaissement->ticketsPesee->first()->nombre_sacs_bidons_cartons ?? 0;
        $sacsRestant = $sacsNet - $sacsTotal;

        // Récupérer la date de livraison (priorité : date_validation du ticket, sinon date_entree)
        $dateLivraison = null;
        if ($connaissement->ticketsPesee->isNotEmpty()) {
            $ticket = $connaissement->ticketsPesee->first();
            $dateLivraison = $ticket->date_validation ? 
                \Carbon\Carbon::parse($ticket->date_validation)->format('Y-m-d') : 
                $ticket->date_entree->format('Y-m-d');
        } else {
            $dateLivraison = $connaissement->date_entree->format('Y-m-d');
        }

        // Récupérer les producteurs de la coopérative
        $producteurs = Producteur::whereHas('cooperatives', function($query) use ($connaissement) {
            $query->where('cooperatives.id', $connaissement->cooperative_id);
        })->orderBy('nom')->get();

        $navigation = $this->navigationService->getNavigation();

        return view('admin.farmer-lists.create', compact(
            'connaissement', 
            'farmerLists', 
            'poidsTotal', 
            'poidsNet', 
            'poidsRestant',
            'sacsTotal',
            'sacsNet', 
            'sacsRestant',
            'dateLivraison', 
            'producteurs', 
            'navigation'
        ));
    }

    /**
     * Ajouter un producteur à la farmer list
     */
    public function store(Request $request, Connaissement $connaissement)
    {
        // Calculer le poids restant AVANT la validation
        $poidsTotal = FarmerList::getPoidsTotal($connaissement->id);
        $poidsNet = $connaissement->ticketsPesee->first()->poids_net ?? 0;
        $poidsRestant = $poidsNet - $poidsTotal;

        $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'quantite_livree' => 'required|numeric|min:0.01|max:' . $poidsRestant,
            'nombre_sacs' => 'required|integer|min:1', // Ajouter cette validation
            'date_livraison' => 'required|date',
            'geolocalisation_precise' => 'required|in:oui,non' // Revenir en minuscules
        ]);

        // Récupérer le producteur avec ses informations
        $producteur = Producteur::findOrFail($request->producteur_id);
        
        // Vérifier que le producteur appartient à la même coopérative
        if (!$producteur->cooperatives()->where('cooperatives.id', $connaissement->cooperative_id)->exists()) {
            return redirect()->back()
                ->with('error', 'Ce producteur n\'appartient pas à la coopérative de cette livraison.');
        }

        // Vérifier que la quantité ne dépasse pas le poids restant
        $poidsTotal = FarmerList::getPoidsTotal($connaissement->id);
        $poidsNet = $connaissement->ticketsPesee->first()->poids_net ?? 0;
        $poidsRestant = $poidsNet - $poidsTotal;

        if ($request->quantite_livree > $poidsRestant) {
            return redirect()->back()
                ->with('error', "La quantité ne peut pas dépasser {$poidsRestant} kg restants.");
        }

        // Convertir geolocalisation_precise en boolean
        $geolocalisationPrecise = $request->geolocalisation_precise === 'oui' ? 1 : 0;

        FarmerList::create([
            'numero_livraison' => $connaissement->numero_livraison,
            'connaissement_id' => $connaissement->id,
            'producteur_id' => $request->producteur_id,
            'quantite_livree' => $request->quantite_livree,
            'nombre_sacs' => $request->nombre_sacs, // Ajouter ce champ
            'date_livraison' => $request->date_livraison,
            'geolocalisation_precise' => $geolocalisationPrecise, // Convertir en boolean
            'contact_producteur' => $producteur->contact, // Récupérer le contact depuis la table producteurs
            'code_producteur' => $producteur->code_fphci, // Récupérer le code producteur depuis la table producteurs
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.farmer-lists.show', $connaissement)
            ->with('success', 'Producteur ajouté à la farmer list avec succès !');
    }

    /**
     * Supprimer un producteur de la farmer list
     */
    public function destroy(FarmerList $farmerList)
    {
        $connaissement = $farmerList->connaissement;
        $farmerList->delete();

        return redirect()->route('admin.farmer-lists.show', $connaissement)
            ->with('success', 'Producteur retiré de la farmer list avec succès !');
    }

    /**
     * Générer le PDF de la farmer list
     */
    public function pdf(Connaissement $connaissement)
    {
        $farmerLists = FarmerList::where('connaissement_id', $connaissement->id)
            ->with(['producteur.secteur'])
            ->orderBy('created_at', 'desc')
            ->get();

        $poidsTotal = FarmerList::getPoidsTotal($connaissement->id);
        $poidsNet = $connaissement->ticketsPesee->first()->poids_net ?? 0;
        $poidsRestant = $poidsNet - $poidsTotal;

        // Générer le nom du fichier PDF
        $filename = 'FarmerList_' . $connaissement->numero_livraison . '_' . now()->format('Y-m-d') . '.pdf';

        // Utiliser DomPDF pour générer le PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.farmer-lists.pdf', compact(
            'connaissement', 
            'farmerLists', 
            'poidsTotal', 
            'poidsNet', 
            'poidsRestant'
        ));

        // Configurer le PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
        ]);

        // Forcer le téléchargement
        return $pdf->download($filename);
    }

    /**
     * Afficher la farmer list en HTML (pour impression)
     */
    public function view(Connaissement $connaissement)
    {
        $farmerLists = FarmerList::where('connaissement_id', $connaissement->id)
            ->with(['producteur.secteur'])
            ->orderBy('created_at', 'desc')
            ->get();

        $poidsTotal = FarmerList::getPoidsTotal($connaissement->id);
        $poidsNet = $connaissement->ticketsPesee->first()->poids_net ?? 0;
        $poidsRestant = $poidsNet - $poidsTotal;

        return view('admin.farmer-lists.view', compact(
            'connaissement', 
            'farmerLists', 
            'poidsTotal', 
            'poidsNet', 
            'poidsRestant'
        ));
    }

    /**
     * Afficher le formulaire de modification d'un producteur
     */
    public function edit(FarmerList $farmerList)
    {
        $connaissement = $farmerList->connaissement;
        
        $farmerLists = FarmerList::where('connaissement_id', $connaissement->id)
            ->with(['producteur.secteur'])
            ->orderBy('created_at', 'desc')
            ->get();

        $poidsTotal = FarmerList::getPoidsTotal($connaissement->id);
        $poidsNet = $connaissement->ticketsPesee->first()->poids_net ?? 0;
        $poidsRestant = $poidsNet - $poidsTotal;

        // Calculer les sacs restants
        $sacsTotal = FarmerList::getSacsTotal($connaissement->id);
        $sacsNet = $connaissement->ticketsPesee->first()->nombre_sacs_bidons_cartons ?? 0;
        $sacsRestant = $sacsNet - $sacsTotal;

        // Récupérer la date de livraison
        $dateLivraison = null;
        if ($connaissement->ticketsPesee->isNotEmpty()) {
            $ticket = $connaissement->ticketsPesee->first();
            $dateLivraison = $ticket->date_validation ? 
                \Carbon\Carbon::parse($ticket->date_validation)->format('Y-m-d') : 
                $ticket->date_entree->format('Y-m-d');
        } else {
            $dateLivraison = $connaissement->date_entree->format('Y-m-d');
        }

        // Récupérer les producteurs de la coopérative
        $producteurs = Producteur::whereHas('cooperatives', function($query) use ($connaissement) {
            $query->where('cooperatives.id', $connaissement->cooperative_id);
        })->orderBy('nom')->get();

        $navigation = $this->navigationService->getNavigation();

        return view('admin.farmer-lists.edit', compact(
            'farmerList',
            'connaissement', 
            'farmerLists', 
            'poidsTotal', 
            'poidsNet', 
            'poidsRestant',
            'sacsTotal',
            'sacsNet', 
            'sacsRestant',
            'dateLivraison', 
            'producteurs', 
            'navigation'
        ));
    }

    /**
     * Mettre à jour un producteur de la farmer list
     */
    public function update(Request $request, FarmerList $farmerList)
    {
        $connaissement = $farmerList->connaissement;
        
        // Calculer le poids restant (en excluant le farmer list actuel)
        $poidsTotal = FarmerList::where('connaissement_id', $connaissement->id)
            ->where('id', '!=', $farmerList->id)
            ->sum('quantite_livree');
        $poidsNet = $connaissement->ticketsPesee->first()->poids_net ?? 0;
        $poidsRestant = $poidsNet - $poidsTotal;

        $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'quantite_livree' => 'required|numeric|min:0.01|max:' . ($poidsRestant + $farmerList->quantite_livree),
            'nombre_sacs' => 'required|integer|min:1',
            'date_livraison' => 'required|date',
            'geolocalisation_precise' => 'required|in:oui,non' // Revenir en minuscules
        ]);

        // Récupérer le producteur avec ses informations
        $producteur = Producteur::findOrFail($request->producteur_id);

        // Convertir geolocalisation_precise en boolean
        $geolocalisationPrecise = $request->geolocalisation_precise === 'oui' ? 1 : 0;

        $farmerList->update([
            'producteur_id' => $request->producteur_id,
            'quantite_livree' => $request->quantite_livree,
            'nombre_sacs' => $request->nombre_sacs,
            'date_livraison' => $request->date_livraison,
            'geolocalisation_precise' => $geolocalisationPrecise,
            'contact_producteur' => $producteur->contact,
            'code_producteur' => $producteur->code_fphci,
        ]);

        return redirect()->route('admin.farmer-lists.show', $connaissement)
            ->with('success', 'Producteur modifié avec succès dans la Farmer List.');
    }
}
