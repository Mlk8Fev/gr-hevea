<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Coopérative - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar')
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <form action="{{ route('admin.cooperatives.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Ajouter une Coopérative</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="{{ route('admin.cooperatives.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Liste des Coopératives
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">Ajouter</li>
                </ul>
            </div>
            <div class="card p-24 radius-12 mb-24">
                <h5 class="card-title mb-4"><iconify-icon icon="solar:info-square-outline" class="me-2 text-primary"></iconify-icon> Informations générales</h5>
                <div class="row gy-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Code de la coopérative *</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
                        <div class="form-text">Exemple: AB01-COOP1, COT1-COOP1, etc.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom de la coopérative *</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="secteur_id" class="form-label">Secteur *</label>
                        <select class="form-select" id="secteur_id" name="secteur_id" required>
                            <option value="">Sélectionner un secteur</option>
                            @foreach($secteurs as $secteur)
                                <option value="{{ $secteur->id }}" {{ old('secteur_id') == $secteur->id ? 'selected' : '' }}>{{ $secteur->code }} - {{ $secteur->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="president" class="form-label">Nom du président *</label>
                        <input type="text" class="form-control" id="president" name="president" value="{{ old('president') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="contact" class="form-label">Contact (10 chiffres) *</label>
                        <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact') }}" required maxlength="10">
                    </div>
                    <div class="col-md-4">
                        <label for="sigle" class="form-label">Sigle</label>
                        <input type="text" class="form-control" id="sigle" name="sigle" value="{{ old('sigle') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="a_sechoir" class="form-label">Séchoir</label>
                        <select class="form-select" id="a_sechoir" name="a_sechoir">
                            <option value="0" {{ old('a_sechoir') == '0' ? 'selected' : '' }}>Non</option>
                            <option value="1" {{ old('a_sechoir') == '1' ? 'selected' : '' }}>Oui</option>
                        </select>
                        <div class="form-text">La coopérative dispose-t-elle d'un séchoir ?</div>
                    </div>
                </div>
            </div>
            
            <!-- Nouvelle section pour les distances -->
            <div class="card p-24 radius-12 mb-24">
                <h5 class="card-title mb-4">
                    <iconify-icon icon="solar:route-outline" class="me-2 text-info"></iconify-icon> 
                    Distances vers les centres de collecte (en km)
                </h5>
                <div class="row gy-3">
                    <div class="col-md-4">
                        <label for="distance_cotraf" class="form-label">Usine COTRAF *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_cotraf" name="distances[cotraf]" value="{{ old('distances.cotraf') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="distance_duekoue" class="form-label">Duekoué *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_duekoue" name="distances[duekoue]" value="{{ old('distances.duekoue') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="distance_guiglo" class="form-label">Guiglo *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_guiglo" name="distances[guiglo]" value="{{ old('distances.guiglo') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="distance_divo" class="form-label">Divo *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_divo" name="distances[divo]" value="{{ old('distances.divo') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="distance_abengourou" class="form-label">Abengourou *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_abengourou" name="distances[abengourou]" value="{{ old('distances.abengourou') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="distance_meagui" class="form-label">Méagui *</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="distance_meagui" name="distances[meagui]" value="{{ old('distances.meagui') }}" required>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="ri-information-line me-2"></i>
                    <strong>Information :</strong> Ces distances seront utilisées pour calculer le coût de transport lors de la facturation.
                </div>
            </div>
            
            <div class="card p-24 radius-12 mb-24">
                <h5 class="card-title mb-4"><iconify-icon icon="solar:bank-outline" class="me-2 text-success"></iconify-icon> Données bancaires</h5>
                <div class="row gy-3">
                    <div class="col-md-3">
                        <label for="compte_bancaire" class="form-label">Compte (12 chiffres) *</label>
                        <input type="text" class="form-control" id="compte_bancaire" name="compte_bancaire" value="{{ old('compte_bancaire') }}" required maxlength="12">
                    </div>
                    <div class="col-md-2">
                        <label for="code_banque" class="form-label">Code banque (5 chiffres) *</label>
                        <input type="text" class="form-control" id="code_banque" name="code_banque" value="{{ old('code_banque') }}" required maxlength="5">
                    </div>
                    <div class="col-md-2">
                        <label for="code_guichet" class="form-label">Code guichet (5 chiffres) *</label>
                        <input type="text" class="form-control" id="code_guichet" name="code_guichet" value="{{ old('code_guichet') }}" required maxlength="5">
                    </div>
                    <div class="col-md-5">
                        <label for="nom_cooperative_banque" class="form-label">Nom de la coopérative à la banque *</label>
                        <input type="text" class="form-control" id="nom_cooperative_banque" name="nom_cooperative_banque" value="{{ old('nom_cooperative_banque') }}" required>
                    </div>
                </div>
            </div>
            <div class="card p-24 radius-12 mb-24">
                <h5 class="card-title mb-4"><iconify-icon icon="solar:document-text-outline" class="me-2 text-warning"></iconify-icon> Documents de traçabilité</h5>
                <div class="row gy-3">
                    @foreach($documentTypes as $key => $label)
                    <div class="col-md-6">
                        <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                        <input type="file" class="form-control" id="{{ $key }}" name="{{ $key }}" accept=".pdf,image/*">
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success px-4">Créer la coopérative</button>
                <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</main>
@include('partials.wowdash-scripts')
</body>
</html> 