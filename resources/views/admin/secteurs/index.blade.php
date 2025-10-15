<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Secteurs - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
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
            <h6 class="fw-semibold mb-0">Gestion des Secteurs</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Gestion des Secteurs</li>
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
                                       placeholder="Rechercher par code ou nom..." 
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
                        
                        <!-- Bouton reset -->
                        <button type="button" id="resetFilters" class="btn btn-outline-secondary">
                            <i class="ri-refresh-line me-1"></i>
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
                            Liste des Secteurs
                        </h5>
                        <button type="button" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addSecteurModal">
                            <i class="ri-add-line icon text-xl line-height-1"></i>
                            Nouveau Secteur
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Code</th>
                                    <th class="border-0">Nom</th>
                                    <th class="border-0">Statistiques</th>
                                    <th class="border-0 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($secteurs as $index => $secteur)
                                <tr>
                                    <td>{{ $secteurs->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $secteur->code }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-secondary">{{ $secteur->nom }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                                                <i class="ri-building-line me-1"></i>
                                                {{ $secteur->cooperatives_count ?? 0 }} Coopératives
                                            </span>
                                            <span class="badge bg-info-100 text-info-600 px-8 py-2 radius-6">
                                                <i class="ri-user-line me-1"></i>
                                                {{ $secteur->producteurs_count ?? 0 }} Producteurs
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <button type="button" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle edit-secteur-btn" 
                                                    data-secteur="{{ $secteur->id }}"
                                                    data-code="{{ $secteur->code }}"
                                                    data-nom="{{ $secteur->nom }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editSecteurModal"
                                                    title="Modifier">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <form action="{{ route('admin.secteurs.destroy', $secteur) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce secteur ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Supprimer">
                                                    <i class="ri-delete-bin-line"></i>
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
                    @if($secteurs->hasPages())
                    <div class="row mt-24">
                        <div class="col-12">
                            <div class="card p-24 radius-12 border-0 shadow-sm">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <!-- Informations de pagination -->
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted text-sm">
                                            Affichage de 
                                            <span class="fw-semibold text-primary">{{ $secteurs->firstItem() }}</span>
                                            à 
                                            <span class="fw-semibold text-primary">{{ $secteurs->lastItem() }}</span>
                                            sur 
                                            <span class="fw-semibold text-primary">{{ $secteurs->total() }}</span>
                                            secteurs
                                        </span>
                                    </div>

                                    <!-- Navigation pagination intelligente -->
                                    <nav aria-label="Navigation des pages">
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Page précédente --}}
                                            @if($secteurs->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link bg-light border-0 text-muted">
                                                        Précédent
                                                    </span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a href="{{ $secteurs->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                        Précédent
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Pages intelligentes --}}
                                            @php
                                                $currentPage = $secteurs->currentPage();
                                                $lastPage = $secteurs->lastPage();
                                                $startPage = max(1, $currentPage - 2);
                                                $endPage = min($lastPage, $currentPage + 2);
                                            @endphp

                                            {{-- Première page --}}
                                            @if($startPage > 1)
                                                <li class="page-item">
                                                    <a href="{{ $secteurs->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
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
                                                        <a href="{{ $secteurs->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $page }}</a>
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
                                                    <a href="{{ $secteurs->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $lastPage }}</a>
                                                </li>
                                            @endif

                                            {{-- Page suivante --}}
                                            @if($secteurs->hasMorePages())
                                                <li class="page-item">
                                                    <a href="{{ $secteurs->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
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

<!-- Modal Ajouter Secteur -->
<div class="modal fade" id="addSecteurModal" tabindex="-1" aria-labelledby="addSecteurModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSecteurModalLabel">
                    <i class="ri-building-add-line me-2"></i>Ajouter un Secteur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.secteurs.store') }}" method="POST" id="addSecteurForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code du Secteur *</label>
                        <input type="text" class="form-control" id="code" name="code" required maxlength="10" placeholder="Ex: AB01">
                        <div class="form-text">Le code sera automatiquement converti en majuscules.</div>
                    </div>
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du Secteur *</label>
                        <input type="text" class="form-control" id="nom" name="nom" required placeholder="Ex: Abengourou">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Secteur -->
<div class="modal fade" id="editSecteurModal" tabindex="-1" aria-labelledby="editSecteurModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSecteurModalLabel">
                    <i class="ri-building-line me-2"></i>Modifier le Secteur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSecteurForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">Code du Secteur *</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required maxlength="10">
                        <div class="form-text">Le code sera automatiquement converti en majuscules.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom du Secteur *</label>
                        <input type="text" class="form-control" id="edit_nom" name="nom" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

// Script pour le modal d'édition
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-secteur-btn');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const secteurId = this.getAttribute('data-secteur');
            const code = this.getAttribute('data-code');
            const nom = this.getAttribute('data-nom');
            
            // Mettre à jour le formulaire d'édition
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_nom').value = nom;
            document.getElementById('editSecteurForm').action = `/admin/secteurs/${secteurId}`;
        });
    });
});
</script>

</body>
</html> 