<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Centres de Transit - FPH-CI</title>
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
            <h6 class="fw-semibold mb-0">Gestion des Centres de Transit</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Gestion des Centres de Transit</li>
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
        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <span class="text-md fw-medium text-secondary-light mb-0">Afficher</span>
                    <form method="GET" action="{{ route('admin.centres-collecte.index') }}" class="d-inline">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 25) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                    <form class="navbar-search" method="GET" action="{{ route('admin.centres-collecte.index') }}">
                        @if(request('per_page'))
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif
                        <input type="text" class="bg-base h-40-px" name="search" placeholder="Rechercher par code, nom ou adresse..." value="{{ request('search') }}" style="min-width: 300px;">
                        <i class="ri-search-line"></i>
                    </form>
                    @if(request('search'))
                        <a href="{{ route('admin.centres-collecte.index') }}{{ request('per_page') ? '?per_page=' . request('per_page') : '' }}" class="btn btn-outline-secondary btn-sm px-12 py-6 radius-8 d-flex align-items-center gap-2">
                            <i class="ri-close-line"></i>
                            Effacer les filtres
                        </a>
                    @endif
                </div>
                <a href="{{ route('admin.centres-collecte.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <i class="ri-add-line icon text-xl line-height-1"></i>
                    Ajouter un centre
                </a>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="centres-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Code</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Adresse</th>
                                <th scope="col">Statut</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($centres as $index => $centre)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $centre->code }}</td>
                                <td>{{ $centre->nom }}</td>
                                <td>{{ Str::limit($centre->adresse, 50) }}</td>
                                <td>
                                    @if($centre->statut === 'actif')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <button type="button" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir" data-bs-toggle="modal" data-bs-target="#viewCentreModal{{ $centre->id }}">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <a href="{{ route('admin.centres-collecte.edit', $centre) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Modifier">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.centres-collecte.destroy', $centre) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce centre de transit ?');">
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
                @if($centres->hasPages())
                <div class="row mt-24">
                    <div class="col-12">
                        <div class="card p-24 radius-12 border-0 shadow-sm">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <!-- Informations de pagination -->
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted text-sm">
                                        Affichage de 
                                        <span class="fw-semibold text-primary">{{ $centres->firstItem() }}</span>
                                        à 
                                        <span class="fw-semibold text-primary">{{ $centres->lastItem() }}</span>
                                        sur 
                                        <span class="fw-semibold text-primary">{{ $centres->total() }}</span>
                                        centres de transit
                                    </span>
                                </div>

                                <!-- Navigation pagination intelligente -->
                                <nav aria-label="Navigation des pages">
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Page précédente --}}
                                        @if($centres->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link bg-light border-0 text-muted">
                                                    Précédent
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a href="{{ $centres->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                                    Précédent
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Pages intelligentes --}}
                                        @php
                                            $currentPage = $centres->currentPage();
                                            $lastPage = $centres->lastPage();
                                            $startPage = max(1, $currentPage - 2);
                                            $endPage = min($lastPage, $currentPage + 2);
                                        @endphp

                                        {{-- Première page --}}
                                        @if($startPage > 1)
                                            <li class="page-item">
                                                <a href="{{ $centres->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
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
                                                    <a href="{{ $centres->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $page }}</a>
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
                                                <a href="{{ $centres->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $lastPage }}</a>
                                            </li>
                                        @endif

                                        {{-- Page suivante --}}
                                        @if($centres->hasMorePages())
                                            <li class="page-item">
                                                <a href="{{ $centres->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
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
                                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
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
    
    <!-- Modals pour voir les détails -->
    @foreach($centres as $centre)
    <div class="modal fade" id="viewCentreModal{{ $centre->id }}" tabindex="-1" aria-labelledby="viewCentreModalLabel{{ $centre->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCentreModalLabel{{ $centre->id }}">
                        <i class="ri-eye-line icon me-2"></i>
                        Détails du Centre de Transit
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Code :</label>
                                <p class="form-control-plaintext">{{ $centre->code }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Nom :</label>
                                <p class="form-control-plaintext">{{ $centre->nom }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Adresse :</label>
                        <p class="form-control-plaintext">{{ $centre->adresse }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Statut :</label>
                        <p class="form-control-plaintext">
                            @if($centre->statut === 'actif')
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Date de création :</label>
                                <p class="form-control-plaintext">{{ $centre->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Dernière modification :</label>
                                <p class="form-control-plaintext">{{ $centre->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.centres-collecte.edit', $centre) }}" class="btn btn-warning btn-sm">
                        <i class="ri-edit-line"></i>
                        Modifier
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i>
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</main>

@include('partials.wowdash-scripts')

<script>
// Fonction pour changer le nombre d'éléments par page
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.delete('page'); // Reset à la page 1
    window.location.href = url.toString();
}
</script>
</body>
</html> 