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
                <h6 class="mb-0">Liste des Tickets de Pesée</h6>
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                        <select class="form-select form-select-sm w-auto" id="show-select">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-md fw-medium text-secondary-light mb-0">entries</span>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <form method="GET" class="d-flex align-items-center gap-2">
                            <input type="text" class="form-control form-control-sm" placeholder="Rechercher..." name="search" value="{{ $search }}">
                            <select name="statut" class="form-select form-select-sm">
                                <option value="all" {{ $statut == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                                <option value="en_attente" {{ $statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="valide" {{ $statut == 'valide' ? 'selected' : '' }}>Validé</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <iconify-icon icon="ri-search-line"></iconify-icon>
                            </button>
                        </form>
                    </div>
                </div>
                @if(auth()->check() && auth()->user()->role !== 'agc')
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.tickets-pesee.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ri-add-line" class="icon text-xl line-height-1"></iconify-icon>
                        Créer depuis Connaissement
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="tickets-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">N° Livraison</th>
                                <th scope="col">Secteur</th>
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
                                <td>{{ $tickets->firstItem() + $index }}</td>
                                <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $ticket->numero_livraison ?: 'N/A' }}</span></td>
                                <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $ticket->connaissement->secteur ? $ticket->connaissement->secteur->nom : 'N/A' }}</span></td>
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
                                        <a href="{{ route('admin.tickets-pesee.show', $ticket) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir">
                                            <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                        </a>

                                        @if(auth()->check() && auth()->user()->role !== 'agc')
                                            <a href="{{ route('admin.tickets-pesee.edit', $ticket) }}" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Modifier">
                                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                            </a>

                                            @if($ticket->statut === 'en_attente')
                                                <form action="{{ route('admin.tickets-pesee.validate', $ticket) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Valider">
                                                        <iconify-icon icon="lucide:check" class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($ticket->statut === 'valide')
                                                <form action="{{ route('admin.tickets-pesee.cancel-validation', $ticket) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-warning-focus text-warning-600 bg-hover-warning-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Annuler validation">
                                                    <iconify-icon icon="lucide:x" class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($ticket->statut !== 'valide')
                                                <form action="{{ route('admin.tickets-pesee.destroy', $ticket) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket de pesée ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-danger-focus text-danger-600 bg-hover-danger-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Supprimer">
                                                        <iconify-icon icon="lucide:trash-2" class="menu-icon"></iconify-icon>
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
                
                @if($tickets->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Affichage de {{ $tickets->firstItem() }} à {{ $tickets->lastItem() }} sur {{ $tickets->total() }} résultats
                    </div>
                    <div>
                        {{ $tickets->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html>