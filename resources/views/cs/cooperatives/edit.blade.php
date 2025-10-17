<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Coopérative - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar')
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Modifier la Coopérative</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('cs.cooperatives.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Liste des Coopératives
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Modifier</li>
            </ul>
        </div>
        <div class="card p-24 radius-12">
            <form action="{{ route('cs.cooperatives.update', $cooperative) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @if(in_array(auth()->user()->role, ['agc', 'cs']))
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>Information :</strong> En tant qu'{{ auth()->user()->role === 'agc' ? 'AGC' : 'Chef Secteur' }}, vous ne pouvez modifier que les documents de traçabilité de cette coopérative.
                    </div>
                @endif
                <div class="row gy-3">
                    @if(!in_array(auth()->user()->role, ['agc', 'cs']))
                    <div class="col-md-6">
                        <label for="code" class="form-label">Code de la coopérative *</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $cooperative->code) }}" required>
                        <div class="form-text">Exemple: AB01-COOP1, COT1-COOP1, etc.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom de la coopérative *</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $cooperative->nom) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="secteur_id" class="form-label">Secteur *</label>
                        <select class="form-select" id="secteur_id" name="secteur_id" required>
                            <option value="">Sélectionner un secteur</option>
                            @foreach($secteurs as $secteur)
                                <option value="{{ $secteur->id }}" {{ old('secteur_id', $cooperative->secteur_id) == $secteur->id ? 'selected' : '' }}>{{ $secteur->code }} - {{ $secteur->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="president" class="form-label">Nom du président *</label>
                        <input type="text" class="form-control" id="president" name="president" value="{{ old('president', $cooperative->president) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Contact (10 chiffres) *</label>
                        <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact', $cooperative->contact) }}" required maxlength="10">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="sigle" class="form-label">Sigle</label>
                        <input type="text" class="form-control" id="sigle" name="sigle" value="{{ old('sigle', $cooperative->sigle) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $cooperative->latitude) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $cooperative->longitude) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="a_sechoir" class="form-label">Séchoir</label>
                        <select class="form-select" id="a_sechoir" name="a_sechoir">
                            <option value="0" {{ old('a_sechoir', $cooperative->a_sechoir) == 0 ? 'selected' : '' }}>Non</option>
                            <option value="1" {{ old('a_sechoir', $cooperative->a_sechoir) == 1 ? 'selected' : '' }}>Oui</option>
                        </select>
                        <div class="form-text">La coopérative dispose-t-elle d'un séchoir ?</div>
                    </div>
                </div>
                @endif
                
                @if(auth()->user()->role !== 'agc')
                <!-- Section des distances -->
                <h5 class="mt-4">
                    <i class="ri-eye-line me-2 text-info"></i> 
                    Distances vers les centres de collecte (en km)
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="distance_cotraf" class="form-label">Usine COTRAF *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_cotraf" name="distances[cotraf]" 
                               value="{{ old('distances.cotraf', $distances['COT1'] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="distance_duekoue" class="form-label">Duekoué *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_duekoue" name="distances[duekoue]" 
                               value="{{ old('distances.duekoue', $distances['DUEK'] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="distance_guiglo" class="form-label">Guiglo *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_guiglo" name="distances[guiglo]" 
                               value="{{ old('distances.guiglo', $distances['GUIG'] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="distance_divo" class="form-label">Divo *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_divo" name="distances[divo]" 
                               value="{{ old('distances.divo', $distances['DIVO'] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="distance_abengourou" class="form-label">Abengourou *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_abengourou" name="distances[abengourou]" 
                               value="{{ old('distances.abengourou', $distances['ABENG'] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="distance_meagui" class="form-label">Méagui *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_meagui" name="distances[meagui]" 
                               value="{{ old('distances.meagui', $distances['MEAG'] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="distance_cotraf_korhogo" class="form-label">COTRAF Korhogo *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_cotraf_korhogo" name="distances[cotraf_korhogo]" 
                               value="{{ old('distances.cotraf_korhogo', $distances['COT2'] ?? '') }}" required>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    <strong>Information :</strong> Ces distances seront utilisées pour calculer le coût de transport lors de la facturation.
                </div>
                @endif
                
                @if(auth()->user()->role !== 'agc')
                <h5 class="mt-4">Données bancaires</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="compte_bancaire" class="form-label">Compte (12 chiffres) *</label>
                        <input type="text" class="form-control" id="compte_bancaire" name="compte_bancaire" value="{{ old('compte_bancaire', $cooperative->compte_bancaire) }}" required maxlength="12">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="cle_rib" class="form-label">Clé RIB (2 chiffres) *</label>
                        <input type="text" class="form-control" id="cle_rib" name="cle_rib" value="{{ old('cle_rib', $cooperative->cle_rib ?? '00') }}" required maxlength="2" pattern="[0-9]{2}" title="La clé RIB doit contenir exactement 2 chiffres">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="code_banque" class="form-label">Code banque (5 caractères) *</label>
                        <input type="text" 
                               class="form-control" 
                               id="code_banque" 
                               name="code_banque" 
                               value="{{ old('code_banque', $cooperative->code_banque) }}" 
                               required 
                               maxlength="5"
                               pattern="[A-Z0-9]{5}"
                               title="Le code banque doit contenir exactement 5 caractères (lettres majuscules et chiffres)"
                               style="text-transform: uppercase;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="code_guichet" class="form-label">Code guichet (5 chiffres) *</label>
                        <input type="text" class="form-control" id="code_guichet" name="code_guichet" value="{{ old('code_guichet', $cooperative->code_guichet) }}" required maxlength="5">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom_cooperative_banque" class="form-label">Nom de la coopérative à la banque *</label>
                        <input type="text" class="form-control" id="nom_cooperative_banque" name="nom_cooperative_banque" value="{{ old('nom_cooperative_banque', $cooperative->nom_cooperative_banque) }}" required>
                    </div>
                </div>
                @endif
                <h5 class="mt-4">Documents à uploader</h5>
                <div class="row">
                    @foreach($documentTypes as $key => $label)
                    <div class="col-md-6 mb-3">
                        <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                        @php
                            $doc = $cooperative->documents->where('type', $key)->first();
                        @endphp
                        @if($doc)
                            <div class="mb-2 p-2 bg-success-focus border border-success-main radius-4">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="ri-eye-line text-success"></i>
                                    <span class="text-success-600 fw-medium">Document déjà fourni</span>
                                </div>
                                <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-outline-success btn-sm">
                                    <i class="ri-eye-line"></i>
                                    Voir le document
                                </a>
                                <small class="text-muted d-block mt-1">Uploadé le {{ $doc->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        @else
                            <div class="mb-2 p-2 bg-neutral-200 border border-neutral-400 radius-4">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-eye-line text-danger"></i>
                                    <span class="text-neutral-600">Document non fourni</span>
                                </div>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="{{ $key }}" name="{{ $key }}" accept=".pdf,image/*">
                        @if($doc)
                            <small class="form-text text-success">Un nouveau fichier remplacera l'ancien</small>
                        @endif
                    </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-success mt-3">Mettre à jour</button>
                <a href="{{ route('cs.cooperatives.index') }}" class="btn btn-secondary mt-3">Annuler</a>
            </form>
        </div>
    </div>
</main>
@include('partials.wowdash-scripts')
<script>
document.getElementById('code_banque').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>
</body>
</html> 