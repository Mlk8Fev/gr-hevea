<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de la Coopérative - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Profil de la Coopérative</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('admin.cooperatives.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Liste des Coopératives
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Profil</li>
            </ul>
        </div>
        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="card border radius-16 overflow-hidden bg-base h-100 p-0">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="text-center mb-16">
                            <h3 class="fw-bold text-primary mb-2">{{ $cooperative->nom }}</h3>
                            <span class="badge bg-secondary fs-6">{{ $cooperative->code }}</span>
                        </div>
                        <h6 class="text-lg fw-semibold mb-0">Informations principales</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:users-group-rounded-bold" class="text-xl text-primary"></iconify-icon></span>
                                    <span class="fw-semibold">Nom :</span> {{ $cooperative->nom }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:building-bold" class="text-xl text-info"></iconify-icon></span>
                                    <span class="fw-semibold">Secteur :</span> {{ $cooperative->secteur ? $cooperative->secteur->code . ' - ' . $cooperative->secteur->nom : '-' }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:user-bold" class="text-xl text-success"></iconify-icon></span>
                                    <span class="fw-semibold">Président :</span> {{ $cooperative->president }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:phone-bold" class="text-xl text-warning"></iconify-icon></span>
                                    <span class="fw-semibold">Contact :</span> {{ $cooperative->contact }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:hashtag-bold" class="text-xl text-primary"></iconify-icon></span>
                                    <span class="fw-semibold">Sigle :</span> {{ $cooperative->sigle }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:map-point-bold" class="text-xl text-danger"></iconify-icon></span>
                                    <span class="fw-semibold">GPS :</span> {{ $cooperative->latitude }}, {{ $cooperative->longitude }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:road-bold" class="text-xl text-secondary"></iconify-icon></span>
                                    <span class="fw-semibold">Kilométrage :</span> {{ $cooperative->kilometrage }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:fire-bold" class="text-xl text-warning"></iconify-icon></span>
                                    <span class="fw-semibold">Séchoir :</span> 
                                    @if($cooperative->a_sechoir)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        <span class="badge bg-secondary">Non</span>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">Données bancaires</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:bank-outline" class="text-xl text-success"></iconify-icon></span>
                                    <span class="fw-semibold">Compte :</span> {{ $cooperative->compte_bancaire }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:bank-outline" class="text-xl text-info"></iconify-icon></span>
                                    <span class="fw-semibold">Code banque :</span> {{ $cooperative->code_banque }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:bank-outline" class="text-xl text-warning"></iconify-icon></span>
                                    <span class="fw-semibold">Code guichet :</span> {{ $cooperative->code_guichet }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:bank-outline" class="text-xl text-primary"></iconify-icon></span>
                                    <span class="fw-semibold">Nom à la banque :</span> {{ $cooperative->nom_cooperative_banque }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="text-lg fw-semibold mb-0">Documents de traçabilité</h6>
                            <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="btn btn-warning btn-sm">
                                <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                Modifier les documents
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <iconify-icon icon="solar:info-circle-bold"></iconify-icon>
                                <strong>Information :</strong> Seuls les documents marqués d'une coche verte sont actuellement fournis. 
                                Cliquez sur "Modifier les documents" pour ajouter ou remplacer des documents.
                            </div>
                        </div>
                        <ul class="list-group radius-8">
                            @foreach($documentTypes as $key => $label)
                                @php $doc = $cooperative->documents->where('type', $key)->first(); @endphp
                                <li class="list-group-item border text-secondary-light p-16 bg-base {{ !$loop->last ? 'border-bottom-0' : '' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($doc)
                                            <iconify-icon icon="solar:check-circle-bold" class="text-success text-xl"></iconify-icon>
                                            <span class="fw-semibold">{{ $label }}</span>
                                            <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-outline-primary btn-sm ms-2">Voir</a>
                                        @else
                                            <iconify-icon icon="solar:close-circle-bold" class="text-danger text-xl"></iconify-icon>
                                            <span class="fw-semibold">{{ $label }}</span>
                                            <span class="text-muted ms-2">Non fourni</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="btn btn-warning px-4">Modifier</a>
                    <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-secondary px-4">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</main>
@include('partials.wowdash-scripts')
</body>
</html> 