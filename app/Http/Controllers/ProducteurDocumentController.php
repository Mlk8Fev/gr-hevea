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
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $producteurId)
    {
        $type = $request->input('type', 'lettre_engagement');
        $producteur = Producteur::findOrFail($producteurId);
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
            // Sauvegarde de la signature en PNG
            $signatureData = $request->input('signature');
            $codeFphci = $producteur->code_fphci;
            $signaturePath = null;
            if (preg_match('/^data:image\/(png|jpeg);base64,/', $signatureData)) {
                $signatureData = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $signatureData);
                $signatureData = str_replace(' ', '+', $signatureData);
                $filename = "signature_lettre_d'engagement_{$codeFphci}.png";
                $filePath = "signatures/{$filename}";
                \Storage::disk('public')->put($filePath, base64_decode($signatureData));
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
            return view('admin.producteurs.documents.edit', compact('producteur', 'document', 'data', 'navigation'));
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
                if (preg_match('/^data:image\/(png|jpeg);base64,/', $signatureData)) {
                    $signatureData = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $signatureData);
                    $signatureData = str_replace(' ', '+', $signatureData);
                    $filename = "signature_lettre_d'engagement_{$codeFphci}.png";
                    $filePath = "signatures/{$filename}";
                    \Storage::disk('public')->put($filePath, base64_decode($signatureData));
                    $signaturePath = $filePath;
                }
            }
            $document->update([
                'data' => json_encode($data),
                'signature' => $signaturePath,
            ]);
            return redirect()->route('admin.producteurs.show', $producteur)->with('success', 'Lettre d\'engagement modifiée !');
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function generatePdf($producteurId, $documentId)
    {
        $document = ProducteurDocument::findOrFail($documentId);
        $producteur = Producteur::with('secteur')->findOrFail($producteurId);
        $data = json_decode($document->data, true);
        $logoPath = public_path('wowdash/images/fph-ci.png'); // Place ton logo ici
        $signaturePath = $document->signature ? public_path('storage/' . $document->signature) : null;
        $pdf = Pdf::loadView('admin.producteurs.documents.pdf_lettre_engagement', [
            'producteur' => $producteur,
            'document' => $document,
            'data' => $data,
            'logoPath' => $logoPath,
            'signaturePath' => $signaturePath,
        ]);
        $filename = 'Lettre_Engagement_'.$producteur->code_fphci.'.pdf';
        if (request('download')) {
            return $pdf->download($filename);
        } else {
            return $pdf->stream($filename);
        }
    }
}
