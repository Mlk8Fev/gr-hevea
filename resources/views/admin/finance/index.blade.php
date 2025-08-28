<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Finance - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
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
            <h6 class="fw-semibold mb-0">Section Finance</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Section Finance</li>
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

        <!-- Informations sur le système de prix -->
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="ri-information-line me-2"></i>
            <strong>Système de Prix :</strong> Prix de base 93 FCFA/kg (94 FCFA/kg avec séchoir) + Bonus Qualité + Transport
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

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
                    
                    <!-- Filtre par Statut ENE CI -->
                    <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.finance.index') }}">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="statut_ene" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                            <option value="all" {{ $statutEne === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                            <option value="en_attente" {{ $statutEne === 'en_attente' ? 'selected' : '' }}>En attente ENE CI</option>
                            <option value="valide_par_ene" {{ $statutEne === 'valide_par_ene' ? 'selected' : '' }}>Validé pour facturation</option>
                            <option value="rejete_par_ene" {{ $statutEne === 'rejete_par_ene' ? 'selected' : '' }}>Rejeté par ENE CI</option>
                        </select>
                    </form>
                    
                    <form class="navbar-search" method="GET" action="{{ route('admin.finance.index') }}">
                        @if(request('statut_ene') && request('statut_ene') !== 'all')
                            <input type="hidden" name="statut_ene" value="{{ request('statut_ene') }}">
                        @endif
                        <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Rechercher..." value="{{ $search }}">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                    
                    @if(request('search') || (request('statut_ene') && request('statut_ene') !== 'all'))
                        <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary btn-sm px-12 py-6 radius-8 d-flex align-items-center gap-2">
                            <iconify-icon icon="lucide:x" class="icon text-sm"></iconify-icon>
                            Effacer les filtres
                        </a>
                    @endif
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.finance.matrice-prix') }}" class="btn btn-outline-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ri-settings-3-line" class="icon text-xl line-height-1"></iconify-icon>
                        Matrice de Prix
                    </a>
                </div>
            </div>
            <div class="card-body p-24">
                @if(count($ticketsAvecPrix) > 0)
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">N° Ticket</th>
                                    <th scope="col">Coopérative</th>
                                    <th scope="col">Poids Net (kg)</th>
                                    <th scope="col">Statut ENE CI</th>
                                    <th scope="col">Prix Final</th>
                                    <th scope="col">Montant Total</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ticketsAvecPrix as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item['ticket']->numero_ticket }}</span>
                                    </td>
                                    <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ $item['ticket']->connaissement->cooperative->nom }}</span></td>
                                    <td><span class="text-md mb-0 fw-normal text-secondary-light">{{ number_format($item['ticket']->poids_net, 2) }}</span></td>
                                    
                                    <!-- Statut ENE CI -->
                                    <td>
                                        @if($item['ticket']->statut_ene === 'en_attente')
                                            <span class="badge bg-warning text-dark">
                                                <i class="ri-time-line me-1"></i>En attente ENE CI
                                            </span>
                                        @elseif($item['ticket']->statut_ene === 'valide_par_ene')
                                            <span class="badge bg-success">
                                                <i class="ri-check-line me-1"></i>Validé pour facturation
                                            </span>
                                        @elseif($item['ticket']->statut_ene === 'rejete_par_ene')
                                            <span class="badge bg-danger">
                                                <i class="ri-close-line me-1"></i>Rejeté par ENE CI
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Non défini</span>
                                        @endif
                                    </td>
                                    
                                    @if($item['prix'])
                                        <td>
                                            <strong class="text-primary fw-semibold">{{ number_format($item['prix']['prix_final_public'], 2) }} FCFA</strong>
                                        </td>
                                        <td>
                                            <strong class="text-success fw-semibold">{{ number_format($item['prix']['prix_final_public'] * $item['ticket']->poids_net, 2) }} FCFA</strong>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.finance.show-calcul', $item['ticket']->id) }}" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0" title="Voir Calcul">
                                                <iconify-icon icon="majesticons:calculator-line" class="icon text-xl"></iconify-icon>
                                            </a>
                                        </td>
                                    @else
                                        <td colspan="3">
                                            <span class="text-danger fw-semibold">{{ $item['erreur'] ?? 'Erreur de calcul' }}</span>
                                        </td>
                                        <td class="text-center">-</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                        <span>Affichage de {{ $ticketsValides->firstItem() ?? 0 }} à {{ $ticketsValides->lastItem() ?? 0 }} sur {{ $ticketsValides->total() }} entrées</span>
                        
                        <!-- Pagination -->
                        @if($ticketsValides->hasPages())
                            <nav aria-label="Page navigation">
                                <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center mb-0">
                                    <!-- Bouton Précédent -->
                                    @if($ticketsValides->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md">
                                                <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $ticketsValides->previousPageUrl() }}">
                                                <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Pages -->
                                    @foreach($ticketsValides->getUrlRange(1, $ticketsValides->lastPage()) as $page => $url)
                                        @if($page == $ticketsValides->currentPage())
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
                                    @if($ticketsValides->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md hover-bg-neutral-300" href="{{ $ticketsValides->nextPageUrl() }}">
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
                @else
                    <div class="text-center py-4">
                        <iconify-icon icon="majesticons:calculator-line" class="text-6xl text-muted"></iconify-icon>
                        <h5 class="mt-3 text-muted">Aucun ticket validé pour paiement</h5>
                        <p class="text-muted">Les tickets de pesée validés apparaîtront ici avec leurs prix calculés.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html> 