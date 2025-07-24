<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Models\Secteur;
use App\Models\ProducteurDocument;

class ProducteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $producteurs = Producteur::with('secteur', 'cooperatives')->orderBy('nom')->get();
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        return view('admin.producteurs.index', compact('producteurs', 'navigation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $secteurs = Secteur::orderBy('code')->get();
        $cooperatives = Cooperative::orderBy('code')->get();
        $documentTypes = [
            'fiche_enquete' => 'Fiche d\'enquête',
            'lettre_engagement' => 'Lettre d\'engagement',
            'self_declaration' => 'Self Declaration',
        ];
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        return view('admin.producteurs.create', compact('secteurs', 'cooperatives', 'documentTypes', 'navigation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'code_fphci' => 'required|string|max:255|unique:producteurs,code_fphci',
            'secteur_id' => 'required|exists:secteurs,id',
            'genre' => 'required|string',
            'contact' => 'required|string|max:20',
            'superficie_totale' => 'nullable|numeric',
            'cooperatives' => 'required|array|max:5',
            'cooperatives.*' => 'exists:cooperatives,id',
        ]);
        $producteur = Producteur::create($request->only(['nom','prenom','code_fphci','secteur_id','genre','contact','superficie_totale']));
        $producteur->cooperatives()->sync($request->cooperatives);
        // Gestion upload fichiers documents (optionnel)
        $documentTypes = ['fiche_enquete','lettre_engagement','self_declaration'];
        foreach ($documentTypes as $type) {
            if ($request->hasFile($type.'_fichier')) {
                $file = $request->file($type.'_fichier');
                $filename = $type.'_'.time().'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('producteurs/'.$producteur->code_fphci, $filename, 'public');
                ProducteurDocument::create([
                    'producteur_id' => $producteur->id,
                    'type' => $type,
                    'fichier' => $path,
                ]);
            }
        }
        return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Producteur créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producteur = Producteur::with(['secteur','cooperatives','documents'])->findOrFail($id);
        $documentTypes = [
            'fiche_enquete' => 'Fiche d\'enquête',
            'lettre_engagement' => 'Lettre d\'engagement',
            'self_declaration' => 'Self Declaration',
        ];
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        return view('admin.producteurs.show', compact('producteur','documentTypes','navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producteur = Producteur::with('cooperatives')->findOrFail($id);
        $secteurs = Secteur::orderBy('code')->get();
        $cooperatives = Cooperative::orderBy('code')->get();
        $documentTypes = [
            'fiche_enquete' => 'Fiche d\'enquête',
            'lettre_engagement' => 'Lettre d\'engagement',
            'self_declaration' => 'Self Declaration',
        ];
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        return view('admin.producteurs.edit', compact('producteur','secteurs','cooperatives','documentTypes','navigation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producteur = Producteur::findOrFail($id);
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'code_fphci' => 'required|string|max:255|unique:producteurs,code_fphci,'.$producteur->id,
            'secteur_id' => 'required|exists:secteurs,id',
            'genre' => 'required|string',
            'contact' => 'required|string|max:20',
            'superficie_totale' => 'nullable|numeric',
            'cooperatives' => 'required|array|max:5',
            'cooperatives.*' => 'exists:cooperatives,id',
        ]);
        $producteur->update($request->only(['nom','prenom','code_fphci','secteur_id','genre','contact','superficie_totale']));
        $producteur->cooperatives()->sync($request->cooperatives);
        // Gestion upload fichiers documents (optionnel)
        $documentTypes = ['fiche_enquete','lettre_engagement','self_declaration'];
        foreach ($documentTypes as $type) {
            if ($request->hasFile($type.'_fichier')) {
                $file = $request->file($type.'_fichier');
                $filename = $type.'_'.time().'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('producteurs/'.$producteur->code_fphci, $filename, 'public');
                $doc = $producteur->documents()->where('type', $type)->first();
                if ($doc) {
                    \Storage::delete($doc->fichier);
                    $doc->update(['fichier' => $path]);
                } else {
                    ProducteurDocument::create([
                        'producteur_id' => $producteur->id,
                        'type' => $type,
                        'fichier' => $path,
                    ]);
                }
            }
        }
        return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Producteur mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producteur = Producteur::findOrFail($id);
        // Supprimer les documents liés (et fichiers)
        foreach ($producteur->documents as $doc) {
            if ($doc->fichier) \Storage::delete($doc->fichier);
            $doc->delete();
        }
        $producteur->cooperatives()->detach();
        $producteur->delete();
        return redirect()->route('admin.producteurs.index')->with('success', 'Producteur supprimé avec succès !');
    }
}
