<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Producteurs - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar', ['navigation' => $navigation])
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Gestion des Producteurs</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Gestion des Producteurs</li>
            </ul>
        </div>
        
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
                                       placeholder="Rechercher par nom, code, secteur ou coopérative..." 
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
                        
                        <!-- Bouton reset -->
                        <button type="button" id="resetFilters" class="btn btn-outline-secondary">
                            <iconify-icon icon="ri:refresh-line" class="me-1"></iconify-icon>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau simplifié -->
        <div class="row">
            <div class="col-12">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-20">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <iconify-icon icon="ri:user-3-line" class="text-primary"></iconify-icon>
                            Liste des Producteurs
                        </h5>
                <a href="{{ route('admin.producteurs.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                            <iconify-icon icon="ri:add-line" class="icon text-xl line-height-1"></iconify-icon>
                            Nouveau Producteur
                </a>
            </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Producteur</th>
                                    <th class="border-0">Contact</th>
                                    <th class="border-0">Secteur</th>
                                    <th class="border-0">Coopératives</th>
                                    <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($producteurs as $index => $producteur)
                            <tr>
                                    <td>{{ $producteurs->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="w-40-px h-40-px radius-12 d-flex justify-content-center align-items-center bg-primary-100">
                                                <iconify-icon icon="ri:user-3-line" class="text-primary text-lg"></iconify-icon>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $producteur->nom }} {{ $producteur->prenom }}</div>
                                                <div class="text-muted text-sm">{{ $producteur->code_fphci }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-secondary">{{ $producteur->contact }}</span>
                                    </td>
                                    <td>
                                        @if($producteur->secteur)
                                            <span class="badge bg-info-100 text-info-600 px-8 py-2 radius-6">
                                                <iconify-icon icon="ri:building-line" class="me-1"></iconify-icon>
                                                {{ $producteur->secteur->code }} - {{ $producteur->secteur->nom }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($producteur->cooperatives->count() > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($producteur->cooperatives->take(2) as $coop)
                                                    <span class="badge bg-success-100 text-success-600 px-6 py-1 radius-4 text-xs">
                                                        {{ $coop->code }}
                                                    </span>
                                    @endforeach
                                                @if($producteur->cooperatives->count() > 2)
                                                    <span class="badge bg-secondary-100 text-secondary-600 px-6 py-1 radius-4 text-xs">
                                                        +{{ $producteur->cooperatives->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="{{ route('admin.producteurs.show', $producteur) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.producteurs.edit', $producteur) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Modifier">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                            <form action="{{ route('admin.producteurs.destroy', $producteur) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce producteur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Supprimer">
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    
                    <!-- Pagination avec filtres préservés -->
                    @if($producteurs->hasPages())
                    <div class="row mt-24">
                        <div class="col-12">
                            <div class="card p-24 radius-12 border-0 shadow-sm">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <!-- Informations de pagination -->
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted text-sm">
                                            Affichage de 
                                            <span class="fw-semibold text-primary">{{ $producteurs->firstItem() }}</span>
                                            à 
                                            <span class="fw-semibold text-primary">{{ $producteurs->lastItem() }}</span>
                                            sur 
                                            <span class="fw-semibold text-primary">{{ $producteurs->total() }}</span>
                                            producteurs
                                        </span>
                                    </div>

                                    <!-- Navigation pagination -->
                                    <nav aria-label="Navigation des pages">
                                        <ul class="pagination pagination-sm mb-0">
                                            <!-- Page précédente -->
                                            @if($producteurs->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link bg-light border-0 text-muted">
                                                        <iconify-icon icon="ri:arrow-left-s-line" class="text-xl"></iconify-icon>
                                                    </span>
                                                </li>
                                            @else
                        <li class="page-item">
                                                    <a href="{{ $producteurs->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                        <iconify-icon icon="ri:arrow-left-s-line" class="text-xl"></iconify-icon>
                            </a>
                        </li>
                                            @endif

                                            <!-- Pages numérotées -->
                                            @foreach($producteurs->getUrlRange(1, $producteurs->lastPage()) as $page => $url)
                                                @if($page == $producteurs->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link bg-primary border-0 text-white fw-semibold">
                                                            {{ $page }}
                                                        </span>
                                                    </li>
                                                @else
                        <li class="page-item">
                                                        <a href="{{ $producteurs->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                            {{ $page }}
                                                        </a>
                        </li>
                                                @endif
                                            @endforeach

                                            <!-- Page suivante -->
                                            @if($producteurs->hasMorePages())
                        <li class="page-item">
                                                    <a href="{{ $producteurs->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
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
const resetFilters = document.getElementById('resetFilters');

function performSearch() {
    const url = new URL(window.location);
    const searchValue = searchInput.value.trim();
    const secteurValue = secteurFilter.value;
    
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

// Filtre secteur immédiat
secteurFilter.addEventListener('change', performSearch);

// Reset des filtres
resetFilters.addEventListener('click', function() {
    searchInput.value = '';
    secteurFilter.value = '';
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('secteur');
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