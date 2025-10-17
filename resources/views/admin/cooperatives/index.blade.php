<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Coopératives - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
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
            <h6 class="fw-semibold mb-0">Gestion des Coopératives</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Gestion des Coopératives</li>
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
                                       placeholder="Rechercher par nom, code ou sigle..." 
                                       value="{{ request('search') }}"
                                       autocomplete="off">
                                <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                            </div>
                        </div>
                        
                        <!-- Bouton Rechercher -->
                        <button type="button" id="searchButton" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>
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
                            <i class="ri-search-line me-1"></i>
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
                            <i class="ri-user-line text-primary"></i>
                            Liste des Coopératives
                        </h5>
                        @if(!in_array(auth()->user()->role, ['agc', 'cs']))
                        <a href="{{ route('admin.cooperatives.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                            <i class="ri-add-line icon text-xl line-height-1"></i>
                            Nouvelle Coopérative
                        </a>
                        @endif
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Code</th>
                                    <th class="border-0">Secteur</th>
                                    <th class="border-0">Sigle</th>
                                    <th class="border-0 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cooperatives as $index => $cooperative)
                                <tr>
                                    <td>{{ $cooperatives->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $cooperative->code }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-100 text-info-600 px-8 py-2 radius-6">
                                            {{ $cooperative->secteur ? $cooperative->secteur->code . ' - ' . $cooperative->secteur->nom : '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-secondary">{{ $cooperative->sigle ?: '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="{{ route('admin.cooperatives.show', $cooperative) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Modifier">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            @if(!in_array(auth()->user()->role, ['agc', 'cs']))
                                            <form action="{{ route('admin.cooperatives.destroy', $cooperative) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette coopérative ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Supprimer">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination avec filtres préservés -->
                    @if($cooperatives->hasPages())
                    <div class="row mt-24">
                        <div class="col-12">
                            <div class="card p-24 radius-12 border-0 shadow-sm">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <!-- Informations de pagination -->
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted text-sm">
                                            Affichage de 
                                            <span class="fw-semibold text-primary">{{ $cooperatives->firstItem() }}</span>
                                            à 
                                            <span class="fw-semibold text-primary">{{ $cooperatives->lastItem() }}</span>
                                            sur 
                                            <span class="fw-semibold text-primary">{{ $cooperatives->total() }}</span>
                                            coopératives
                                        </span>
                                    </div>

                                    <!-- Navigation pagination intelligente -->
                                    <nav aria-label="Navigation des pages">
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Page précédente --}}
                                            @if($cooperatives->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link bg-light border-0 text-muted">
                                                        Précédent
                                                    </span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ $cooperatives->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                        Précédent
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Pages intelligentes --}}
                                            @php
                                                $currentPage = $cooperatives->currentPage();
                                                $lastPage = $cooperatives->lastPage();
                                                $startPage = max(1, $currentPage - 2);
                                                $endPage = min($lastPage, $currentPage + 2);
                                            @endphp

                                            {{-- Première page --}}
                                            @if($startPage > 1)
                                                <li class="page-item">
                                                    <a href="{{ $cooperatives->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
                                                </li>
                                                @if($startPage > 2)
                                                    <li class="page-item disabled">
                                                        <span class="page-link bg-light border-0 text-muted">...</span>
                                                    </li>
                                                @endif
                                            @endif

                                            {{-- Pages autour de la page courante --}}
                                            @for($page = $startPage; $page <= $endPage; $page++)
                                                @if($page == $currentPage)
                                                    <li class="page-item active">
                                                        <span class="page-link bg-primary border-0 text-white fw-semibold">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a href="{{ $cooperatives->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            {{-- Dernière page --}}
                                            @if($endPage < $lastPage)
                                                @if($endPage < $lastPage - 1)
                                                    <li class="page-item disabled">
                                                        <span class="page-link bg-light border-0 text-muted">...</span>
                                                    </li>
                                                @endif
                                                <li class="page-item">
                                                    <a href="{{ $cooperatives->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $lastPage }}</a>
                                                </li>
                                            @endif

                                            {{-- Page suivante --}}
                                            @if($cooperatives->hasMorePages())
                                                <li class="page-item">
                                                    <a href="{{ $cooperatives->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                        Suivant
                                                    </a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link bg-light border-0 text-muted">
                                                        Suivant
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
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