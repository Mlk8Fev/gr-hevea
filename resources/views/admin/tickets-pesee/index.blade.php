<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tickets de Pesée - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
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
            <h6 class="fw-semibold mb-0">Gestion des Tickets de Pesée</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Tickets de Pesée</li>
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
                                       placeholder="Rechercher par numéro de livraison, ticket, origine, transporteur..." 
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
                        
                        <!-- Filtre par coopérative -->
                        <div style="min-width: 200px;">
                            <select id="cooperativeFilter" class="form-select">
                                <option value="">Toutes les coopératives</option>
                                @foreach($cooperatives as $cooperative)
                                    <option value="{{ $cooperative->id }}" {{ request('cooperative') == $cooperative->id ? 'selected' : '' }}>
                                        {{ $cooperative->code }} - {{ $cooperative->nom }}
                                    </option>
                                @endforeach
                        </select>
                    </div>
                        
                        <!-- Filtre par statut -->
                        <div style="min-width: 150px;">
                            <select id="statutFilter" class="form-select">
                                <option value="all" {{ request('statut') == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>
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
                            <iconify-icon icon="ri:file-text-line" class="text-primary"></iconify-icon>
                            Liste des Tickets de Pesée
                        </h5>
                        @if(auth()->check() && auth()->user()->role !== 'agc')
                            <a href="{{ route('admin.tickets-pesee.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                                <iconify-icon icon="ri:add-line" class="icon text-xl line-height-1"></iconify-icon>
                                Nouveau Ticket
                            </a>
                        @endif
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Numéro Ticket</th>
                                    <th class="border-0">Coopérative / Secteur</th>
                                    <th class="border-0">Poids Net (kg)</th>
                                    <th class="border-0">Date Création</th>
                                    <th class="border-0">Statut</th>
                                    <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $index => $ticket)
                            <tr>
                                <td>{{ $tickets->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="w-40-px h-40-px radius-12 d-flex justify-content-center align-items-center bg-primary-100">
                                                <iconify-icon icon="ri:file-text-line" class="text-primary text-lg"></iconify-icon>
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
                                        <div class="fw-semibold text-dark">{{ $ticket->created_at->format('d/m/Y') }}</div>
                                        <div class="text-muted text-sm">{{ $ticket->created_at->format('H:i') }}</div>
                                    </td>
                                <td>
                                    @if($ticket->statut === 'en_attente')
                                            <span class="badge bg-warning-100 text-warning-600 px-8 py-2 radius-6">
                                                <iconify-icon icon="ri:time-line" class="me-1"></iconify-icon>
                                                En attente
                                            </span>
                                    @elseif($ticket->statut === 'valide')
                                            <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                                                <iconify-icon icon="ri:check-line" class="me-1"></iconify-icon>
                                                Validé
                                            </span>
                                    @else
                                            <span class="badge bg-danger-100 text-danger-600 px-8 py-2 radius-6">
                                                <iconify-icon icon="ri:close-line" class="me-1"></iconify-icon>
                                                Annulé
                                            </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="{{ route('admin.tickets-pesee.show', $ticket) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>

                                            @if(auth()->check() && auth()->user()->role !== 'agc')
                                        <a href="{{ route('admin.tickets-pesee.edit', $ticket) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Modifier">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>

                                        @if($ticket->statut === 'en_attente')
                                                    <form action="{{ route('admin.tickets-pesee.validate', $ticket) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir valider ce ticket ?')">
                                                @csrf
                                                        <button type="submit" class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Valider">
                                                <iconify-icon icon="lucide:check" class="menu-icon"></iconify-icon>
                                                </button>
                                            </form>
                                        @endif

                                        @if($ticket->statut === 'valide')
                                                    <form action="{{ route('admin.tickets-pesee.cancel-validation', $ticket) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce ticket ?')">
                                                @csrf
                                                @method('PATCH')
                                                        <button type="submit" class="bg-warning-focus text-warning-600 bg-hover-warning-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Annuler validation">
                                                <iconify-icon icon="lucide:x" class="menu-icon"></iconify-icon>
                                                </button>
                                            </form>
                                        @endif

                                                @if($ticket->statut === 'en_attente')
                                                    <form action="{{ route('admin.tickets-pesee.destroy', $ticket) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket ?')">
                                                @csrf
                                                @method('DELETE')
                                                        <button type="submit" class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Supprimer">
                                                            <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                                </button>
                                            </form>
                                                @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                    <!-- Pagination avec filtres préservés -->
                @if($tickets->hasPages())
                    <div class="row mt-24">
                        <div class="col-12">
                            <div class="card p-24 radius-12 border-0 shadow-sm">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <!-- Informations de pagination -->
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted text-sm">
                                            Affichage de 
                                            <span class="fw-semibold text-primary">{{ $tickets->firstItem() }}</span>
                                            à 
                                            <span class="fw-semibold text-primary">{{ $tickets->lastItem() }}</span>
                                            sur 
                                            <span class="fw-semibold text-primary">{{ $tickets->total() }}</span>
                                            tickets
                                        </span>
                                    </div>

                                    <!-- Navigation pagination intelligente -->
                                    <nav aria-label="Navigation des pages">
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Page précédente --}}
                                            @if($tickets->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link bg-light border-0 text-muted">
                                                        <iconify-icon icon="ri:arrow-left-s-line" class="text-xl"></iconify-icon>
                                                    </span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ $tickets->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                        <iconify-icon icon="ri:arrow-left-s-line" class="text-xl"></iconify-icon>
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Pages intelligentes --}}
                                            @php
                                                $currentPage = $tickets->currentPage();
                                                $lastPage = $tickets->lastPage();
                                                $startPage = max(1, $currentPage - 2);
                                                $endPage = min($lastPage, $currentPage + 2);
                                            @endphp

                                            {{-- Première page --}}
                                            @if($startPage > 1)
                                                <li class="page-item">
                                                    <a href="{{ $tickets->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
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
                                                        <a href="{{ $tickets->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $page }}</a>
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
                                                    <a href="{{ $tickets->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $lastPage }}</a>
                                                </li>
                                            @endif

                                            {{-- Page suivante --}}
                                            @if($tickets->hasMorePages())
                                                <li class="page-item">
                                                    <a href="{{ $tickets->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
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
const cooperativeFilter = document.getElementById('cooperativeFilter');
const statutFilter = document.getElementById('statutFilter');
const dateFilter = document.getElementById('dateFilter');
const resetFilters = document.getElementById('resetFilters');

function performSearch() {
    const url = new URL(window.location);
    const searchValue = searchInput.value.trim();
    const secteurValue = secteurFilter.value;
    const cooperativeValue = cooperativeFilter.value;
    const statutValue = statutFilter.value;
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
    
    if (statutValue && statutValue !== 'all') {
        url.searchParams.set('statut', statutValue);
    } else {
        url.searchParams.delete('statut');
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

// Filtres immédiats
secteurFilter.addEventListener('change', performSearch);
cooperativeFilter.addEventListener('change', performSearch);
statutFilter.addEventListener('change', performSearch);
dateFilter.addEventListener('change', performSearch);

// Reset des filtres
resetFilters.addEventListener('click', function() {
    searchInput.value = '';
    secteurFilter.value = '';
    cooperativeFilter.value = '';
    statutFilter.value = 'all';
    dateFilter.value = '';
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('secteur');
    url.searchParams.delete('cooperative');
    url.searchParams.delete('statut');
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