@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card radius-12 p-24">
                <h4 class="mb-4 fw-bold text-primary">Lettre d'engagement du producteur</h4>
                <form action="{{ route('admin.producteurs.documents.store', ['producteur' => $producteur->id, 'type' => 'lettre_engagement']) }}" method="POST" enctype="multipart/form-data" id="engagement-form">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" value="{{ $producteur->nom }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control" value="{{ $producteur->prenom }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Genre</label>
                            <select class="form-select" name="genre" required>
                                <option value="">Sélectionner</option>
                                <option value="Mme">Mme</option>
                                <option value="Mlle">Mlle</option>
                                <option value="M">M.</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" name="date_naissance" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lieu de naissance</label>
                            <input type="text" class="form-control" name="lieu_naissance" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profession</label>
                            <input type="text" class="form-control" name="profession" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom du Bureau de Secteur</label>
                            <input type="text" class="form-control" value="{{ $producteur->secteur ? $producteur->secteur->nom : '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Domicile</label>
                            <input type="text" class="form-control" name="domicile" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Superficie (ha)</label>
                            <input type="text" class="form-control" value="{{ $producteur->superficie_totale }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lieu de la plantation</label>
                            <input type="text" class="form-control" name="lieu_plantation" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Commune</label>
                            <input type="text" class="form-control" name="commune" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sous-préfecture</label>
                            <input type="text" class="form-control" name="sous_prefecture" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de signature</label>
                            <input type="date" class="form-control" name="date_signature" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Signature du producteur <span class="text-danger">*</span></label>
                        <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                            <canvas id="signature-pad" width="400" height="120" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                            <input type="hidden" name="signature" id="signature-input">
                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature">Effacer</button>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-success px-4">Enregistrer</button>
                        <a href="{{ route('admin.producteurs.show', $producteur) }}" class="btn btn-secondary px-4">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
const canvas = document.getElementById('signature-pad');
const signaturePad = new SignaturePad(canvas, { backgroundColor: '#fff' });
document.getElementById('clear-signature').onclick = function() {
    signaturePad.clear();
};
document.getElementById('engagement-form').onsubmit = function(e) {
    if (signaturePad.isEmpty()) {
        alert('Veuillez signer avant de soumettre.');
        e.preventDefault();
        return false;
    }
    document.getElementById('signature-input').value = signaturePad.toDataURL();
};
</script>
@endsection 