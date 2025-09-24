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
    public function index(Request $request)
    {
        $query = Producteur::with('secteur', 'cooperatives')->orderBy('nom');

        // Filtre par secteur
        if ($request->filled('secteur')) {
            $query->where('secteur_id', $request->secteur);
        }

        // Filtre de recherche simplifié
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'LIKE', "%{$request->search}%")
                  ->orWhere('prenom', 'LIKE', "%{$request->search}%")
                  ->orWhere('code_fphci', 'LIKE', "%{$request->search}%")
                  ->orWhere('agronica_id', 'LIKE', "%{$request->search}%")
                  ->orWhere('localite', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('secteur', function($q2) use ($request) {
                      $q2->where('nom', 'LIKE', "%{$request->search}%")
                         ->orWhere('code', 'LIKE', "%{$request->search}%");
                  })
                  ->orWhereHas('cooperatives', function($q2) use ($request) {
                      $q2->where('nom', 'LIKE', "%{$request->search}%")
                         ->orWhere('code', 'LIKE', "%{$request->search}%");
                  });
            });
        }

        // Filtre AGC par secteur
        if (auth()->check() && auth()->user()->role === 'agc' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            $query->whereHas('secteur', function($q) use ($userSecteurCode) {
                $q->where('code', $userSecteurCode);
            });
        }

        $producteurs = $query->paginate($request->get('per_page', 10));
        $secteurs = Secteur::orderBy('code')->get();
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        
        return view('admin.producteurs.index', compact('producteurs', 'secteurs', 'navigation'));
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
            'agronica_id' => 'nullable|string|max:255',
            'localite' => 'nullable|string|max:255',
            'secteur_id' => 'required|exists:secteurs,id',
            'genre' => 'required|string',
            'contact' => 'required|string|max:20',
            'superficie_totale' => 'nullable|numeric',
            'cooperatives' => 'required|array|max:5',
            'cooperatives.*' => 'exists:cooperatives,id',
            // Validation des parcelles
            'parcelles' => 'nullable|array|max:10',
            'parcelles.*.nom_parcelle' => 'nullable|string|max:255',
            'parcelles.*.latitude' => 'required_with:parcelles|numeric|between:-90,90',
            'parcelles.*.longitude' => 'required_with:parcelles|numeric|between:-180,180',
            'parcelles.*.superficie' => 'required_with:parcelles|numeric|min:0.01|max:999999.99',
        ]);

        // Debug pour voir les données reçues
        \Log::info('=== DÉBUT STORE PRODUCTEUR ===');
        \Log::info('Données complètes reçues:', $request->all());
        \Log::info('Données parcelles reçues:', $request->parcelles ?? []);
        \Log::info('Code FPHCI:', ['code' => $request->code_fphci]);

        // Validation des parcelles
        if ($request->has('parcelles')) {
            foreach ($request->parcelles as $index => $parcelleData) {
                if (!empty($parcelleData['latitude']) && !empty($parcelleData['longitude']) && !empty($parcelleData['superficie'])) {
                    // Validation des coordonnées
                    if ($parcelleData['latitude'] < -90 || $parcelleData['latitude'] > 90) {
                        return back()->withErrors(['parcelles.' . $index . '.latitude' => 'La latitude doit être entre -90 et 90.'])->withInput();
                    }
                    if ($parcelleData['longitude'] < -180 || $parcelleData['longitude'] > 180) {
                        return back()->withErrors(['parcelles.' . $index . '.longitude' => 'La longitude doit être entre -180 et 180.'])->withInput();
                    }
                    if ($parcelleData['superficie'] <= 0) {
                        return back()->withErrors(['parcelles.' . $index . '.superficie' => 'La superficie doit être supérieure à 0.'])->withInput();
                    }
                }
            }
        }

        $producteur = Producteur::create($request->only([
            'nom', 'prenom', 'code_fphci', 'agronica_id', 'localite',
            'secteur_id', 'genre', 'contact', 'superficie_totale'
        ]));
        
        \Log::info('Producteur créé avec ID:', ['id' => $producteur->id]);
        \Log::info('Code FPHCI du producteur créé:', ['code' => $producteur->code_fphci]);
        
        $producteur->cooperatives()->sync($request->cooperatives);

        // Gestion des parcelles
        if ($request->has('parcelles')) {
            \Log::info('Nombre de parcelles à créer:', ['count' => count($request->parcelles)]);
            foreach ($request->parcelles as $index => $parcelleData) {
                \Log::info("Parcelle $index:", $parcelleData);
                if (!empty($parcelleData['latitude']) && !empty($parcelleData['longitude']) && !empty($parcelleData['superficie'])) {
                    $nomParcelle = 'PARC' . ($index + 1) . $producteur->code_fphci;
                    \Log::info("Création parcelle avec nom:", ['nom' => $nomParcelle]);
                    \App\Models\Parcelle::create([
                        'producteur_id' => $producteur->id,
                        'nom_parcelle' => $nomParcelle,
                        'latitude' => $parcelleData['latitude'],
                        'longitude' => $parcelleData['longitude'],
                        'superficie' => $parcelleData['superficie'],
                        'ordre' => $index + 1,
                    ]);
                    \Log::info("Parcelle créée avec succès:", ['nom' => $nomParcelle]);
                } else {
                    \Log::warning("Parcelle ignorée - données manquantes:", ['index' => $index]);
                }
            }
            $producteur->calculateSuperficieTotale();
            \Log::info('Superficie totale calculée:', ['superficie' => $producteur->superficie_totale]);
        } else {
            \Log::info('Aucune parcelle à créer');
        }

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
        
        \Log::info('=== FIN STORE PRODUCTEUR - SUCCÈS ===');
        return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Producteur créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producteur = Producteur::with(['secteur','cooperatives','documents','parcelles'])->findOrFail($id);
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
        $producteur = Producteur::with(['cooperatives','parcelles'])->findOrFail($id);
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

        // Si AGC, restreindre les champs modifiables
        if (auth()->check() && auth()->user()->role === 'agc') {
            // Valider uniquement ce que l'AGC peut modifier
            $request->validate([
                'cooperatives' => 'required|array|max:5',
                'cooperatives.*' => 'exists:cooperatives,id',
                'parcelles' => 'nullable|array|max:10',
                'parcelles.*.latitude' => 'required_with:parcelles|numeric|between:-90,90',
                'parcelles.*.longitude' => 'required_with:parcelles|numeric|between:-180,180',
                'parcelles.*.superficie' => 'required_with:parcelles|numeric|min:0.01|max:999999.99',
            ]);

            // Mettre à jour uniquement les coopératives
            $producteur->cooperatives()->sync($request->cooperatives);

            // Gérer les parcelles (remplacement complet puis recalcul)
            if ($request->has('parcelles')) {
                $producteur->parcelles()->delete();
                foreach ($request->parcelles as $index => $parcelleData) {
                    if (!empty($parcelleData['latitude']) && !empty($parcelleData['longitude']) && !empty($parcelleData['superficie'])) {
                        \App\Models\Parcelle::create([
                            'producteur_id' => $producteur->id,
                            'nom_parcelle' => 'PARC' . ($index + 1) . $producteur->code_fphci,
                            'latitude' => $parcelleData['latitude'],
                            'longitude' => $parcelleData['longitude'],
                            'superficie' => $parcelleData['superficie'],
                            'ordre' => $index + 1,
                        ]);
                    }
                }
                $producteur->calculateSuperficieTotale();
            }

            // Gestion upload fichiers documents
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

            return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Liaisons et parcelles mises à jour avec succès.');
        }

        // Comportement normal pour autres rôles
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'code_fphci' => 'required|string|max:255|unique:producteurs,code_fphci,'.$producteur->id,
            'agronica_id' => 'nullable|string|max:255',
            'localite' => 'nullable|string|max:255',
            'secteur_id' => 'required|exists:secteurs,id',
            'genre' => 'required|string',
            'contact' => 'required|string|max:20',
            'superficie_totale' => 'nullable|numeric',
            'cooperatives' => 'required|array|max:5',
            'cooperatives.*' => 'exists:cooperatives,id',
            'parcelles' => 'nullable|array|max:10',
            'parcelles.*.id' => 'nullable|exists:parcelles,id',
            'parcelles.*.nom_parcelle' => 'nullable|string|max:255',
            'parcelles.*.latitude' => 'required_with:parcelles|numeric|between:-90,90',
            'parcelles.*.longitude' => 'required_with:parcelles|numeric|between:-180,180',
            'parcelles.*.superficie' => 'required_with:parcelles|numeric|min:0.01|max:999999.99',
        ]);

        $producteur->update($request->only([
            'nom', 'prenom', 'code_fphci', 'agronica_id', 'localite', 
            'secteur_id', 'genre', 'contact', 'superficie_totale'
        ]));
        
        $producteur->cooperatives()->sync($request->cooperatives);

        if ($request->has('parcelles')) {
            $producteur->parcelles()->delete();
            foreach ($request->parcelles as $index => $parcelleData) {
                if (!empty($parcelleData['latitude']) && !empty($parcelleData['longitude']) && !empty($parcelleData['superficie'])) {
                    \App\Models\Parcelle::create([
                        'producteur_id' => $producteur->id,
                        'nom_parcelle' => 'PARC' . ($index + 1) . $producteur->code_fphci,
                        'latitude' => $parcelleData['latitude'],
                        'longitude' => $parcelleData['longitude'],
                        'superficie' => $parcelleData['superficie'],
                        'ordre' => $index + 1,
                    ]);
                }
            }
            $producteur->calculateSuperficieTotale();
        }

        // Gestion upload fichiers documents (identique au dessus)
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
