<?php

namespace App\Http\Controllers;

use App\Models\Cooperative;
use App\Models\CooperativeDocument;
use App\Models\CooperativeDistance;
use App\Models\CentreCollecte;
use App\Models\Secteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CooperativeController extends Controller
{
    // Liste des types de documents attendus
    private $documentTypes = [
        'dfe' => 'DFE',
        'registre_commerce' => 'Registre de commerce',
        'statuts' => 'Statuts et règlements intérieurs',
        'delegation_pouvoir' => 'Délégation de pouvoir',
        'journal_officiel' => 'Journal officiel',
        'contrat_bail' => 'Contrat de bail',
        'protocole_fph_ci' => 'Protocole d\'accord avec la FPH-CI',
        'fiche_enquete' => 'Fiche d\'enquête',
        'fiche_etalonnage' => 'Fiche d\'étalonnage',
        'liste_formation' => 'Liste de présence de formation',
    ];

    public function index(Request $request)
    {
        $query = Cooperative::with('secteur')->orderBy('code');
        
        // Pour les AGC et CS, filtrer uniquement les coopératives de leur secteur
        if (auth()->check() && in_array(auth()->user()->role, ['agc', 'cs']) && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            $query->whereHas('secteur', function($q) use ($userSecteurCode) {
                $q->where('code', $userSecteurCode);
            });
        }
        
        // Filtre par secteur (pour les autres rôles)
        if ($request->filled('secteur')) {
            $query->where('secteur_id', $request->secteur);
        }
        
        // Filtre par nom (recherche dynamique)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('sigle', 'LIKE', "%{$search}%");
            });
        }
        
        $cooperatives = $query->paginate($request->get('per_page', 25));
        $secteurs = Secteur::orderBy('code')->get();
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        
        return view('admin.cooperatives.index', compact('cooperatives', 'secteurs', 'navigation'));
    }

    public function create()
    {
        $secteurs = Secteur::orderBy('code')->get();
        $documentTypes = $this->documentTypes;
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        return view('admin.cooperatives.create', compact('secteurs', 'documentTypes', 'navigation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:cooperatives,code',
            'nom' => 'required|string|max:255',
            'secteur_id' => 'required|exists:secteurs,id',
            'president' => 'required|string|max:255',
            'contact' => 'required|digits:10',
            'sigle' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'compte_bancaire' => 'required|digits:12',
            'cle_rib' => 'required|string|size:2|regex:/^[0-9]{2}$/',
            'code_banque' => 'required|string|size:5|regex:/^[A-Z0-9]{5}$/',
            'code_guichet' => 'required|digits:5',
            'nom_cooperative_banque' => 'required|string|max:255',
            'distances.cotraf' => 'required|numeric|min:0',
            'distances.duekoue' => 'required|numeric|min:0',
            'distances.guiglo' => 'required|numeric|min:0',
            'distances.divo' => 'required|numeric|min:0',
            'distances.abengourou' => 'required|numeric|min:0',
            'distances.meagui' => 'required|numeric|min:0',
            'distances.cotraf_korhogo' => 'required|numeric|min:0',
        ]);

        $cooperative = Cooperative::create([
            'code' => $request->code,
            'nom' => $request->nom,
            'secteur_id' => $request->secteur_id,
            'president' => $request->president,
            'contact' => $request->contact,
            'sigle' => $request->sigle,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'a_sechoir' => $request->boolean('a_sechoir'),
            'compte_bancaire' => $request->compte_bancaire,
            'cle_rib' => $request->cle_rib,
            'code_banque' => $request->code_banque,
            'code_guichet' => $request->code_guichet,
            'nom_cooperative_banque' => $request->nom_cooperative_banque,
        ]);

        // Sauvegarder les distances vers les centres de collecte
        $centreMapping = [
            'cotraf' => 'COT1',
            'duekoue' => 'DUEK', 
            'guiglo' => 'GUIG',
            'divo' => 'DIVO',
            'abengourou' => 'ABENG',
            'meagui' => 'MEAG',
            'cotraf_korhogo' => 'COT2'
        ];
        
        foreach ($centreMapping as $key => $code) {
            $centre = CentreCollecte::where('code', $code)->first();
            if ($centre && isset($request->distances[$key])) {
                CooperativeDistance::create([
                    'cooperative_id' => $cooperative->id,
                    'centre_collecte_id' => $centre->id,
                    'distance_km' => $request->distances[$key]
                ]);
            }
        }

        // Gestion des uploads de documents un à un (SÉCURISÉ)
        foreach ($this->documentTypes as $key => $label) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                
                // SÉCURITÉ 1: Valider le MIME type réel (PDF UNIQUEMENT)
                $mimeType = $file->getMimeType();
                $allowedMimes = ['application/pdf'];
                
                if (!in_array($mimeType, $allowedMimes)) {
                    return back()->withErrors([
                        $key => 'Type de fichier non autorisé pour ' . $label . '. Seul le format PDF est accepté.'
                    ])->withInput();
                }
                
                // SÉCURITÉ 2: Valider la taille (10 MB max)
                if ($file->getSize() > 10 * 1024 * 1024) {
                    return back()->withErrors([
                        $key => 'Fichier trop volumineux pour ' . $label . '. Taille maximale : 10 MB'
                    ])->withInput();
                }
                
                // SÉCURITÉ 3: Nom de fichier avec timestamp
                $extension = $file->getClientOriginalExtension();
                $filename = $key . '_' . $cooperative->code . '.' . $extension;
                
                // Stockage dans 'public' (accessible via /storage/)
                $path = $file->storeAs('cooperatives/' . $cooperative->code, $filename, 'public');
                
                CooperativeDocument::create([
                    'cooperative_id' => $cooperative->id,
                    'type' => $key,
                    'fichier' => $path,
                ]);
            }
        }

        return redirect()->route('admin.cooperatives.show', $cooperative)->with('success', 'Coopérative créée avec succès !');
    }

    public function show($id)
    {
        $cooperative = Cooperative::with(['secteur', 'documents'])->findOrFail($id);
        
        // Vérifier que l'AGC et CS ne peuvent voir que les coopératives de leur secteur
        if (auth()->check() && in_array(auth()->user()->role, ['agc', 'cs']) && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        $documentTypes = $this->documentTypes;
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        return view('admin.cooperatives.show', compact('cooperative', 'documentTypes', 'navigation'));
    }

    public function edit($id)
    {
        $cooperative = Cooperative::with(['documents', 'distances.centreCollecte'])->findOrFail($id);
        
        // Vérifier que l'AGC et CS ne peuvent voir que les coopératives de leur secteur
        if (auth()->check() && in_array(auth()->user()->role, ['agc', 'cs']) && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        $secteurs = Secteur::orderBy('code')->get();
        $documentTypes = $this->documentTypes;
        
        // Créer un mapping des distances par code de centre
        $distances = [];
        foreach ($cooperative->distances as $distance) {
            $distances[$distance->centreCollecte->code] = $distance->distance_km;
        }
        
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        return view('admin.cooperatives.edit', compact('cooperative', 'secteurs', 'documentTypes', 'distances', 'navigation'));
    }

    public function update(Request $request, $id)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        // Vérifier que l'AGC et CS ne peuvent voir que les coopératives de leur secteur
        if (auth()->check() && in_array(auth()->user()->role, ['agc', 'cs']) && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        // Pour les AGC et CS, traiter uniquement les documents
        if (in_array(auth()->user()->role, ['agc', 'cs'])) {
            // Gestion des uploads de documents (ajout ou remplacement - SÉCURISÉ)
            foreach ($this->documentTypes as $key => $label) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    
                    // SÉCURITÉ 1: Valider le MIME type réel (PDF UNIQUEMENT)
                    $mimeType = $file->getMimeType();
                    $allowedMimes = ['application/pdf'];
                    
                    if (!in_array($mimeType, $allowedMimes)) {
                        return back()->withErrors([
                            $key => 'Type de fichier non autorisé pour ' . $label . '. Seul le format PDF est accepté.'
                        ])->withInput();
                    }
                    
                    // SÉCURITÉ 2: Valider la taille (10 MB max)
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        return back()->withErrors([
                            $key => 'Fichier trop volumineux pour ' . $label . '. Taille maximale : 10 MB'
                        ])->withInput();
                    }
                    
                    // SÉCURITÉ 3: Nom de fichier avec code coopérative
                    $extension = $file->getClientOriginalExtension();
                    $filename = $key . '_' . $cooperative->code . '.' . $extension;
                    
                    // SÉCURITÉ 4: Supprimer l'ancien document s'il existe
                    $existingDoc = $cooperative->documents()->where('type', $key)->first();
                    if ($existingDoc) {
                        if (Storage::exists($existingDoc->fichier)) {
                            Storage::delete($existingDoc->fichier);
                        }
                        $existingDoc->delete();
                    }
                    
                    // SÉCURITÉ 5: Stockage sécurisé
                    $path = $file->storeAs('cooperatives/documents', $filename, 'public');
                    
                    // SÉCURITÉ 6: Validation base64 stricte (détection corruption)
                    $fileContent = Storage::get('public/' . $path);
                    if (base64_encode(base64_decode($fileContent, true)) !== base64_encode($fileContent)) {
                        Storage::delete('public/' . $path);
                        return back()->withErrors([
                            $key => 'Fichier corrompu détecté pour ' . $label . '. Veuillez réessayer.'
                        ])->withInput();
                    }
                    
                    // Créer l'enregistrement en base
                    $cooperative->documents()->create([
                        'type' => $key,
                        'fichier' => $path,
                    ]);
                }
            }
            
            return redirect()->route('admin.cooperatives.show', $cooperative)->with('success', 'Documents mis à jour avec succès !');
        }
        
        $request->validate([
            'code' => 'required|string|max:50|unique:cooperatives,code,' . $cooperative->id,
            'nom' => 'required|string|max:255',
            'secteur_id' => 'required|exists:secteurs,id',
            'president' => 'required|string|max:255',
            'contact' => 'required|digits:10',
            'sigle' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kilometrage' => 'nullable|numeric',
            'compte_bancaire' => 'required|digits:12',
            'cle_rib' => 'required|string|size:2|regex:/^[0-9]{2}$/',
            'code_banque' => 'required|string|size:5|regex:/^[A-Z0-9]{5}$/',
            'code_guichet' => 'required|digits:5',
            'nom_cooperative_banque' => 'required|string|max:255',
        ]);

        $cooperative->update([
            'code' => $request->code,
            'nom' => $request->nom,
            'secteur_id' => $request->secteur_id,
            'president' => $request->president,
            'contact' => $request->contact,
            'sigle' => $request->sigle,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kilometrage' => $request->kilometrage,
            'a_sechoir' => $request->boolean('a_sechoir'),
            'compte_bancaire' => $request->compte_bancaire,
            'cle_rib' => $request->cle_rib,
            'code_banque' => $request->code_banque,
            'code_guichet' => $request->code_guichet,
            'nom_cooperative_banque' => $request->nom_cooperative_banque,
        ]);

        // Mettre à jour les distances vers les centres de collecte
        $centreMapping = [
            'cotraf' => 'COT1',
            'duekoue' => 'DUEK', 
            'guiglo' => 'GUIG',
            'divo' => 'DIVO',
            'abengourou' => 'ABENG',
            'meagui' => 'MEAG',
            'cotraf_korhogo' => 'COT2'
        ];
        
        foreach ($centreMapping as $key => $code) {
            $centre = CentreCollecte::where('code', $code)->first();
            if ($centre && isset($request->distances[$key])) {
                CooperativeDistance::updateOrCreate(
                    [
                        'cooperative_id' => $cooperative->id,
                        'centre_collecte_id' => $centre->id
                    ],
                    [
                        'distance_km' => $request->distances[$key]
                    ]
                );
            }
        }

        // Gestion des uploads de documents (ajout ou remplacement - SÉCURISÉ)
        foreach ($this->documentTypes as $key => $label) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                
                // SÉCURITÉ 1: Valider le MIME type réel (PDF UNIQUEMENT)
                $mimeType = $file->getMimeType();
                $allowedMimes = ['application/pdf'];
                
                if (!in_array($mimeType, $allowedMimes)) {
                    return back()->withErrors([
                        $key => 'Type de fichier non autorisé pour ' . $label . '. Seul le format PDF est accepté.'
                    ])->withInput();
                }
                
                // SÉCURITÉ 2: Valider la taille (10 MB max)
                if ($file->getSize() > 10 * 1024 * 1024) {
                    return back()->withErrors([
                        $key => 'Fichier trop volumineux pour ' . $label . '. Taille maximale : 10 MB'
                    ])->withInput();
                }
                
                // SÉCURITÉ 3: Nom de fichier avec code coopérative
                $extension = $file->getClientOriginalExtension();
                $filename = $key . '_' . $cooperative->code . '.' . $extension;
                
                // Stockage dans 'public' (accessible via /storage/)
                $path = $file->storeAs('cooperatives/' . $cooperative->code, $filename, 'public');
                
                // Supprimer l'ancien document si existe
                $doc = $cooperative->documents()->where('type', $key)->first();
                if ($doc) {
                    Storage::disk('public')->delete($doc->fichier);
                    $doc->update(['fichier' => $path]);
                } else {
                    CooperativeDocument::create([
                        'cooperative_id' => $cooperative->id,
                        'type' => $key,
                        'fichier' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('admin.cooperatives.show', $cooperative)->with('success', 'Coopérative mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $cooperative = Cooperative::findOrFail($id);
        // Supprimer tous les fichiers liés
        foreach ($cooperative->documents as $doc) {
            Storage::delete($doc->fichier);
        }
        // Supprimer le dossier de la coopérative
        Storage::deleteDirectory('cooperatives/' . $cooperative->code);
        $cooperative->delete();
        return redirect()->route('admin.cooperatives.index')->with('success', 'Coopérative supprimée avec succès !');
    }
}
