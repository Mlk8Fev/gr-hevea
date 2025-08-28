<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Factures - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Gestion des Factures</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Gestion des Factures</li>
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
                    
                    <!-- Filtre par Type -->
                    <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.factures.index') }}">
                        @if(request('statut'))
                            <input type="hidden" name="statut" value="{{ request('statut') }}">
                        @endif
                        @if(request('cooperative'))
                            <input type="hidden" name="cooperative" value="{{ request('cooperative') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="type" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Tous les types</option>
                            <option value="individuelle" {{ $type === 'individuelle' ? 'selected' : '' }}>Individuelles</option>
                            <option value="globale" {{ $type === 'globale' ? 'selected' : '' }}>Globales</option>
                        </select>
                    </form>
                    
                    <!-- Filtre par Statut -->
                    <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.factures.index') }}">
                        @if(request('type'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                        @if(request('cooperative'))
                            <input type="hidden" name="cooperative" value="{{ request('cooperative') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="statut" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                            <option value="all" {{ $statut === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                            <option value="brouillon" {{ $statut === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="validee" {{ $statut === 'validee' ? 'selected' : '' }}>Validée</option>
                            <option value="payee" {{ $statut === 'payee' ? 'selected' : '' }}>Payée</option>
                            <option value="annulee" {{ $statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </form>
                    
                    <!-- Filtre par Coopérative -->
                    <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.factures.index') }}">
                        @if(request('type'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                        @if(request('statut'))
                            <input type="hidden" name="statut" value="{{ request('statut') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="cooperative" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                            <option value="all" {{ $cooperative === 'all' ? 'selected' : '' }}>Toutes les coopératives</option>
                            @foreach($cooperatives as $coop)
                                <option value="{{ $coop->id }}" {{ $cooperative == $coop->id ? 'selected' : '' }}>{{ $coop->nom }}</option>
                            @endforeach
                        </select>
                    </form>
                    
                    <form class="navbar-search" method="GET" action="{{ route('admin.factures.index') }}">
                        @if(request('type') && request('type') !== 'all')
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                        @if(request('statut') && request('statut') !== 'all')
                            <input type="hidden" name="statut" value="{{ request('statut') }}">
                        @endif
                        @if(request('cooperative') && request('cooperative') !== 'all')
                            <input type="hidden" name="cooperative" value="{{ request('cooperative') }}">
                        @endif
                        <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Rechercher..." value="{{ $search }}">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                    
                    @if(request('search') || (request('type') && request('type') !== 'all') || (request('statut') && request('statut') !== 'all') || (request('cooperative') && request('cooperative') !== 'all'))
                        <a href="{{ route('admin.factures.index') }}" class="btn btn-outline-secondary btn-sm px-12 py-6 radius-8 d-flex align-items-center gap-2">
                            <iconify-icon icon="lucide:x" class="icon text-sm"></iconify-icon>
                            Effacer les filtres
                        </a>
                    @endif
                </div>
                
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.factures.create', ['type' => 'individuelle']) }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ri-add-line" class="icon text-xl line-height-1"></iconify-icon>
                        Facture Individuelle
                    </a>
                    <a href="{{ route('admin.factures.create', ['type' => 'globale']) }}" class="btn btn-success text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ri-add-line" class="icon text-xl line-height-1"></iconify-icon>
                        Facture Globale
                    </a>
                </div>
            </div>
            
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="factures-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">N° Facture</th>
                                <th scope="col">Type</th>
                                <th scope="col">Coopérative</th>
                                <th scope="col">Montant TTC</th>
                                <th scope="col">Date Émission</th>
                                <th scope="col">Échéance</th>
                                <th scope="col">Statut</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($factures as $index => $facture)
                                <tr>
                                    <td>{{ $factures->firstItem() + $index }}</td>
                                    <td>
                                        <span class="text-md mb-0 fw-normal text-primary fw-bold">{{ $facture->numero_facture }}</span>
                                    </td>
                                    <td>
                                        @if($facture->type === 'individuelle')
                                            <span class="badge bg-info">Individuelle</span>
                                        @else
                                            <span class="badge bg-success">Globale</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-md mb-0 fw-normal text-secondary-light">{{ $facture->cooperative->nom }}</span>
                                    </td>
                                    <td>
                                        <span class="text-md mb-0 fw-normal text-success fw-bold">{{ number_format($facture->montant_ttc, 0) }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="text-md mb-0 fw-normal text-secondary-light">{{ $facture->date_emission->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        @if($facture->isEnRetard())
                                            <span class="text-danger fw-medium">{{ $facture->date_echeance->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-md mb-0 fw-normal text-secondary-light">{{ $facture->date_echeance->format('d/m/Y') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($facture->statut === 'brouillon')
                                            <span class="badge bg-warning">Brouillon</span>
                                        @elseif($facture->statut === 'validee')
                                            <span class="badge bg-info">Validée</span>
                                        @elseif($facture->statut === 'payee')
                                            <span class="badge bg-success">Payée</span>
                                        @elseif($facture->statut === 'annulee')
                                            <span class="badge bg-secondary">Annulée</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="{{ route('admin.factures.show', $facture) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Voir">
                                                <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                            </a>
                                            
                                            @if($facture->canBeValidated())
                                                <form action="{{ route('admin.factures.validate', $facture) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="bg-primary-focus text-primary-600 bg-hover-primary-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Valider" onclick="return confirm('Valider cette facture ?')">
                                                        <iconify-icon icon="lucide:check" class="menu-icon"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($facture->canBePaid())
                                                <button type="button" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Marquer comme payée" onclick="markAsPaid({{ $facture->id }}, {{ $facture->montant_ttc }})">
                                                    <iconify-icon icon="lucide:credit-card" class="menu-icon"></iconify-icon>
                                                </button>
                                            @endif
                                            
                                            @if($facture->statut === 'validee')
                                                <a href="{{ route('admin.factures.preview', $facture) }}" class="bg-warning-focus text-warning-600 bg-hover-warning-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Preview">
                                                    <iconify-icon icon="lucide:file-text" class="menu-icon"></iconify-icon>
                                                </a>
                                            @endif
                                            
                                            @if($facture->statut === 'brouillon')
                                                <form action="{{ route('admin.factures.destroy', $facture) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette facture ?');">
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
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-24">
                                        <div class="text-center">
                                            <iconify-icon icon="majesticons:inbox-line" class="text-6xl text-muted"></iconify-icon>
                                            <h6 class="mt-3 text-muted">Aucune facture trouvée</h6>
                                            <p class="text-muted mb-0">Aucune facture ne correspond aux critères de recherche.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span>Affichage de {{ $factures->firstItem() ?? 0 }} à {{ $factures->lastItem() ?? 0 }} sur {{ $factures->total() }} entrées</span>
                    
                    @if($factures->hasPages())
                        <nav aria-label="Page navigation">
                            <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center mb-0">
                                <!-- Bouton Précédent -->
                                @if($factures->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md">
                                            <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $factures->previousPageUrl() }}">
                                            <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                        </a>
                                    </li>
                                @endif

                                <!-- Pages -->
                                @for($page = 1; $page <= $factures->lastPage(); $page++)
                                    @if($page == $factures->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md bg-primary-600 text-white">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $factures->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endfor

                                <!-- Bouton Suivant -->
                                @if($factures->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $factures->nextPageUrl() }}">
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

<!-- Modal pour marquer comme payée -->
<div class="modal fade" id="markAsPaidModal" tabindex="-1" aria-labelledby="markAsPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAsPaidModalLabel">Marquer comme payée</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="markAsPaidForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="montant_paye" class="form-label">Montant payé (FCFA)</label>
                        <input type="number" class="form-control" id="montant_paye" name="montant_paye" step="0.01" required>
                        <div class="form-text">Montant total de la facture : <span id="montantTotal"></span> FCFA</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer le paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function markAsPaid(factureId, montantTotal) {
    document.getElementById('montantTotal').textContent = montantTotal.toLocaleString();
    document.getElementById('montant_paye').value = montantTotal;
    document.getElementById('montant_paye').max = montantTotal;
    document.getElementById('markAsPaidForm').action = `/admin/factures/${factureId}/mark-as-paid`;
    
    new bootstrap.Modal(document.getElementById('markAsPaidModal')).show();
}
</script>

</body>
</html> 