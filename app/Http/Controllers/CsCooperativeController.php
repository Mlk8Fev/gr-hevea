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
        
        return view('cs.cooperatives.show', compact('cooperative', 'navigation'));
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
        
        return view('cs.cooperatives.edit', compact('cooperative', 'secteurs', 'centresCollecte', 'navigation'));
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
            // Gestion des uploads de documents (ajout ou remplacement - SÉCURISÉ)
            foreach ($this->documentTypes as $key => $label) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    
                    // Validation du fichier
                    if (!$file->isValid()) {
                        return back()->withErrors(['error' => "Le fichier {$label} n'est pas valide."]);
                    }
                    
                    // Vérification de la taille (max 10MB)
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        return back()->withErrors(['error' => "Le fichier {$label} est trop volumineux (max 10MB)."]);
                    }
                    
                    // Vérification du type MIME
                    $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
                    if (!in_array($file->getMimeType(), $allowedMimes)) {
                        return back()->withErrors(['error' => "Le fichier {$label} doit être un PDF ou une image (JPG, PNG)."]);
                    }
                    
                    // Supprimer l'ancien document s'il existe
                    $existingDoc = CooperativeDocument::where('cooperative_id', $cooperative->id)
                        ->where('type', $key)
                        ->first();
                    
                    if ($existingDoc && $existingDoc->file_path) {
                        Storage::disk('public')->delete($existingDoc->file_path);
                        $existingDoc->delete();
                    }
                    
                    // Générer un nom de fichier unique
                    $extension = $file->getClientOriginalExtension();
                    $filename = Str::slug($cooperative->code) . '_' . $key . '_' . time() . '.' . $extension;
                    $path = 'cooperatives/' . $cooperative->id . '/' . $filename;
                    
                    // Stocker le fichier
                    $file->storeAs('cooperatives/' . $cooperative->id, $filename, 'public');
                    
                    // Enregistrer en base
                    CooperativeDocument::create([
                        'cooperative_id' => $cooperative->id,
                        'type' => $key,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]);
                }
            }
            
            return redirect()->route('cs.cooperatives.show', $cooperative)
                ->with('success', 'Documents mis à jour avec succès.');
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
        
        return view('cs.cooperatives.documents', compact('cooperative', 'navigation'));
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
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);
        
        $file = $request->file('document');
        $type = $request->type;
        
        // Supprimer l'ancien document s'il existe
        $existingDoc = CooperativeDocument::where('cooperative_id', $cooperative->id)
            ->where('type', $type)
            ->first();
        
        if ($existingDoc && $existingDoc->file_path) {
            Storage::disk('public')->delete($existingDoc->file_path);
            $existingDoc->delete();
        }
        
        // Générer un nom de fichier unique
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug($cooperative->code) . '_' . $type . '_' . time() . '.' . $extension;
        $path = 'cooperatives/' . $cooperative->id . '/' . $filename;
        
        // Stocker le fichier
        $file->storeAs('cooperatives/' . $cooperative->id, $filename, 'public');
        
        // Enregistrer en base
        CooperativeDocument::create([
            'cooperative_id' => $cooperative->id,
            'type' => $type,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
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