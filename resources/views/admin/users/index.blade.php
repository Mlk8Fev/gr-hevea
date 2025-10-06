<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar')

<main class="dashboard-main">
    @include('partials.navbar-header')

    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Gestion des Utilisateurs</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Gestion des Utilisateurs</li>
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
                                       placeholder="Rechercher par nom, email ou rôle..." 
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
                            <iconify-icon icon="ri:user-settings-line" class="text-primary"></iconify-icon>
                            Liste des Utilisateurs
                        </h5>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                            <iconify-icon icon="ri:add-line" class="icon text-xl line-height-1"></iconify-icon>
                            Nouvel Utilisateur
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Utilisateur</th>
                                    <th class="border-0">Rôle</th>
                                    <th class="border-0">Secteur</th>
                                    <th class="border-0">Fonction</th>
                                    <th class="border-0">Statut</th>
                                    <th class="border-0 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="w-40-px h-40-px radius-12 d-flex justify-content-center align-items-center bg-primary-100">
                                                <iconify-icon icon="ri:user-3-line" class="text-primary text-lg"></iconify-icon>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $user->full_name }}</div>
                                                <div class="text-muted text-sm">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'superadmin' => 'danger',
                                                'admin' => 'warning', 
                                                'manager' => 'info',
                                                'agc' => 'success'
                                            ];
                                            $roleLabels = [
                                                'superadmin' => 'Super Admin',
                                                'admin' => 'Admin',
                                                'manager' => 'Manager',
                                                'agc' => 'Agent Gestion Qualité'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}-100 text-{{ $roleColors[$user->role] ?? 'secondary' }}-600 px-8 py-2 radius-6">
                                            {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->secteur)
                                            <span class="badge bg-info-100 text-info-600 px-8 py-2 radius-6">
                                                <iconify-icon icon="ri:building-line" class="me-1"></iconify-icon>
                                                {{ $user->secteur }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->fonction)
                                            <span class="fw-medium text-secondary">{{ $user->fonction->nom }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->status === 'active')
                                            <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                                                <iconify-icon icon="ri:check-line" class="me-1"></iconify-icon>
                                                Actif
                                            </span>
                                        @else
                                            <span class="badge bg-danger-100 text-danger-600 px-8 py-2 radius-6">
                                                <iconify-icon icon="ri:close-line" class="me-1"></iconify-icon>
                                                Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <!-- Bouton Activer/Désactiver -->
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-{{ $user->status === 'active' ? 'warning' : 'success' }}-focus text-{{ $user->status === 'active' ? 'warning' : 'success' }}-600 bg-hover-{{ $user->status === 'active' ? 'warning' : 'success' }}-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" 
                                                        title="{{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                                    <iconify-icon icon="ri:{{ $user->status === 'active' ? 'pause' : 'play' }}-line" class="menu-icon"></iconify-icon>
                                                </button>
                                            </form>

                                            <!-- Bouton Éditer -->
                                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Éditer">
                                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                            </a>

                                            <!-- Bouton Supprimer -->
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
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
                    @if($users->hasPages())
                    <div class="row mt-24">
                        <div class="col-12">
                            <div class="card p-24 radius-12 border-0 shadow-sm">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <!-- Informations de pagination -->
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted text-sm">
                                            Affichage de 
                                            <span class="fw-semibold text-primary">{{ $users->firstItem() }}</span>
                                            à 
                                            <span class="fw-semibold text-primary">{{ $users->lastItem() }}</span>
                                            sur 
                                            <span class="fw-semibold text-primary">{{ $users->total() }}</span>
                                            utilisateurs
                                        </span>
                                    </div>

                                    <!-- Navigation pagination intelligente -->
                                    <nav aria-label="Navigation des pages">
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Page précédente --}}
                                            @if($users->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link bg-light border-0 text-muted">
                                                        <iconify-icon icon="ri:arrow-left-s-line" class="text-xl"></iconify-icon>
                                                    </span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ $users->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                        <iconify-icon icon="ri:arrow-left-s-line" class="text-xl"></iconify-icon>
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Pages intelligentes --}}
                                            @php
                                                $currentPage = $users->currentPage();
                                                $lastPage = $users->lastPage();
                                                $startPage = max(1, $currentPage - 2);
                                                $endPage = min($lastPage, $currentPage + 2);
                                            @endphp

                                            {{-- Première page --}}
                                            @if($startPage > 1)
                                                <li class="page-item">
                                                    <a href="{{ $users->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
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
                                                        <a href="{{ $users->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $page }}</a>
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
                                                    <a href="{{ $users->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $lastPage }}</a>
                                                </li>
                                            @endif

                                            {{-- Page suivante --}}
                                            @if($users->hasMorePages())
                                                <li class="page-item">
                                                    <a href="{{ $users->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
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
const resetFilters = document.getElementById('resetFilters');

function performSearch() {
    const url = new URL(window.location);
    const searchValue = searchInput.value.trim();
    
    if (searchValue) {
        url.searchParams.set('search', searchValue);
    } else {
        url.searchParams.delete('search');
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

// Reset des filtres
resetFilters.addEventListener('click', function() {
    searchInput.value = '';
    const url = new URL(window.location);
    url.searchParams.delete('search');
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