<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producteur;
use App\Models\ProducteurDocument;
use Barryvdh\DomPDF\Facade\Pdf;

class ProducteurDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $producteurId)
    {
        $type = $request->input('type', 'lettre_engagement');
        $producteur = Producteur::with('secteur')->findOrFail($producteurId);
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        // On pourra ajouter d'autres types plus tard
        if ($type === 'lettre_engagement') {
            return view('admin.producteurs.documents.create', compact('producteur', 'navigation'));
        }
        if ($type === 'self_declaration') {
            return view('admin.producteurs.documents.create', compact('producteur', 'navigation', 'type'));
        }
        if ($type === 'fiche_enquete') {
            return view('admin.producteurs.documents.create', compact('producteur', 'navigation', 'type'));
        }
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $producteurId)
    {
        $type = $request->input('type', 'lettre_engagement');
        $producteur = Producteur::findOrFail($producteurId);
        if ($type === 'self_declaration') {
            $request->validate([
                'adresse_complete' => 'required',
                'lieu' => 'required',
                'signature' => 'required',
            ]);
            $data = [
                'adresse_complete' => $request->input('adresse_complete'),
                'lieu' => $request->input('lieu'),
                'date' => date('d/m/Y'),
            ];
            $signatureData = $request->input('signature');
            $codeFphci = $producteur->code_fphci;
            $signaturePath = null;
            
            // SÉCURITÉ: Validation base64
            if (preg_match('/^data:image\/(png|jpeg);base64,/', $signatureData)) {
                $signatureData = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $signatureData);
                $signatureData = str_replace(' ', '+', $signatureData);
                
                $decodedData = base64_decode($signatureData, true);
                
                if ($decodedData === false) {
                    return back()->withErrors(['signature' => 'Signature invalide'])->withInput();
                }
                
                if (strlen($decodedData) > 2 * 1024 * 1024) {
                    return back()->withErrors(['signature' => 'Signature trop volumineuse (max 2 MB)'])->withInput();
                }
                
                $filename = "selfdeclaration_{$codeFphci}.png";
                $filePath = "signatures/selfdeclaration/{$filename}";
                \Storage::disk('public')->put($filePath, $decodedData);
                $signaturePath = $filePath;
            }
            $document = ProducteurDocument::create([
                'producteur_id' => $producteur->id,
                'type' => 'self_declaration',
                'data' => json_encode($data),
                'signature' => $signaturePath,
                'code_fphci' => $codeFphci,
            ]);
            return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Déclaration sur l\'honneur enregistrée !');
        }
        if ($type === 'lettre_engagement') {
            $request->validate([
                'genre' => 'required',
                'date_naissance' => 'required|date',
                'lieu_naissance' => 'required',
                'profession' => 'required',
                'domicile' => 'required',
                'lieu_plantation' => 'required',
                'commune' => 'required',
                'sous_prefecture' => 'required',
                'date_signature' => 'required|date',
                'signature' => 'required',
            ]);
            $data = $request->except(['_token', 'signature']);
            // Sauvegarde de la signature en PNG (SÉCURISÉ)
            $signatureData = $request->input('signature');
            $codeFphci = $producteur->code_fphci;
            $signaturePath = null;
            
            // SÉCURITÉ: Validation base64
            if (preg_match('/^data:image\/(png|jpeg);base64,/', $signatureData)) {
                $signatureData = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $signatureData);
                $signatureData = str_replace(' ', '+', $signatureData);
                
                $decodedData = base64_decode($signatureData, true);
                
                if ($decodedData === false) {
                    return back()->withErrors(['signature' => 'Signature invalide'])->withInput();
                }
                
                if (strlen($decodedData) > 2 * 1024 * 1024) {
                    return back()->withErrors(['signature' => 'Signature trop volumineuse (max 2 MB)'])->withInput();
                }
                
                $filename = "signature_lettre_engagement_{$codeFphci}.png";
                $filePath = "signatures/lettre_engagement/{$filename}";
                \Storage::disk('public')->put($filePath, $decodedData);
                $signaturePath = $filePath;
            }
            ProducteurDocument::create([
                'producteur_id' => $producteur->id,
                'code_fphci' => $codeFphci,
                'type' => $type,
                'data' => json_encode($data),
                'signature' => $signaturePath,
            ]);
            return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Lettre d\'engagement enregistrée !');
        }
        if ($type === 'fiche_enquete') {
            // Validation allégée : on ne valide que les signatures et l'enquêteur
            $request->validate([
                'enqueteur_nom' => 'required',
                'enqueteur_tel' => 'required',
                'signature_producer' => 'required',
                'signature_agent' => 'required',
            ]);
            $data = $request->except(['_token', 'signature_producer', 'signature_agent']);
            $codeFphci = $producteur->code_fphci;
            // Signature producteur (SÉCURISÉ)
            $signatureProducerPath = null;
            if ($request->filled('signature_producer')) {
                $signatureData = $request->input('signature_producer');
                
                // SÉCURITÉ 1: Valider le format base64 strict
                if (preg_match('/^data:image\\/(png|jpeg);base64,/', $signatureData)) {
                    $signatureData = preg_replace('/^data:image\\/(png|jpeg);base64,/', '', $signatureData);
                    $signatureData = str_replace(' ', '+', $signatureData);
                    
                    // SÉCURITÉ 2: Décoder et valider que c'est une image valide
                    $decodedData = base64_decode($signatureData, true);
                    
                    if ($decodedData === false) {
                        return back()->withErrors(['signature_producer' => 'Signature invalide (base64 corrompu)']);
                    }
                    
                    // SÉCURITÉ 3: Vérifier la taille (max 2 MB pour une signature)
                    if (strlen($decodedData) > 2 * 1024 * 1024) {
                        return back()->withErrors(['signature_producer' => 'Signature trop volumineuse (max 2 MB)']);
                    }
                    
                    // SÉCURITÉ 4: Générer nom de fichier aléatoire + stocker dans 'local'
                    $filename = "signature_producer_".\Illuminate\Support\Str::random(40).".png";
                    $filePath = "signaturefichedenquete/{$filename}";
                    \Storage::disk('public')->put($filePath, $decodedData);
                    $signatureProducerPath = $filePath;
                }
            }
            // Signature agent (SÉCURISÉ)
            $signatureAgentPath = null;
            if ($request->filled('signature_agent')) {
                $signatureData = $request->input('signature_agent');
                
                // SÉCURITÉ 1: Valider le format base64 strict
                if (preg_match('/^data:image\\/(png|jpeg);base64,/', $signatureData)) {
                    $signatureData = preg_replace('/^data:image\\/(png|jpeg);base64,/', '', $signatureData);
                    $signatureData = str_replace(' ', '+', $signatureData);
                    
                    // SÉCURITÉ 2: Décoder et valider que c'est une image valide
                    $decodedData = base64_decode($signatureData, true);
                    
                    if ($decodedData === false) {
                        return back()->withErrors(['signature_agent' => 'Signature invalide (base64 corrompu)']);
                    }
                    
                    // SÉCURITÉ 3: Vérifier la taille (max 2 MB pour une signature)
                    if (strlen($decodedData) > 2 * 1024 * 1024) {
                        return back()->withErrors(['signature_agent' => 'Signature trop volumineuse (max 2 MB)']);
                    }
                    
                    // SÉCURITÉ 4: Générer nom de fichier aléatoire + stocker dans 'local'
                    $filename = "signature_agent_".\Illuminate\Support\Str::random(40).".png";
                    $filePath = "signaturefichedenquete/{$filename}";
                    \Storage::disk('public')->put($filePath, $decodedData);
                    $signatureAgentPath = $filePath;
                }
            }
            $data['signature_producer'] = $signatureProducerPath;
            $data['signature_agent'] = $signatureAgentPath;
            $document = ProducteurDocument::create([
                'producteur_id' => $producteur->id,
                'type' => 'fiche_enquete',
                'data' => json_encode($data),
                'signature' => null,
                'code_fphci' => $codeFphci,
            ]);
            return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Fiche d\'enquête enregistrée !');
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show($producteurId, $documentId)
    {
        $document = ProducteurDocument::findOrFail($documentId);
        if ($document->type === 'lettre_engagement') {
            return $this->generatePdf($producteurId, $documentId);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($producteurId, $documentId)
    {
        $document = ProducteurDocument::findOrFail($documentId);
        $producteur = Producteur::with('secteur')->findOrFail($producteurId);
        $navigation = app(\App\Services\NavigationService::class)->getNavigation();
        $data = json_decode($document->data, true);
        if ($document->type === 'lettre_engagement') {
            $type = 'lettre_engagement';
            return view('admin.producteurs.documents.edit', compact('producteur', 'document', 'data', 'navigation', 'type'));
        }
        if ($document->type === 'self_declaration') {
            $type = 'self_declaration';
            return view('admin.producteurs.documents.edit', compact('producteur', 'document', 'data', 'navigation', 'type'));
        }
        if ($document->type === 'fiche_enquete') {
            $type = 'fiche_enquete';
            return view('admin.producteurs.documents.edit', compact('producteur', 'document', 'data', 'navigation', 'type'));
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $producteurId, $documentId)
    {
        $document = ProducteurDocument::findOrFail($documentId);
        $producteur = Producteur::findOrFail($producteurId);
        if ($document->type === 'lettre_engagement') {
            $request->validate([
                'genre' => 'required',
                'date_naissance' => 'required|date',
                'lieu_naissance' => 'required',
                'profession' => 'required',
                'domicile' => 'required',
                'lieu_plantation' => 'required',
                'commune' => 'required',
                'sous_prefecture' => 'required',
                'date_signature' => 'required|date',
            ]);
            $data = $request->except(['_token', '_method', 'signature']);
            $signaturePath = $document->signature;
            if ($request->filled('signature')) {
                $signatureData = $request->input('signature');
                $codeFphci = $producteur->code_fphci;
                
                // SÉCURITÉ: Validation base64
                if (preg_match('/^data:image\/(png|jpeg);base64,/', $signatureData)) {
                    $signatureData = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $signatureData);
                    $signatureData = str_replace(' ', '+', $signatureData);
                    
                    $decodedData = base64_decode($signatureData, true);
                    
                    if ($decodedData === false) {
                        return back()->withErrors(['signature' => 'Signature invalide'])->withInput();
                    }
                    
                    if (strlen($decodedData) > 2 * 1024 * 1024) {
                        return back()->withErrors(['signature' => 'Signature trop volumineuse (max 2 MB)'])->withInput();
                    }
                    
                    $filename = "signature_lettre_".\Illuminate\Support\Str::random(40).".png";
                    $filePath = "signatures/lettre_engagement/{$filename}";
                    \Storage::disk('public')->put($filePath, $decodedData);
                    $signaturePath = $filePath;
                }
            }
            $document->update([
                'data' => json_encode($data),
                'signature' => $signaturePath,
            ]);
            return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Lettre d\'engagement modifiée !');
        }
        if ($document->type === 'self_declaration') {
            $request->validate([
                'adresse_complete' => 'required',
                'lieu' => 'required',
            ]);
            $data = [
                'adresse_complete' => $request->input('adresse_complete'),
                'lieu' => $request->input('lieu'),
                'date' => $document->data ? (json_decode($document->data, true)['date'] ?? date('d/m/Y')) : date('d/m/Y'),
            ];
            $signaturePath = $document->signature;
            if ($request->filled('signature')) {
                $signatureData = $request->input('signature');
                $codeFphci = $producteur->code_fphci;
                
                // SÉCURITÉ 1: Valider le format base64 strict
                if (preg_match('/^data:image\/(png|jpeg);base64,/', $signatureData)) {
                    $signatureData = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $signatureData);
                    $signatureData = str_replace(' ', '+', $signatureData);
                    
                    // SÉCURITÉ 2: Décoder et valider
                    $decodedData = base64_decode($signatureData, true);
                    
                    if ($decodedData === false) {
                        return back()->withErrors(['signature' => 'Signature invalide (base64 corrompu)']);
                    }
                    
                    // SÉCURITÉ 3: Vérifier la taille (max 2 MB)
                    if (strlen($decodedData) > 2 * 1024 * 1024) {
                        return back()->withErrors(['signature' => 'Signature trop volumineuse (max 2 MB)']);
                    }
                    
                    // SÉCURITÉ 4: Nom aléatoire + stockage 'local'
                    $filename = "signature_selfdec_".\Illuminate\Support\Str::random(40).".png";
                    $filePath = "signatures/selfdeclaration/{$filename}";
                    \Storage::disk('public')->put($filePath, $decodedData);
                    $signaturePath = $filePath;
                }
            }
            $document->update([
                'data' => json_encode($data),
                'signature' => $signaturePath,
            ]);
            return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Déclaration sur l\'honneur modifiée !');
        }
        if ($document->type === 'fiche_enquete') {
            // Validation allégée : on ne valide que l'enquêteur
            $request->validate([
                'enqueteur_nom' => 'required',
                'enqueteur_tel' => 'required',
            ]);
            
            // Récupérer les données existantes
            $existingData = json_decode($document->data, true) ?? [];
            
            // Récupérer seulement les nouvelles données du formulaire (informations personnelles)
            $newData = $request->except(['_token', '_method', 'signature_producer', 'signature_agent']);
            $codeFphci = $producteur->code_fphci;
            
            // Fusionner les données : nouvelles données personnelles + données existantes du questionnaire
            $mergedData = array_merge($existingData, $newData);
            
            // Signature producteur (SÉCURISÉ)
            $signatureProducerPath = $existingData['signature_producer'] ?? null;
            if ($request->filled('signature_producer')) {
                $signatureData = $request->input('signature_producer');
                
                // SÉCURITÉ 1: Valider le format base64 strict
                if (preg_match('/^data:image\\/(png|jpeg);base64,/', $signatureData)) {
                    $signatureData = preg_replace('/^data:image\\/(png|jpeg);base64,/', '', $signatureData);
                    $signatureData = str_replace(' ', '+', $signatureData);
                    
                    // SÉCURITÉ 2: Décoder et valider
                    $decodedData = base64_decode($signatureData, true);
                    
                    if ($decodedData === false) {
                        return back()->withErrors(['signature_producer' => 'Signature invalide (base64 corrompu)']);
                    }
                    
                    // SÉCURITÉ 3: Vérifier la taille (max 2 MB)
                    if (strlen($decodedData) > 2 * 1024 * 1024) {
                        return back()->withErrors(['signature_producer' => 'Signature trop volumineuse (max 2 MB)']);
                    }
                    
                    // SÉCURITÉ 4: Nom avec code FPHCI + stockage 'local'
                    $filename = "signaturefichedenquete_producer_{$codeFphci}.png";
                    $filePath = "signaturefichedenquete/{$filename}";
                    \Storage::disk('public')->put($filePath, $decodedData);
                    $signatureProducerPath = $filePath;
                }
            }
            
            // Signature agent (SÉCURISÉ)
            $signatureAgentPath = $existingData['signature_agent'] ?? null;
            if ($request->filled('signature_agent')) {
                $signatureData = $request->input('signature_agent');
                
                // SÉCURITÉ 1: Valider le format base64 strict
                if (preg_match('/^data:image\\/(png|jpeg);base64,/', $signatureData)) {
                    $signatureData = preg_replace('/^data:image\\/(png|jpeg);base64,/', '', $signatureData);
                    $signatureData = str_replace(' ', '+', $signatureData);
                    
                    // SÉCURITÉ 2: Décoder et valider
                    $decodedData = base64_decode($signatureData, true);
                    
                    if ($decodedData === false) {
                        return back()->withErrors(['signature_agent' => 'Signature invalide (base64 corrompu)']);
                    }
                    
                    // SÉCURITÉ 3: Vérifier la taille (max 2 MB)
                    if (strlen($decodedData) > 2 * 1024 * 1024) {
                        return back()->withErrors(['signature_agent' => 'Signature trop volumineuse (max 2 MB)']);
                    }
                    
                    // SÉCURITÉ 4: Nom avec code FPHCI + stockage 'local'
                    $filename = "signaturefichedenquete_agent_{$codeFphci}.png";
                    $filePath = "signaturefichedenquete/{$filename}";
                    \Storage::disk('public')->put($filePath, $decodedData);
                    $signatureAgentPath = $filePath;
                }
            }
            
            $mergedData['signature_producer'] = $signatureProducerPath;
            $mergedData['signature_agent'] = $signatureAgentPath;
            
            $document->update([
                'data' => json_encode($mergedData),
            ]);
            return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Fiche d\'enquête modifiée !');
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($producteurId, $documentId)
    {
        $document = ProducteurDocument::findOrFail($documentId);
        $producteur = Producteur::findOrFail($producteurId);
        
        // Supprimer les fichiers de signature s'ils existent
        if ($document->signature && \Storage::disk('public')->exists($document->signature)) {
            \Storage::disk('public')->delete($document->signature);
        }
        
        // Pour la fiche d'enquête, supprimer les signatures séparées
        if ($document->type === 'fiche_enquete') {
            $data = json_decode($document->data, true);
            if (isset($data['signature_producer']) && \Storage::disk('public')->exists($data['signature_producer'])) {
                \Storage::disk('public')->delete($data['signature_producer']);
            }
            if (isset($data['signature_agent']) && \Storage::disk('public')->exists($data['signature_agent'])) {
                \Storage::disk('public')->delete($data['signature_agent']);
            }
        }
        
        // Supprimer le document
        $document->delete();
        
        return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Document supprimé avec succès !');
    }

    public function generatePdf($producteurId, $documentId, Request $request)
    {
        $document = ProducteurDocument::findOrFail($documentId);
        $producteur = Producteur::findOrFail($producteurId);
        $data = json_decode($document->data, true);
        $download = $request->has('download');
        if ($document->type === 'lettre_engagement') {
            $logoPath = public_path('wowdash/images/fph-ci.png');
            $signaturePath = $document->signature ? public_path('storage/' . $document->signature) : null;
            $pdf = \PDF::loadView('admin.producteurs.documents.pdf_lettre_engagement', compact('producteur', 'document', 'data', 'logoPath', 'signaturePath'));
        } elseif ($document->type === 'self_declaration') {
            $pdf = \PDF::loadView('admin.producteurs.documents.pdf_self_declaration', compact('producteur', 'document', 'data'));
        } elseif ($document->type === 'fiche_enquete') {
            $pdf = \PDF::loadView('admin.producteurs.documents.pdf_fiche_enquete', compact('producteur', 'document', 'data'));
        } else {
            abort(404);
        }
        $filename = $document->type . '_' . $producteur->code_fphci . '.pdf';
        if ($download) {
            return $pdf->download($filename);
        }
        return $pdf->stream($filename);
    }
}
