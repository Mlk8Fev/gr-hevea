<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Coopérative - WowDash</title>
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
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Modifier la Coopérative</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('admin.cooperatives.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Liste des Coopératives
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Modifier</li>
            </ul>
        </div>
        <div class="card p-24 radius-12">
            <form action="{{ route('admin.cooperatives.update', $cooperative) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">Nom de la coopérative *</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $cooperative->nom) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
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
                        <label for="kilometrage" class="form-label">Kilométrage</label>
                        <input type="text" class="form-control" id="kilometrage" name="kilometrage" value="{{ old('kilometrage', $cooperative->kilometrage) }}">
                    </div>
                </div>
                <h5 class="mt-4">Données bancaires</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="compte_bancaire" class="form-label">Compte (12 chiffres) *</label>
                        <input type="text" class="form-control" id="compte_bancaire" name="compte_bancaire" value="{{ old('compte_bancaire', $cooperative->compte_bancaire) }}" required maxlength="12">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="code_banque" class="form-label">Code banque (5 chiffres) *</label>
                        <input type="text" class="form-control" id="code_banque" name="code_banque" value="{{ old('code_banque', $cooperative->code_banque) }}" required maxlength="5">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="code_guichet" class="form-label">Code guichet (5 chiffres) *</label>
                        <input type="text" class="form-control" id="code_guichet" name="code_guichet" value="{{ old('code_guichet', $cooperative->code_guichet) }}" required maxlength="5">
                    </div>
                    <div class="col-md-5 mb-3">
                        <label for="nom_cooperative_banque" class="form-label">Nom de la coopérative à la banque *</label>
                        <input type="text" class="form-control" id="nom_cooperative_banque" name="nom_cooperative_banque" value="{{ old('nom_cooperative_banque', $cooperative->nom_cooperative_banque) }}" required>
                    </div>
                </div>
                <h5 class="mt-4">Documents à uploader</h5>
                <div class="row">
                    @foreach($documentTypes as $key => $label)
                    <div class="col-md-6 mb-3">
                        <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                        @php
                            $doc = $cooperative->documents->where('type', $key)->first();
                        @endphp
                        @if($doc)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank">Document existant</a>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="{{ $key }}" name="{{ $key }}" accept=".pdf,image/*">
                    </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-success mt-3">Mettre à jour</button>
                <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-secondary mt-3">Annuler</a>
            </form>
        </div>
    </div>
</main>
@include('partials.wowdash-scripts')
</body>
</html> 