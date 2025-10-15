<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Finance - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar', ['navigation' => $navigation])
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Section Finance</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Finance</li>
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
                                       placeholder="Rechercher par numéro de ticket, livraison, coopérative..." 
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

                        <!-- Filtre par coopérative -->
                        <div style="min-width: 200px;">
                            <input type="text" id="cooperativeFilter" class="form-control" placeholder="Tapez le nom de la coopérative..." list="cooperatives-list" value="{{ request('cooperative') ? ($cooperatives->find(request('cooperative'))->code ?? '') . ' - ' . ($cooperatives->find(request('cooperative'))->nom ?? '') : '' }}">
                            <datalist id="cooperatives-list">
                                @foreach($cooperatives as $cooperative)
                                    <option value="{{ $cooperative->code }} - {{ $cooperative->nom }}" data-id="{{ $cooperative->id }}">
                                @endforeach
                            </datalist>
                            <input type="hidden" id="cooperativeFilterHidden" name="cooperative" value="{{ request('cooperative') }}">
                        </div>
                        
                        <!-- Filtre par statut ENE -->
                        <div style="min-width: 180px;">
                            <select id="statutEneFilter" class="form-select">
                                <option value="all" {{ request('statut_ene') == 'all' ? 'selected' : '' }}>Tous les statuts ENE</option>
                                @foreach($statutsEne as $key => $label)
                                    <option value="{{ $key }}" {{ request('statut_ene') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                        </select>
                        </div>
                        
                        <!-- Filtre par date -->
                        <div style="min-width: 150px;">
                            <input type="date" 
                                   id="dateFilter" 
                                   class="form-control" 
                                   value="{{ request('date') }}"
                                   placeholder="Date de création">
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2">
                            <button type="button" id="searchButton" class="btn btn-primary">
                                <i class="ri-search-line me-1"></i>
                                Rechercher
                            </button>
                            <button type="button" id="resetFilters" class="btn btn-outline-secondary">
                                <i class="ri-search-line me-1"></i>
                                Reset
                            </button>
                        </div>
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
                            Tickets Validés pour Paiement
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                                <i class="ri-search-line me-1"></i>
                                {{ $ticketsValides->total() }} tickets
                            </span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Numéro Ticket</th>
                                    <th class="border-0">Coopérative / Secteur</th>
                                    <th class="border-0">Poids Net (kg)</th>
                                    <th class="border-0">Prix (FCFA)</th>
                                    <th class="border-0">Statut ENE</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticketsAvecPrix as $index => $item)
                                @php
                                    $ticket = $item['ticket'];
                                    $prix = $item['prix'] ?? null;
                                @endphp
                                <tr>
                                    <td>{{ $ticketsValides->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="w-40-px h-40-px radius-12 d-flex justify-content-center align-items-center bg-primary-100">
                                                <i class="ri-eye-line text-primary text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-primary">{{ $ticket->numero_ticket }}</div>
                                                <div class="text-muted text-sm">{{ $ticket->numero_livraison }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $ticket->connaissement->cooperative->nom }}</div>
                                            <div class="text-muted text-sm">
                                                <span class="badge bg-info-100 text-info-600 px-6 py-1 radius-4 text-xs">
                                                    {{ $ticket->connaissement->secteur->code }} - {{ $ticket->connaissement->secteur->nom }}
                                            </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">{{ number_format($ticket->poids_net, 2) }} kg</span>
                                    </td>
                                    <td>
                                        @if($prix)
                                            <span class="fw-semibold text-success">{{ number_format($prix['prix_final_total'], 0) }} FCFA</span>
                                        @else
                                            <span class="text-muted">Erreur calcul</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->statut_ene === 'valide_par_ene')
                                            <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                                                <i class="ri-search-line me-1"></i>
                                                Validé par ENE
                                            </span>
                                        @elseif($ticket->statut_ene === 'rejete_par_ene')
                                            <span class="badge bg-danger-100 text-danger-600 px-8 py-2 radius-6">
                                                <i class="ri-search-line me-1"></i>
                                                Rejeté par ENE
                                            </span>
                                        @else
                                            <span class="badge bg-warning-100 text-warning-600 px-8 py-2 radius-6">
                                                <i class="ri-search-line me-1"></i>
                                                En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ticket->created_at->format('d/m/Y') }}</div>
                                        <div class="text-muted text-sm">{{ $ticket->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="{{ route('admin.finance.show-calcul', $ticket->id) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir calcul">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </div>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination avec filtres préservés -->
                    @if($ticketsValides->hasPages())
                    <div class="row mt-24">
                        <div class="col-12">
                            <div class="card p-24 radius-12 border-0 shadow-sm">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <!-- Informations de pagination -->
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted text-sm">
                                            Affichage de 
                                            <span class="fw-semibold text-primary">{{ $ticketsValides->firstItem() }}</span>
                                            à 
                                            <span class="fw-semibold text-primary">{{ $ticketsValides->lastItem() }}</span>
                                            sur 
                                            <span class="fw-semibold text-primary">{{ $ticketsValides->total() }}</span>
                                            tickets
                                        </span>
                                    </div>

                                    <!-- Navigation pagination -->
                                    <nav aria-label="Navigation des pages">
                                        <ul class="pagination pagination-sm mb-0">
                                            <!-- Page précédente -->
                                    @if($ticketsValides->onFirstPage())
                                        <li class="page-item disabled">
                                                    <span class="page-link bg-light border-0 text-muted">
                                                        Précédent
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                                    <a href="{{ $ticketsValides->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                        Précédent
                                            </a>
                                        </li>
                                    @endif

                                            <!-- Pages intelligentes -->
                                            @php
                                                $currentPage = $ticketsValides->currentPage();
                                                $lastPage = $ticketsValides->lastPage();
                                                $startPage = max(1, $currentPage - 2);
                                                $endPage = min($lastPage, $currentPage + 2);
                                            @endphp

                                            <!-- Première page -->
                                            @if($startPage > 1)
                                                <li class="page-item">
                                                    <a href="{{ $ticketsValides->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
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
                                                        <span class="page-link bg-primary border-0 text-white fw-semibold">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                        <a href="{{ $ticketsValides->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $page }}</a>
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
                                                    <a href="{{ $ticketsValides->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $lastPage }}</a>
                                            </li>
                                        @endif

                                            <!-- Page suivante -->
                                    @if($ticketsValides->hasMorePages())
                                        <li class="page-item">
                                                    <a href="{{ $ticketsValides->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
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
const cooperativeFilter = document.getElementById('cooperativeFilter');
const statutEneFilter = document.getElementById('statutEneFilter');
const dateFilter = document.getElementById('dateFilter');
const resetFilters = document.getElementById('resetFilters');

function performSearch() {
    const url = new URL(window.location);
    const searchValue = searchInput.value.trim();
    const secteurValue = secteurFilter.value;
    const cooperativeValue = document.getElementById('cooperativeFilterHidden').value;
    const statutEneValue = statutEneFilter.value;
    const dateValue = dateFilter.value;
    
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
    
    if (statutEneValue && statutEneValue !== 'all') {
        url.searchParams.set('statut_ene', statutEneValue);
    } else {
        url.searchParams.delete('statut_ene');
    }
    
    if (dateValue) {
        url.searchParams.set('date', dateValue);
    } else {
        url.searchParams.delete('date');
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

// Gérer la sélection de coopérative avec datalist
cooperativeFilter.addEventListener('input', function() {
    const input = this;
    const value = input.value;
    const datalist = document.getElementById('cooperatives-list');
    const hiddenInput = document.getElementById('cooperativeFilterHidden');
    
    // Trouver l'option correspondante
    const option = datalist.querySelector(`option[value="${value}"]`);
    if (option) {
        hiddenInput.value = option.getAttribute('data-id');
    } else {
        hiddenInput.value = '';
    }
});

// Filtres immédiats
secteurFilter.addEventListener('change', performSearch);
statutEneFilter.addEventListener('change', performSearch);
dateFilter.addEventListener('change', performSearch);

// Reset des filtres
resetFilters.addEventListener('click', function() {
    searchInput.value = '';
    secteurFilter.value = '';
    cooperativeFilter.value = '';
    document.getElementById('cooperativeFilterHidden').value = '';
    statutEneFilter.value = 'all';
    dateFilter.value = '';
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('secteur');
    url.searchParams.delete('cooperative');
    url.searchParams.delete('statut_ene');
    url.searchParams.delete('date');
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