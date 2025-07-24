<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Producteur - WowDash</title>
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
        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="card border radius-16 overflow-hidden bg-base h-100 p-0">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="text-center mb-16">
                            <h3 class="fw-bold text-primary mb-2">{{ $producteur->nom }} {{ $producteur->prenom }}</h3>
                            <span class="badge bg-secondary fs-6">{{ $producteur->code_fphci }}</span>
                        </div>
                        <h6 class="text-lg fw-semibold mb-0">Informations principales</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:building-bold" class="text-xl text-info"></iconify-icon></span>
                                    <span class="fw-semibold">Secteur :</span> {{ $producteur->secteur ? $producteur->secteur->code . ' - ' . $producteur->secteur->nom : '-' }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:phone-bold" class="text-xl text-warning"></iconify-icon></span>
                                    <span class="fw-semibold">Contact :</span> {{ $producteur->contact }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:road-bold" class="text-xl text-secondary"></iconify-icon></span>
                                    <span class="fw-semibold">Superficie totale :</span> {{ $producteur->superficie_totale }} ha
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:user-bold" class="text-xl text-success"></iconify-icon></span>
                                    <span class="fw-semibold">Genre :</span> {{ $producteur->genre }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">Coopératives</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            @foreach($producteur->cooperatives as $coop)
                            <li class="list-group-item border text-secondary-light p-16 bg-base {{ !$loop->last ? 'border-bottom-0' : '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:users-group-rounded-bold" class="text-xl text-primary"></iconify-icon></span>
                                    <span class="fw-semibold">{{ $coop->code }} - {{ $coop->nom }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">Documents de traçabilité</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            @foreach($documentTypes as $key => $label)
                                @php $doc = $producteur->documents->where('type', $key)->first(); @endphp
                                <li class="list-group-item border text-secondary-light p-16 bg-base {{ !$loop->last ? 'border-bottom-0' : '' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($doc && ($doc->fichier || $doc->data))
                                            <iconify-icon icon="solar:check-circle-bold" class="text-success text-xl"></iconify-icon>
                                        @else
                                            <iconify-icon icon="solar:close-circle-bold" class="text-danger text-xl"></iconify-icon>
                                        @endif
                                        <span class="fw-semibold">{{ $label }}</span>
                                        @if($doc && $key === 'lettre_engagement')
                                            <a href="{{ route('admin.admin.producteurs.documents.pdf', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" class="btn btn-outline-primary btn-sm ms-2" target="_blank">Voir</a>
                                            <a href="{{ route('admin.admin.producteurs.documents.pdf', ['producteur' => $producteur->id, 'document' => $doc->id]) }}?download=1" class="btn btn-outline-danger btn-sm ms-2">Télécharger PDF</a>
                                            <a href="{{ route('admin.producteurs.documents.edit', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" class="btn btn-outline-warning btn-sm ms-2">Modifier</a>
                                        @elseif($doc && $doc->fichier)
                                            <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-outline-primary btn-sm ms-2">Voir</a>
                                        @elseif(!$doc && $key === 'lettre_engagement')
                                            <a href="{{ route('admin.producteurs.documents.create', ['producteur' => $producteur->id, 'type' => $key]) }}" class="btn btn-outline-success btn-sm ms-2">Remplir digitalement</a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.producteurs.edit', $producteur) }}" class="btn btn-warning px-4">Modifier</a>
                    <a href="{{ route('admin.producteurs.index') }}" class="btn btn-secondary px-4">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</main>
@include('partials.wowdash-scripts')
</body>
</html> 