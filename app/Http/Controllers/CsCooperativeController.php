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

class CsCooperativeController extends Controller
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
        
        // Pour les CS, filtrer uniquement les coopératives de leur secteur
        if (auth()->check() && auth()->user()->role === 'cs' && auth()->user()->secteur) {
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
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%");
            });
        }
        
        $cooperatives = $query->paginate(20)->withQueryString();
        $secteurs = Secteur::orderBy('nom')->get();
        
        // Récupérer la navigation pour l'utilisateur CS
        $navigationService = app(\App\Services\NavigationService::class);
        $navigation = $navigationService->getNavigation();
        
        return view('cs.cooperatives.index', compact('cooperatives', 'secteurs', 'navigation'));
    }

    public function show($id)
    {
        $cooperative = Cooperative::with(['secteur', 'documents'])->findOrFail($id);
        
        // Vérifier que le CS ne peut voir que les coopératives de son secteur
        if (auth()->check() && auth()->user()->role === 'cs' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        // Récupérer la navigation pour l'utilisateur CS
        $navigationService = app(\App\Services\NavigationService::class);
        $navigation = $navigationService->getNavigation();
        
        return view('cs.cooperatives.show', compact('cooperative', 'navigation') + ['documentTypes' => $this->documentTypes]);
    }

    public function edit($id)
    {
        $cooperative = Cooperative::with(['secteur', 'documents'])->findOrFail($id);
        
        // Vérifier que le CS ne peut voir que les coopératives de son secteur
        if (auth()->check() && auth()->user()->role === 'cs' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        $secteurs = Secteur::orderBy('nom')->get();
        $centresCollecte = CentreCollecte::orderBy('nom')->get();
        
        // Récupérer la navigation pour l'utilisateur CS
        $navigationService = app(\App\Services\NavigationService::class);
        $navigation = $navigationService->getNavigation();
        
        return view('cs.cooperatives.edit', compact('cooperative', 'secteurs', 'centresCollecte', 'navigation') + ['documentTypes' => $this->documentTypes]);
    }

    public function update(Request $request, $id)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        // Vérifier que le CS ne peut voir que les coopératives de son secteur
        if (auth()->check() && auth()->user()->role === 'cs' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        // Pour les CS, traiter uniquement les documents
        if (auth()->user()->role === 'cs') {
            $documentsProcessed = false;
            
            // Gestion des uploads de documents (ajout ou remplacement - SÉCURISÉ)
            foreach ($this->documentTypes as $key => $label) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    
                    // Vérification supplémentaire que le fichier existe et n'est pas vide
                    if (!$file || $file->getSize() === 0) {
                        continue;
                    }
                    
                    $documentsProcessed = true;
                    
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
            
            if ($documentsProcessed) {
                return redirect()->route('cs.cooperatives.show', $cooperative)
                    ->with('success', 'Documents mis à jour avec succès !');
            } else {
                return redirect()->route('cs.cooperatives.show', $cooperative)
                    ->with('info', 'Aucun document valide n\'a été fourni.');
            }
        }
        
        // Logique normale pour les autres rôles (si nécessaire)
        return redirect()->route('cs.cooperatives.show', $cooperative)
            ->with('success', 'Coopérative mise à jour avec succès.');
    }

    public function documents($id)
    {
        $cooperative = Cooperative::with(['secteur', 'documents'])->findOrFail($id);
        
        // Vérifier que le CS ne peut voir que les coopératives de son secteur
        if (auth()->check() && auth()->user()->role === 'cs' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        // Récupérer la navigation pour l'utilisateur CS
        $navigationService = app(\App\Services\NavigationService::class);
        $navigation = $navigationService->getNavigation();
        
        return view('cs.cooperatives.documents', compact('cooperative', 'navigation') + ['documentTypes' => $this->documentTypes]);
    }

    public function storeDocument(Request $request, $id)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        // Vérifier que le CS ne peut voir que les coopératives de son secteur
        if (auth()->check() && auth()->user()->role === 'cs' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys($this->documentTypes)),
            'document' => 'required|file|mimes:pdf|max:10240'
        ]);
        
        $file = $request->file('document');
        $type = $request->type;
        
        // SÉCURITÉ 1: Valider le MIME type réel (PDF UNIQUEMENT)
        $mimeType = $file->getMimeType();
        $allowedMimes = ['application/pdf'];
        
        if (!in_array($mimeType, $allowedMimes)) {
            return back()->withErrors([
                'document' => 'Type de fichier non autorisé. Seul le format PDF est accepté.'
            ])->withInput();
        }
        
        // SÉCURITÉ 2: Valider la taille (10 MB max)
        if ($file->getSize() > 10 * 1024 * 1024) {
            return back()->withErrors([
                'document' => 'Fichier trop volumineux. Taille maximale : 10 MB'
            ])->withInput();
        }
        
        // SÉCURITÉ 3: Nom de fichier avec code coopérative
        $extension = $file->getClientOriginalExtension();
        $filename = $type . '_' . $cooperative->code . '.' . $extension;
        
        // SÉCURITÉ 4: Supprimer l'ancien document s'il existe
        $existingDoc = $cooperative->documents()->where('type', $type)->first();
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
                'document' => 'Fichier corrompu détecté. Veuillez réessayer.'
            ])->withInput();
        }
        
        // Créer l'enregistrement en base
        $cooperative->documents()->create([
            'type' => $type,
            'fichier' => $path,
        ]);
        
        return redirect()->route('cs.cooperatives.documents', $cooperative)
            ->with('success', 'Document ajouté avec succès.');
    }

    public function destroyDocument($id, $documentId)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        // Vérifier que le CS ne peut voir que les coopératives de son secteur
        if (auth()->check() && auth()->user()->role === 'cs' && auth()->user()->secteur) {
            $userSecteurCode = auth()->user()->secteur;
            if ($cooperative->secteur->code !== $userSecteurCode) {
                abort(403, 'Accès non autorisé à cette coopérative.');
            }
        }
        
        $document = CooperativeDocument::where('cooperative_id', $cooperative->id)
            ->where('id', $documentId)
            ->firstOrFail();
        
        // Supprimer le fichier physique
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        // Supprimer l'enregistrement
        $document->delete();
        
        return redirect()->route('cs.cooperatives.documents', $cooperative)
            ->with('success', 'Document supprimé avec succès.');
    }
}