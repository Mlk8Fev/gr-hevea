<?php

namespace App\Http\Controllers;

use App\Models\RecuAchat;
use App\Models\FarmerList;
use App\Models\Connaissement;
use App\Services\CalculPrixService;
use App\Services\NavigationService;
use Illuminate\Http\Request;

class RecuAchatController extends Controller
{
    protected $calculPrixService;
    protected $navigationService;

    public function __construct(CalculPrixService $calculPrixService, NavigationService $navigationService)
    {
        $this->calculPrixService = $calculPrixService;
        $this->navigationService = $navigationService;
    }

    /**
     * Afficher le formulaire de création de reçu d'achat
     */
    public function create(Connaissement $connaissement, FarmerList $farmerList)
    {
        // Prix fixe : 72 FCFA par kg
        $prixUnitaire = 72;

        // Calculer le montant à payer
        $montantAPayer = $farmerList->quantite_livree * $prixUnitaire;

        $navigation = $this->navigationService->getNavigation();

        return view('admin.recus-achat.create', compact(
            'connaissement',
            'farmerList',
            'prixUnitaire',
            'montantAPayer',
            'navigation'
        ));
    }

    /**
     * Enregistrer le reçu d'achat
     */
    public function store(Request $request, Connaissement $connaissement, FarmerList $farmerList)
    {
        $request->validate([
            'signature_acheteur' => 'nullable|string',
            'signature_producteur' => 'nullable|string'
        ]);

        // Prix fixe : 72 FCFA par kg
        $prixUnitaire = 72;

        // Calculer le montant à payer
        $montantAPayer = $farmerList->quantite_livree * $prixUnitaire;

        // Générer le numéro de reçu (séquentiel)
        $numeroRecu = RecuAchat::max('id') + 1;

        // Récupérer le producteur avec ses relations
        $producteur = $farmerList->producteur;

        RecuAchat::create([
            'numero_recu' => $numeroRecu,
            'connaissement_id' => $connaissement->id,
            'producteur_id' => $farmerList->producteur_id,
            'farmer_list_id' => $farmerList->id,
            'nom_producteur' => $producteur->nom,
            'prenom_producteur' => $producteur->prenom,
            'telephone_producteur' => $producteur->contact,
            'code_fphci' => $producteur->code_fphci, // Récupérer depuis la table producteurs
            'secteur_fphci' => $producteur->secteur->nom,
            'centre_collecte' => $connaissement->centreCollecte->nom,
            'quantite_livree' => $farmerList->quantite_livree,
            'prix_unitaire' => $prixUnitaire,
            'montant_total' => $montantAPayer,
            'signature_acheteur' => $request->signature_acheteur,
            'signature_producteur' => $request->signature_producteur,
            'date_creation' => now(),
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.farmer-lists.show', $connaissement)
            ->with('success', 'Reçu d\'achat créé avec succès.');
    }

    /**
     * Afficher le reçu d'achat
     */
    public function show(RecuAchat $recuAchat)
    {
        $navigation = $this->navigationService->getNavigation();

        return view('admin.recus-achat.show', compact('recuAchat', 'navigation'));
    }

    /**
     * Afficher le formulaire d'édition des signatures
     */
    public function edit(RecuAchat $recuAchat)
    {
        $navigation = $this->navigationService->getNavigation();

        return view('admin.recus-achat.edit', compact('recuAchat', 'navigation'));
    }

    /**
     * Mettre à jour les signatures du reçu d'achat
     */
    public function update(Request $request, RecuAchat $recuAchat)
    {
        $request->validate([
            'signature_acheteur' => 'nullable|string',
            'signature_producteur' => 'nullable|string'
        ]);

        $recuAchat->update([
            'signature_acheteur' => $request->signature_acheteur,
            'signature_producteur' => $request->signature_producteur,
        ]);

        return redirect()->route('admin.recus-achat.show', $recuAchat)
            ->with('success', 'Signatures mises à jour avec succès.');
    }

    /**
     * Générer le PDF du reçu d'achat
     */
    public function pdf(RecuAchat $recuAchat)
    {
        $pdf = \PDF::loadView('admin.recus-achat.pdf', compact('recuAchat'));
        return $pdf->download('recu_achat_' . $recuAchat->numero_recu . '.pdf');
    }
}
