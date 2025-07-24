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
    <div class="navbar-header">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <button type="button" class="sidebar-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                        <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                    </button>
                    <button type="button" class="sidebar-mobile-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                    </button>
                    <form class="navbar-search" method="GET" action="{{ route('admin.cooperatives.index') }}">
                        <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                    <div class="dropdown">
                        <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                                </div>
                                <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('wowdash/images/avatar/avatar1.png') }}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm">
                            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ auth()->user()->full_name }}</h6>
                                    <span class="text-secondary-light fw-medium text-sm">{{ ucfirst(auth()->user()->role) }}</span>
                                </div>
                                <button type="button" class="hover-text-danger">
                                    <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon> 
                                </button>
                            </div>
                            <ul class="to-top-list">
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="#"> 
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> Mon Profil</a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="#"> 
                                    <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon> Paramètres</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3 w-100 border-0 bg-transparent"> 
                                        <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        <h6 class="text-lg fw-semibold mb-0">Documents de traçabilité</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            @foreach($documentTypes as $key => $label)
                                @php $doc = $cooperative->documents->where('type', $key)->first(); @endphp
                                <li class="list-group-item border text-secondary-light p-16 bg-base {{ !$loop->last ? 'border-bottom-0' : '' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($doc)
                                            <iconify-icon icon="solar:check-circle-bold" class="text-success text-xl"></iconify-icon>
                                        @else
                                            <iconify-icon icon="solar:close-circle-bold" class="text-danger text-xl"></iconify-icon>
                                        @endif
                                        <span class="fw-semibold">{{ $label }}</span>
                                        @if($doc)
                                            <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-outline-primary btn-sm ms-2">Voir</a>
                                        @else
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