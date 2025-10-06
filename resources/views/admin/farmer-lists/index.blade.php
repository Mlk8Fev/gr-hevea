<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Lists - Gestion des Livraisons - WowDash</title>
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
                <h6 class="fw-semibold mb-0">Farmer Lists - Gestion des Livraisons</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">Farmer Lists</li>
                </ul>
            </div>

            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filtres de recherche -->
            <div class="row mb-24">
                <div class="col-12">
                    <div class="card p-24 radius-12 border-0 shadow-sm">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <!-- Recherche manuelle -->
                            <div class="flex-grow-1">
                                <div class="position-relative">
                                    <input type="text" 
                                           id="searchInput" 
                                           class="form-control" 
                                           placeholder="Rechercher par numéro de livraison..." 
                                           value="{{ request('search') }}"
                                           autocomplete="off">
                                    <iconify-icon icon="ri:search-line" class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></iconify-icon>
                                </div>
                            </div>
                            
                            <!-- Bouton Rechercher -->
                            <button type="button" id="searchButton" class="btn btn-primary">
                                <iconify-icon icon="ri:search-line" class="me-1"></iconify-icon>
                                Rechercher
                            </button>
                            
                            <!-- Filtre par secteur -->
                            @if(auth()->check() && auth()->user()->role === 'agc')
                                <!-- AGC : Secteur fixe, pas de filtre -->
                                <div style="min-width: 200px;">
                                    <select class="form-select" disabled>
                                        <option value="{{ auth()->user()->secteur_id }}" selected>
                                            {{ auth()->user()->secteur_code }} - {{ \App\Models\Secteur::where('code', auth()->user()->secteur)->first()->nom ?? 'Secteur' }}
                                        </option>
                                    </select>
                                    <small class="text-muted">Votre secteur</small>
                                </div>
                            @else
                                <!-- Admin/Super-admin : Filtre complet -->
                            <div style="min-width: 200px;">
                                <select id="secteurFilter" class="form-select">
                                    <option value="">Tous les secteurs</option>
                                    @foreach($secteurs as $secteur)
                                        <option value="{{ $secteur->id }}" {{ request('secteur') == $secteur->id ? 'selected' : '' }}>
                                            {{ $secteur->code }} - {{ $secteur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                            <!-- Filtre par coopérative -->
                            <div style="min-width: 200px;">
                                <select id="cooperativeFilter" class="form-select">
                                    <option value="">Toutes les coopératives</option>
                                    @foreach($cooperatives as $cooperative)
                                        @if(auth()->check() && auth()->user()->role === 'agc')
                                            @if($cooperative->secteur_id == auth()->user()->secteur_id)
                                                <option value="{{ $cooperative->id }}" {{ request('cooperative') == $cooperative->id ? 'selected' : '' }}>
                                                    {{ $cooperative->code }} - {{ $cooperative->nom }}
                                                </option>
                                            @endif
                                        @else
                                        <option value="{{ $cooperative->id }}" {{ request('cooperative') == $cooperative->id ? 'selected' : '' }}>
                                            {{ $cooperative->code }} - {{ $cooperative->nom }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Filtre par centre de collecte -->
                            <div style="min-width: 200px;">
                                <select id="centreCollecteFilter" class="form-select">
                                    <option value="">Tous les centres</option>
                                    @foreach($centresCollecte as $centre)
                                        @if(auth()->check() && auth()->user()->role === 'agc')
                                            @if($centre->secteur_id == auth()->user()->secteur_id)
                                                <option value="{{ $centre->id }}" {{ request('centre_collecte') == $centre->id ? 'selected' : '' }}>
                                                    {{ $centre->nom }}
                                                </option>
                                            @endif
                                        @else
                                        <option value="{{ $centre->id }}" {{ request('centre_collecte') == $centre->id ? 'selected' : '' }}>
                                            {{ $centre->nom }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Bouton reset -->
                            <button type="button" id="resetFilters" class="btn btn-outline-secondary">
                                <iconify-icon icon="ri:refresh-line" class="me-1"></iconify-icon>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des livraisons -->
            <div class="row">
                <div class="col-12">
                    <div class="card p-24 radius-12 border-0 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-20">
                            <h5 class="mb-0 d-flex align-items-center gap-2">
                                <iconify-icon icon="ri:file-list-2-line" class="text-primary"></iconify-icon>
                                Livraisons avec Farmer Lists
                            </h5>
                        </div>
                        
                        @if($livraisons->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">#</th>
                                            <th class="border-0">N° Livraison</th>
                                            <th class="border-0">Coopérative / Secteur</th>
                                            <th class="border-0">Centre de Collecte</th>
                                            <th class="border-0">Poids Net (kg)</th>
                                            <th class="border-0">Poids Farmer List (kg)</th>
                                            <th class="border-0">État / Date Livraison</th>
                                            <th class="border-0 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($livraisons as $index => $livraison)
                                        <tr>
                                            <td>{{ $livraisons->firstItem() + $index }}</td>
                                            <td>
                                                <div class="fw-semibold text-primary">{{ $livraison->numero_livraison }}</div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-semibold text-dark">{{ $livraison->cooperative->nom }}</div>
                                                    <div class="text-muted text-sm">
                                                        <span class="badge bg-info-100 text-info-600 px-6 py-1 radius-4 text-xs">
                                                            {{ $livraison->cooperative->secteur->code }} - {{ $livraison->cooperative->secteur->nom }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-medium text-secondary">{{ $livraison->centreCollecte->nom }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-success">{{ number_format($livraison->poids_net, 2) }} kg</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-info">{{ number_format($livraison->poids_total_farmer_list, 2) }} kg</span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $livraison->created_at->format('d/m/Y') }}</div>
                                                <div class="text-muted text-sm">
                                                    @if($livraison->farmer_list_complete)
                                                        <span class="badge bg-success-100 text-success-600 px-6 py-1 radius-4 text-xs">
                                                            <iconify-icon icon="ri:check-line" class="me-1"></iconify-icon>
                                                            Complète
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-100 text-warning-600 px-6 py-1 radius-4 text-xs">
                                                            <iconify-icon icon="ri:time-line" class="me-1"></iconify-icon>
                                                            Incomplète
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="{{ route('admin.farmer-lists.show', $livraison) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir">
                                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                                    </a>
                                                    @if(!$livraison->farmer_list_complete)
                                                        <a href="{{ route('admin.farmer-lists.create', $livraison) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Ajouter">
                                                            <iconify-icon icon="ri:add-line" class="menu-icon"></iconify-icon>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('admin.farmer-lists.pdf', $livraison) }}" class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="PDF" target="_blank">
                                                        <iconify-icon icon="ri:file-pdf-line" class="menu-icon"></iconify-icon>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination avec filtres préservés -->
                            @if($livraisons->hasPages())
                            <div class="row mt-24">
                                <div class="col-12">
                                    <div class="card p-24 radius-12 border-0 shadow-sm">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                            <!-- Informations de pagination -->
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted text-sm">
                                                    Affichage de 
                                                    <span class="fw-semibold text-primary">{{ $livraisons->firstItem() }}</span>
                                                    à 
                                                    <span class="fw-semibold text-primary">{{ $livraisons->lastItem() }}</span>
                                                    sur 
                                                    <span class="fw-semibold text-primary">{{ $livraisons->total() }}</span>
                                                    livraisons
                                                </span>
                                            </div>

                                            <!-- Navigation pagination -->
                                            <nav aria-label="Navigation des pages">
                                                <ul class="pagination pagination-sm mb-0">
                                                    <!-- Page précédente -->
                                                    @if($livraisons->onFirstPage())
                                                        <li class="page-item disabled">
                                                            <span class="page-link bg-light border-0 text-muted">
                                                                <iconify-icon icon="ri:arrow-left-s-line" class="text-xl"></iconify-icon>
                                                            </span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a href="{{ $livraisons->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                                <iconify-icon icon="ri:arrow-left-s-line" class="text-xl"></iconify-icon>
                                                            </a>
                                                        </li>
                                                    @endif

                                                    <!-- Pages numérotées -->
                                                    @php
                                                        $currentPage = $livraisons->currentPage();
                                                        $lastPage = $livraisons->lastPage();
                                                        $startPage = max(1, $currentPage - 2);
                                                        $endPage = min($lastPage, $currentPage + 2);
                                                    @endphp

                                                    <!-- Première page -->
                                                    @if($startPage > 1)
                                                        <li class="page-item">
                                                            <a href="{{ $livraisons->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
                                                        </li>
                                                        @if($startPage > 2)
                                                            <li class="page-item disabled">
                                                                <span class="page-link bg-light border-0 text-muted">...</span>
                                                            </li>
                                                        @endif
                                                    @endif

                                                    <!-- Pages autour de la page courante -->
                                                    @for($page = $startPage; $page <= $endPage; $page++)
                                                        @if($page == $currentPage)
                                                            <li class="page-item active">
                                                                <span class="page-link bg-primary border-0 text-white fw-semibold">
                                                                    {{ $page }}
                                                                </span>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a href="{{ $livraisons->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                                    {{ $page }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endfor

                                                    <!-- Dernière page -->
                                                    @if($endPage < $lastPage)
                                                        @if($endPage < $lastPage - 1)
                                                            <li class="page-item disabled">
                                                                <span class="page-link bg-light border-0 text-muted">...</span>
                                                            </li>
                                                        @endif
                                                        <li class="page-item">
                                                            <a href="{{ $livraisons->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                                {{ $lastPage }}
                                                            </a>
                                                        </li>
                                                    @endif

                                                    <!-- Page suivante -->
                                                    @if($livraisons->hasMorePages())
                                                        <li class="page-item">
                                                            <a href="{{ $livraisons->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                                <iconify-icon icon="ri:arrow-right-s-line" class="text-xl"></iconify-icon>
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li class="page-item disabled">
                                                            <span class="page-link bg-light border-0 text-muted">
                                                                <iconify-icon icon="ri:arrow-right-s-line" class="text-xl"></iconify-icon>
                                                            </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </nav>

                                            <!-- Sélecteur de nombre d'éléments par page -->
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted text-sm">Afficher :</span>
                                                <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <iconify-icon icon="ri-inbox-line" class="display-1 text-muted"></iconify-icon>
                                <h5 class="mt-3">Aucune livraison trouvée</h5>
                                <p class="text-muted">Aucune livraison validée ne correspond à vos critères de recherche.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.wowdash-scripts')

    <script>
    // Recherche manuelle avec bouton
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const secteurFilter = document.getElementById('secteurFilter');
    const cooperativeFilter = document.getElementById('cooperativeFilter');
    const centreCollecteFilter = document.getElementById('centreCollecteFilter');
    const resetFilters = document.getElementById('resetFilters');

    function performSearch() {
        const url = new URL(window.location);
        const searchValue = searchInput.value.trim();
        const secteurValue = secteurFilter.value;
        const cooperativeValue = cooperativeFilter.value;
        const centreCollecteValue = centreCollecteFilter.value;
        
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }
        
        if (secteurValue) {
            url.searchParams.set('secteur', secteurValue);
        } else {
            url.searchParams.delete('secteur');
        }
        
        if (cooperativeValue) {
            url.searchParams.set('cooperative', cooperativeValue);
        } else {
            url.searchParams.delete('cooperative');
        }
        
        if (centreCollecteValue) {
            url.searchParams.set('centre_collecte', centreCollecteValue);
        } else {
            url.searchParams.delete('centre_collecte');
        }
        
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    // Recherche au clic du bouton
    searchButton.addEventListener('click', performSearch);

    // Recherche avec Entrée
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Filtres immédiats
    secteurFilter.addEventListener('change', performSearch);
    cooperativeFilter.addEventListener('change', performSearch);
    centreCollecteFilter.addEventListener('change', performSearch);

    // Reset des filtres
    resetFilters.addEventListener('click', function() {
        searchInput.value = '';
        secteurFilter.value = '';
        cooperativeFilter.value = '';
        centreCollecteFilter.value = '';
        const url = new URL(window.location);
        url.searchParams.delete('search');
        url.searchParams.delete('secteur');
        url.searchParams.delete('cooperative');
        url.searchParams.delete('centre_collecte');
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });

    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
    </script>
</body>
</html> 