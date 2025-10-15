<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation ENE CI - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/apexcharts.css') }}">
    <!-- Data Table css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/dataTables.min.css') }}">
    <!-- Text Editor css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor-katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor.atom-one-dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor.quill.snow.css') }}">
    <!-- Date picker css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/flatpickr.min.css') }}">
    <!-- Calendar css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/full-calendar.css') }}">
    <!-- Vector Map css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/jquery-jvectormap-2.0.5.css') }}">
    <!-- Popup css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/magnific-popup.css') }}">
    <!-- Slick Slider css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/slick.css') }}">
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/prism.css') }}">
    <!-- file upload css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/audioplayer.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar')

<main class="dashboard-main">
    @include('partials.navbar-header')

    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Validation ENE CI</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Validation ENE CI</li>
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

        <!-- Filtres -->
        <div class="card h-100 p-0 radius-12 mb-24">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Recherche manuelle -->
                    <div class="flex-grow-1">
                        <div class="position-relative">
                            <input type="text" 
                                   id="searchInput" 
                                   class="form-control" 
                                   placeholder="Rechercher par numéro de ticket, coopérative..." 
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                        </div>
                    </div>
                    
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
                    
                    <!-- Filtre par Statut ENE CI -->
                    <div style="min-width: 200px;">
                        <select id="statutEneFilter" class="form-select">
                            <option value="all" {{ $statutEne === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                            <option value="en_attente" {{ $statutEne === 'en_attente' ? 'selected' : '' }}>En attente ENE CI</option>
                            <option value="valide_par_ene" {{ $statutEne === 'valide_par_ene' ? 'selected' : '' }}>Validé pour facturation</option>
                            <option value="rejete_par_ene" {{ $statutEne === 'rejete_par_ene' ? 'selected' : '' }}>Rejeté par ENE CI</option>
                        </select>
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

        <!-- Onglets -->
        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                    <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    
                    <form class="navbar-search" method="GET" action="{{ route('admin.ene-validation.index') }}">
                        @if(request('statut_ene') && request('statut_ene') !== 'all')
                            <input type="hidden" name="statut_ene" value="{{ request('statut_ene') }}">
                        @endif
                        <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Rechercher..." value="{{ $search }}">
                        <i class="ri-search-line"></i>
                    </form>
                    
                    @if(request('search') || (request('statut_ene') && request('statut_ene') !== 'all'))
                        <a href="{{ route('admin.ene-validation.index') }}" class="btn btn-outline-secondary btn-sm px-12 py-6 radius-8 d-flex align-items-center gap-2">
                            <i class="ri-close-line"></i>
                            Effacer les filtres
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="tickets-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">N° Ticket</th>
                                <th scope="col">Coopérative</th>
                                <th scope="col">Poids Net / Prix Final</th>
                                <th scope="col">Montant Total</th>
                                <th scope="col">Statut ENE CI</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ticketsAvecPrix as $index => $item)
                                @php
                                    $ticket = $item['ticket'];
                                    $prix = $item['prix'];
                                @endphp
                                <tr>
                                    <td>{{ $tickets->firstItem() + $index }}</td>
                                    <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $ticket->numero_ticket }}</span></td>
                                    <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $ticket->connaissement->cooperative->nom }}</span></td>
                                    <td>
                                        <div>
                                            <div class="text-success fw-bold">{{ number_format($ticket->poids_net, 2) }} kg</div>
                                            @if($prix && !isset($prix['erreur']))
                                                <div class="text-primary fw-bold text-sm">{{ number_format($prix['details']['prix_final_public'], 2) }} FCFA</div>
                                            @else
                                                <div class="text-muted text-sm">Erreur calcul</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($prix && !isset($prix['erreur']))
                                            <span class="text-md mb-0 fw-normal text-success fw-bold">{{ number_format($prix['details']['montant_public'], 0) }} FCFA</span>
                                        @else
                                            <span class="text-sm text-muted">Erreur calcul</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->statut_ene === 'en_attente')
                                            <span class="badge bg-warning">En attente ENE CI</span>
                                        @elseif($ticket->statut_ene === 'valide_par_ene')
                                            <span class="badge bg-success">Validé pour facturation</span>
                                        @elseif($ticket->statut_ene === 'rejete_par_ene')
                                            <span class="badge bg-danger">Rejeté par ENE CI</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="{{ route('admin.ene-validation.show', $ticket->id) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Voir Détails">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-24">
                                        <div class="text-center">
                                            <i class="ri-inbox-line"></i>
                                            <h6 class="mt-3 text-muted">Aucun ticket trouvé</h6>
                                            <p class="text-muted mb-0">Aucun ticket ne correspond aux critères de recherche.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
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
                                                    Précédent
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a href="{{ $tickets->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                    Précédent
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
</main>

@include('partials.wowdash-scripts')

<script>
// Recherche manuelle avec bouton
const searchInput = document.getElementById('searchInput');
const searchButton = document.getElementById('searchButton');
const secteurFilter = document.getElementById('secteurFilter');
const cooperativeFilter = document.getElementById('cooperativeFilter');
const statutEneFilter = document.getElementById('statutEneFilter');
const resetFilters = document.getElementById('resetFilters');

function performSearch() {
    const url = new URL(window.location);
    const searchValue = searchInput.value.trim();
    const secteurValue = secteurFilter.value;
    const cooperativeValue = document.getElementById('cooperativeFilterHidden').value;
    const statutEneValue = statutEneFilter.value;
    
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

// Reset des filtres
resetFilters.addEventListener('click', function() {
    searchInput.value = '';
    secteurFilter.value = '';
    cooperativeFilter.value = '';
    document.getElementById('cooperativeFilterHidden').value = '';
    statutEneFilter.value = 'all';
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('secteur');
    url.searchParams.delete('cooperative');
    url.searchParams.delete('statut_ene');
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