@extends('layouts.app')
@section('content')
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card radius-12 p-24">
                    @if(isset($type) && $type === 'self_declaration')
                        <h4 class="mb-4 fw-bold text-primary">Modifier la déclaration sur l'honneur du producteur</h4>
                    @elseif(!isset($type) || $type === 'lettre_engagement')
                        <h4 class="mb-4 fw-bold text-primary">Modifier la lettre d'engagement du producteur</h4>
                    @endif
                    
                    {{-- ======================= FORMULAIRE EDIT LETTRE D'ENGAGEMENT ======================= --}}
                    @if(!isset($type) || $type === 'lettre_engagement')
                        <form action="{{ route('admin.producteurs.documents.update', ['producteur' => $producteur->id, 'document' => $document->id]) }}" method="POST" enctype="multipart/form-data" id="engagement-form">
                        @csrf
                        @method('PUT')
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
                                    <option value="Mme" {{ ($data['genre'] ?? '') == 'Mme' ? 'selected' : '' }}>Mme</option>
                                    <option value="Mlle" {{ ($data['genre'] ?? '') == 'Mlle' ? 'selected' : '' }}>Mlle</option>
                                    <option value="M" {{ ($data['genre'] ?? '') == 'M' ? 'selected' : '' }}>M.</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" name="date_naissance" value="{{ $data['date_naissance'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lieu de naissance</label>
                                <input type="text" class="form-control" name="lieu_naissance" value="{{ $data['lieu_naissance'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profession</label>
                                <input type="text" class="form-control" name="profession" value="{{ $data['profession'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nom du Bureau de Secteur</label>
                                <input type="text" class="form-control" value="{{ $producteur->secteur ? $producteur->secteur->nom : '' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Domicile</label>
                                <input type="text" class="form-control" name="domicile" value="{{ $data['domicile'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Superficie (ha)</label>
                                <input type="text" class="form-control" value="{{ $producteur->superficie_totale }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lieu de la plantation</label>
                                <input type="text" class="form-control" name="lieu_plantation" value="{{ $data['lieu_plantation'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Commune</label>
                                <input type="text" class="form-control" name="commune" value="{{ $data['commune'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sous-préfecture</label>
                                <input type="text" class="form-control" name="sous_prefecture" value="{{ $data['sous_prefecture'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de signature</label>
                                <input type="date" class="form-control" name="date_signature" value="{{ $data['date_signature'] ?? date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Signature du producteur <span class="text-danger">*</span></label>
                            <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                                <canvas id="signature-pad" width="400" height="120" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                                <input type="hidden" name="signature" id="signature-input">
                                <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature">Effacer</button>
                            </div>
                            @if($document->signature)
                                <div class="alert alert-info mt-2 p-2"><i class="ri-information-line me-1"></i> Si vous ne signez pas, la signature actuelle sera conservée.</div>
                                <div class="mt-2"><span class="text-muted">Signature actuelle :</span><br><img src="{{ asset('storage/' . $document->signature) }}" style="width:180px; height:60px; object-fit:contain;"></div>
                            @endif
                        </div>
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-success px-4"><i class="ri-save-line me-1"></i>Enregistrer les modifications</button>
                            <a href="{{ route('admin.producteurs.show', $producteur) }}" class="btn btn-secondary px-4"><i class="ri-arrow-go-back-line me-1"></i>Annuler</a>
                        </div>
                    </form>
                    @endif
                    {{-- ======================= FORMULAIRE EDIT SELF DECLARATION ======================= --}}
                    @if(isset($type) && $type === 'self_declaration')
                        <h4 class="mb-4 fw-bold text-primary">Modifier La Self Declaration</h4>
                        <form action="{{ route('admin.producteurs.documents.update', ['producteur' => $producteur->id, 'document' => $document->id]) }}" method="POST" enctype="multipart/form-data" id="selfdeclaration-form">
                            @csrf
                            @method('PUT')
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom</label>
                                    <input type="text" class="form-control" value="{{ $producteur->nom }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénoms</label>
                                    <input type="text" class="form-control" value="{{ $producteur->prenom }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" value="{{ $producteur->contact }}" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Adresse complète</label>
                                    <textarea class="form-control" name="adresse_complete" rows="2" required placeholder="Adresse, Code postal, Ville/Région, Département, Sous-préfecture">{{ $data['adresse_complete'] ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Lieu</label>
                                    <input type="text" class="form-control" name="lieu" value="{{ $data['lieu'] ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date</label>
                                    <input type="text" class="form-control" value="{{ $data['date'] ?? date('d/m/Y') }}" readonly>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="form-label">Signature du producteur <span class="text-danger">*</span></label>
                                <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                                    <canvas id="signature-pad" width="400" height="120" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                                    <input type="hidden" name="signature" id="signature-input">
                                    <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature">Effacer</button>
                                </div>
                                @if($document->signature)
                                    <div class="alert alert-info mt-2 p-2"><i class="ri-information-line me-1"></i> Si vous ne signez pas, la signature actuelle sera conservée.</div>
                                    <div class="mt-2"><span class="text-muted">Signature actuelle :</span><br><img src="{{ asset('storage/' . $document->signature) }}" style="width:120px; height:45px; object-fit:contain;"></div>
                                @endif
                            </div>
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-success px-4"><i class="ri-save-line me-1"></i>Enregistrer les modifications</button>
                                <a href="{{ route('admin.producteurs.show', $producteur) }}" class="btn btn-secondary px-4"><i class="ri-arrow-go-back-line me-1"></i>Annuler</a>
                            </div>
                        </form>
                    @endif
                    {{-- ======================= FORMULAIRE EDIT FICHE D'ENQUETE ======================= --}}
                    @if(isset($type) && $type === 'fiche_enquete')
                        <h4 class="mb-4 fw-bold text-primary">Modifier la fiche d'enquête du producteur</h4>
                        <form action="{{ route('admin.producteurs.documents.update', ['producteur' => $producteur->id, 'document' => $document->id]) }}" method="POST" enctype="multipart/form-data" id="fiche-enquete-form">
                            @csrf
                            @method('PUT')
                            
                            <!-- Données de l'opérateur et de l'agriculteur -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0 text-primary">1. Données de l'opérateur et de l'agriculteur</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <label class="form-label">ID producteur</label>
                                            <input type="text" class="form-control" value="{{ $producteur->code_fphci }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Culture certifiée</label>
                                            <input type="text" class="form-control" value="Hevea brasiliensis" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nom de l'enquêteur <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="enqueteur_nom" value="{{ $data['enqueteur_nom'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Téléphone de l'enquêteur <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="enqueteur_tel" value="{{ $data['enqueteur_tel'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nom du producteur</label>
                                            <input type="text" class="form-control" value="{{ $producteur->nom }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Prénoms du producteur</label>
                                            <input type="text" class="form-control" value="{{ $producteur->prenom }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Téléphone du producteur</label>
                                            <input type="text" class="form-control" value="{{ $producteur->contact }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Région</label>
                                            <input type="text" class="form-control" name="region" value="{{ $data['region'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Département</label>
                                            <input type="text" class="form-control" name="departement" value="{{ $data['departement'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Localité</label>
                                            <input type="text" class="form-control" name="localite" value="{{ $data['localite'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Secteur FPH-CI</label>
                                            <input type="text" class="form-control" value="{{ $producteur->secteur ? $producteur->secteur->nom : '' }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Genre</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="genre" value="Homme" {{ ($data['genre'] ?? '') == 'Homme' ? 'checked' : '' }}>
                                                        <label class="form-check-label">Homme</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="genre" value="Femme" {{ ($data['genre'] ?? '') == 'Femme' ? 'checked' : '' }}>
                                                        <label class="form-check-label">Femme</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Superficie totale cultivée (ha)</label>
                                            <input type="number" step="0.01" class="form-control" name="superficie_totale" value="{{ $data['superficie_totale'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nombre total de champs</label>
                                            <input type="number" class="form-control" name="nb_champs_total" value="{{ $data['nb_champs_total'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Superficie totale cultivée avec hévéa (ha)</label>
                                            <input type="number" step="0.01" class="form-control" name="superficie_hevea" value="{{ $data['superficie_hevea'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nombre de plantations d'hévéas</label>
                                            <input type="number" class="form-control" name="nb_plantations_hevea" value="{{ $data['nb_plantations_hevea'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Clone principal</label>
                                            <select class="form-select" name="clone_principal">
                                                <option value="">Sélectionner</option>
                                                <option value="GT1" {{ ($data['clone_principal'] ?? '') == 'GT1' ? 'selected' : '' }}>GT1</option>
                                                <option value="PB217" {{ ($data['clone_principal'] ?? '') == 'PB217' ? 'selected' : '' }}>PB217</option>
                                                <option value="PB235" {{ ($data['clone_principal'] ?? '') == 'PB235' ? 'selected' : '' }}>PB235</option>
                                                <option value="PR107" {{ ($data['clone_principal'] ?? '') == 'PR107' ? 'selected' : '' }}>PR107</option>
                                                <option value="RRIM600" {{ ($data['clone_principal'] ?? '') == 'RRIM600' ? 'selected' : '' }}>RRIM600</option>
                                                <option value="Autres" {{ ($data['clone_principal'] ?? '') == 'Autres' ? 'selected' : '' }}>Autres</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Autres clones (préciser)</label>
                                            <input type="text" class="form-control" name="clone_autre" value="{{ $data['clone_autre'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Année de création du champ d'hévéa</label>
                                            <input type="number" class="form-control" name="annee_creation_champ" value="{{ $data['annee_creation_champ'] ?? '' }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Pour laquelle de ces productions êtes-vous déjà certifiée ?</label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="certif_cacao" value="1" {{ isset($data['certif_cacao']) && !empty($data['certif_cacao']) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Cacao</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="certif_cafe" value="1" {{ isset($data['certif_cafe']) && !empty($data['certif_cafe']) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Café</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="certif_palmier" value="1" {{ isset($data['certif_palmier']) && !empty($data['certif_palmier']) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Palmier à huile</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="certif_autre" value="1" {{ isset($data['certif_autre']) && !empty($data['certif_autre']) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Autre</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2" id="certif_autre_preciser_div" style="display: {{ !empty($data['certif_autre']) ? 'block' : 'none' }};">
                                                <input type="text" class="form-control" name="certif_autre_preciser" placeholder="Préciser" value="{{ $data['certif_autre_preciser'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Le Producteur marque son accord pour la réalisation de cette enquête de durabilité, librement et sans contrainte.</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="accord_enquete" value="Oui" {{ ($data['accord_enquete'] ?? '') == 'Oui' ? 'checked' : '' }}>
                                                        <label class="form-check-label">Oui</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="accord_enquete" value="Non" {{ ($data['accord_enquete'] ?? '') == 'Non' ? 'checked' : '' }}>
                                                        <label class="form-check-label">Non</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Date</label>
                                            <input type="date" class="form-control" name="date_enquete" value="{{ $data['date_enquete'] ?? date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Signatures -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0 text-primary">Signatures</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <label class="form-label">Signature du producteur <span class="text-danger">*</span></label>
                                            <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                                                <canvas id="signature-pad-producer" width="500" height="150" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                                                <input type="hidden" name="signature_producer" id="signature-producer-input">
                                                <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature-producer">Effacer</button>
                                            </div>
                                            @if(isset($data['signature_producer']) && !empty($data['signature_producer']))
                                                <div class="alert alert-info mt-2 p-2"><i class="ri-information-line me-1"></i> Si vous ne signez pas, la signature actuelle sera conservée.</div>
                                                <div class="mt-2"><span class="text-muted">Signature actuelle :</span><br><img src="{{ asset('storage/' . $data['signature_producer']) }}" style="width:200px; height:75px; object-fit:contain;"></div>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Signature de l'agent de traçabilité <span class="text-danger">*</span></label>
                                            <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                                                <canvas id="signature-pad-agent" width="500" height="150" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                                                <input type="hidden" name="signature_agent" id="signature-agent-input">
                                                <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature-agent">Effacer</button>
                                            </div>
                                            @if(isset($data['signature_agent']) && !empty($data['signature_agent']))
                                                <div class="alert alert-info mt-2 p-2"><i class="ri-information-line me-1"></i> Si vous ne signez pas, la signature actuelle sera conservée.</div>
                                                <div class="mt-2"><span class="text-muted">Signature actuelle :</span><br><img src="{{ asset('storage/' . $data['signature_agent']) }}" style="width:200px; height:75px; object-fit:contain;"></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-success px-4"><i class="ri-save-line me-1"></i>Enregistrer les modifications</button>
                                <a href="{{ route('admin.producteurs.show', $producteur) }}" class="btn btn-secondary px-4"><i class="ri-arrow-go-back-line me-1"></i>Annuler</a>
                            </div>
                        </form>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include("partials.wowdash-scripts")

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
// Signature pads
const canvasProducer = document.getElementById('signature-pad-producer');
const signaturePadProducer = new SignaturePad(canvasProducer, { backgroundColor: '#fff' });
document.getElementById('clear-signature-producer').onclick = function() {
    signaturePadProducer.clear();
};

const canvasAgent = document.getElementById('signature-pad-agent');
const signaturePadAgent = new SignaturePad(canvasAgent, { backgroundColor: '#fff' });
document.getElementById('clear-signature-agent').onclick = function() {
    signaturePadAgent.clear();
};

// Afficher les signatures existantes si elles existent
@if(isset($data['signature_producer']) && !empty($data['signature_producer']))
    const existingSignatureProducer = new Image();
    existingSignatureProducer.onload = function() {
        const ctx = canvasProducer.getContext('2d');
        ctx.drawImage(this, 0, 0, canvasProducer.width, canvasProducer.height);
    };
    existingSignatureProducer.src = "{{ asset('storage/' . $data['signature_producer']) }}";
@endif

@if(isset($data['signature_agent']) && !empty($data['signature_agent']))
    const existingSignatureAgent = new Image();
    existingSignatureAgent.onload = function() {
        const ctx = canvasAgent.getContext('2d');
        ctx.drawImage(this, 0, 0, canvasAgent.width, canvasAgent.height);
    };
    existingSignatureAgent.src = "{{ asset('storage/' . $data['signature_agent']) }}";
@endif

// Form submission
document.getElementById('fiche-enquete-form').addEventListener('submit', function(e) {
    console.log('Form submission triggered');
    
    // Ajouter les signatures si elles ne sont pas vides
    if (!signaturePadProducer.isEmpty()) {
        document.getElementById('signature-producer-input').value = signaturePadProducer.toDataURL();
        console.log('Producer signature added');
    }
    if (!signaturePadAgent.isEmpty()) {
        document.getElementById('signature-agent-input').value = signaturePadAgent.toDataURL();
        console.log('Agent signature added');
    }
    
    // Permettre la soumission du formulaire
    console.log('Form will be submitted');
    return true;
});
</script>
