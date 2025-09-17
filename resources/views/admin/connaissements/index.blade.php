<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Connaissements - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Gestion des Connaissements</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Connaissements</li>
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
                <h6 class="mb-0">Liste des Connaissements</h6>
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                        <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                        
                        <!-- Filtre par Statut -->
                        <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.connaissements.index') }}">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="statut" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                                <option value="all" {{ $statut === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                                <option value="programme" {{ $statut === 'programme' ? 'selected' : '' }}>Programmé</option>
                                <option value="valide" {{ $statut === 'valide' ? 'selected' : '' }}>Validé pour ticket de pesée</option>
                            </select>
                        </form>
                        
                        <form class="navbar-search" method="GET" action="{{ route('admin.connaissements.index') }}">
                            @if(request('statut') && request('statut') !== 'all')
                                <input type="hidden" name="statut" value="{{ request('statut') }}">
                            @endif
                            <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                            <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                        </form>
                        
                        @if(request('search') || (request('statut') && request('statut') !== 'all'))
                            <a href="{{ route('admin.connaissements.index') }}" class="btn btn-outline-secondary btn-sm px-12 py-6 radius-8 d-flex align-items-center gap-2">
                                <iconify-icon icon="lucide:x" class="icon text-sm"></iconify-icon>
                                Effacer les filtres
                            </a>
                        @endif
                    </div>
                    <a href="{{ route('admin.connaissements.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Nouveau Connaissement
                    </a>
                </div>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="connaissements-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Numéro</th>
                                <th scope="col">Secteur</th>                                <th scope="col">Coopérative</th>
                                <th scope="col">Poids (kg)</th>
                                <th scope="col">Date Réception</th>
                                <th scope="col">Heure Arrivée</th>
                                <th scope="col">Statut</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($connaissements as $index => $connaissement)
                            <tr>
                                <td>{{ $connaissements->firstItem() + $index }}</td>
                                <td>{{ $connaissement->numero_livraison ?: "N/A" }}</td>
                                <td>{{ $connaissement->secteur ? $connaissement->secteur->nom : "N/A" }}</td>                                <td>{{ $connaissement->cooperative->nom }}</td>
                                <td>{{ number_format($connaissement->poids_brut_estime, 2) }}</td>
                                <td>
                                    @if($connaissement->date_reception)
                                        <span class="text-success fw-medium">{{ $connaissement->date_reception->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($connaissement->heure_arrivee)
                                        <span class="text-success fw-medium">{{ $connaissement->heure_arrivee }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($connaissement->statut === 'programme')
                                        @if($connaissement->date_reception)
                                            <span class="badge bg-info">Programmé</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Validé pour ticket de pesée</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="{{ route('admin.connaissements.show', $connaissement) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>

                                        @if(auth()->check() && auth()->user()->role !== 'agc')
                                            <a href="{{ route('admin.connaissements.edit', $connaissement) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Modifier">
                                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                            </a>

                                            @if($connaissement->statut === 'programme')
                                                <a href="{{ route('admin.connaissements.program', $connaissement) }}" class="bg-warning-focus text-warning-600 bg-hover-warning-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Programmer">
                                                    <iconify-icon icon="lucide:calendar" class="menu-icon"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.connaissements.validate', $connaissement) }}" class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Valider">
                                                    <iconify-icon icon="lucide:check" class="menu-icon"></iconify-icon>
                                                </a>
                                            @endif

                                            @if($connaissement->statut === 'programme')
                                                <form action="{{ route('admin.connaissements.destroy', $connaissement) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce connaissement ?');">
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
                
                <!-- Pagination -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span>Affichage de {{ $connaissements->firstItem() ?? 0 }} à {{ $connaissements->lastItem() ?? 0 }} sur {{ $connaissements->total() }} entrées</span>
                    
                    @if($connaissements->hasPages())
                        <nav aria-label="Page navigation">
                            <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center mb-0">
                                <!-- Bouton Précédent -->
                                @if($connaissements->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md">
                                            <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $connaissements->previousPageUrl() }}">
                                            <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                        </a>
                                    </li>
                                @endif

                                <!-- Pages -->
                                @foreach($connaissements->getUrlRange(1, $connaissements->lastPage()) as $page => $url)
                                    @if($page == $connaissements->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md bg-primary-600 text-white">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                <!-- Bouton Suivant -->
                                @if($connaissements->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $connaissements->nextPageUrl() }}">
                                            <iconify-icon icon="ep:d-arrow-right"></iconify-icon>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md">
                                            <iconify-icon icon="ep:d-arrow-right"></iconify-icon>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html> 