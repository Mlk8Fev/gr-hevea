<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tickets de Pesée - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Gestion des Tickets de Pesée</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Gestion des Tickets de Pesée</li>
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
                    <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                    <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    
                    <!-- Filtre par Statut -->
                    <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.tickets-pesee.index') }}">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="statut" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                            <option value="all" {{ $statut === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                            <option value="en_attente" {{ $statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="valide" {{ $statut === 'valide' ? 'selected' : '' }}>Validé pour paiement</option>
                        </select>
                    </form>
                    
                    <form class="navbar-search" method="GET" action="{{ route('admin.tickets-pesee.index') }}">
                        @if(request('statut') && request('statut') !== 'all')
                            <input type="hidden" name="statut" value="{{ request('statut') }}">
                        @endif
                        <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                    
                    @if(request('search') || (request('statut') && request('statut') !== 'all'))
                        <a href="{{ route('admin.tickets-pesee.index') }}" class="btn btn-outline-secondary btn-sm px-12 py-6 radius-8 d-flex align-items-center gap-2">
                            <iconify-icon icon="lucide:x" class="icon text-sm"></iconify-icon>
                            Effacer les filtres
                        </a>
                    @endif
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.tickets-pesee.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ri-add-line" class="icon text-xl line-height-1"></iconify-icon>
                        Créer depuis Connaissement
                    </a>
                </div>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="tickets-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">N° Ticket</th>
                                <th scope="col">Connaissement</th>
                                <th scope="col">Coopérative</th>
                                <th scope="col">Poids Net (kg)</th>
                                <th scope="col">Date Entrée</th>
                                <th scope="col">Statut</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $index => $ticket)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $ticket->numero_ticket }}</span></td>
                                <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $ticket->connaissement->numero }}</span></td>
                                <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $ticket->connaissement->cooperative->nom }}</span></td>
                                <td><span class="text-md mb-0 fw-normal text-success fw-bold">{{ number_format($ticket->poids_net, 2) }}</span></td>
                                <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $ticket->date_entree->format('d/m/Y') }}</span></td>
                                <td>
                                    @if($ticket->statut === 'en_attente')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($ticket->statut === 'valide')
                                        <span class="badge bg-success">Validé pour paiement</span>
                                    @else
                                        <span class="badge bg-secondary">Archivé</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="{{ route('admin.tickets-pesee.show', $ticket) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Voir">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>
                                        
                                        <a href="{{ route('admin.tickets-pesee.edit', $ticket) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Modifier">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        
                                        @if($ticket->statut === 'en_attente')
                                            <form action="{{ route('admin.tickets-pesee.validate', $ticket) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Valider" onclick="return confirm('Valider ce ticket de pesée ?')">
                                                    <iconify-icon icon="lucide:check" class="menu-icon"></iconify-icon>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($ticket->statut === 'valide')
                                            <form action="{{ route('admin.tickets-pesee.archive', $ticket) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-secondary-focus text-secondary-600 bg-hover-secondary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Archiver" onclick="return confirm('Archiver ce ticket de pesée ?')">
                                                    <iconify-icon icon="lucide:archive" class="menu-icon"></iconify-icon>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($ticket->statut === 'en_attente')
                                            <form action="{{ route('admin.tickets-pesee.destroy', $ticket) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce ticket de pesée ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Supprimer">
                                                    <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
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
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span>Affichage de {{ $tickets->firstItem() ?? 0 }} à {{ $tickets->lastItem() ?? 0 }} sur {{ $tickets->total() }} entrées</span>
                    
                    @if($tickets->hasPages())
                        <nav aria-label="Page navigation">
                            <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center mb-0">
                                <!-- Bouton Précédent -->
                                @if($tickets->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md">
                                            <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $tickets->previousPageUrl() }}">
                                            <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                        </a>
                                    </li>
                                @endif

                                <!-- Pages -->
                                @foreach($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                                    @if($page == $tickets->currentPage())
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
                                @if($tickets->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $tickets->nextPageUrl() }}">
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