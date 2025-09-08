<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Secteurs - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Gestion des Secteurs</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
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

        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                    <form method="GET" action="{{ route('admin.secteurs.index') }}" class="d-inline">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                    <form class="navbar-search" method="GET" action="{{ route('admin.secteurs.index') }}">
                        @if(request('per_page'))
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif
                        <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                    @if(request('search') || request('per_page') != 10)
                        <a href="{{ route('admin.secteurs.index') }}" class="btn btn-outline-secondary btn-sm px-12 py-6 radius-8 d-flex align-items-center gap-2">
                            <iconify-icon icon="lucide:x" class="icon text-sm"></iconify-icon>
                            Effacer les filtres
                        </a>
                    @endif
                </div>
                <button type="button" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addSecteurModal">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    Ajouter un Secteur
                </button>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Code Secteur</th>
                                <th scope="col">Nom Secteur</th>
                                <th scope="col">Statistiques</th>
                                <th scope="col" class="text-center">Modifier</th>
                                <th scope="col" class="text-center">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($secteurs as $index => $secteur)
                            <tr>
                                <td>{{ $secteurs->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary rounded-circle p-1">
                                            <iconify-icon icon="solar:buildings-2-bold" class="text-white" style="font-size: 0.8rem;"></iconify-icon>
                                        </div>
                                        <span class="text-md mb-0 fw-normal text-secondary-light">{{ $secteur->code }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="text-md mb-0 fw-semibold text-dark">{{ $secteur->nom }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-success">
                                            <iconify-icon icon="" class="me-1"></iconify-icon>
                                            {{ $secteur->cooperatives_count ?? 0 }} Coopératives
                                        </span>
                                        <span class="badge bg-info">
                                            <iconify-icon icon="" class="me-1"></iconify-icon>
                                            {{ $secteur->producteurs_count ?? 0 }} Producteurs
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle edit-secteur-btn" 
                                            data-secteur="{{ $secteur->id }}"
                                            data-code="{{ $secteur->code }}"
                                            data-nom="{{ $secteur->nom }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editSecteurModal"
                                            title="Modifier">
                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.secteurs.destroy', $secteur) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce secteur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" title="Supprimer">
                                            <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination WowDash -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span>Affichage de {{ $secteurs->firstItem() }} à {{ $secteurs->lastItem() }} sur {{ $secteurs->total() }} entrées</span>
                    <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">
                        <!-- Page précédente -->
                        @if($secteurs->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md">
                                    <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="{{ $secteurs->previousPageUrl() }}">
                                    <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                                </a>
                            </li>
                        @endif

                        <!-- Numéros de pages -->
                        @foreach($secteurs->getUrlRange(1, $secteurs->lastPage()) as $page => $url)
                            @if($page == $secteurs->currentPage())
                                <li class="page-item active">
                                    <span class="page-link text-white fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md bg-primary-600">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        <!-- Page suivante -->
                        @if($secteurs->hasMorePages())
                            <li class="page-item">
                                <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="{{ $secteurs->nextPageUrl() }}">
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
                </div>

                @if($secteurs->total() > 10)
                    <div class="alert alert-info mt-3">
                        <iconify-icon icon="solar:info-circle-bold" class="me-2"></iconify-icon>
                        <strong>Note :</strong> {{ $secteurs->total() }} secteurs au total. Utilisez la recherche pour filtrer les résultats ou naviguez avec les flèches.
                    </div>
                @endif
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

<!-- jQuery library js -->
<script src="{{ asset('wowdash/js/lib/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap js -->
<script src="{{ asset('wowdash/js/lib/bootstrap.bundle.min.js') }}"></script>
<!-- Apex Chart js -->
<script src="{{ asset('wowdash/js/lib/apexcharts.min.js') }}"></script>
<!-- Data Table js -->
<script src="{{ asset('wowdash/js/lib/dataTables.min.js') }}"></script>
<!-- Iconify Font js -->
<script src="{{ asset('wowdash/js/lib/iconify-icon.min.js') }}"></script>
<!-- jQuery UI js -->
<script src="{{ asset('wowdash/js/lib/jquery-ui.min.js') }}"></script>
<!-- Vector Map js -->
<script src="{{ asset('wowdash/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- Popup js -->
<script src="{{ asset('wowdash/js/lib/magnifc-popup.min.js') }}"></script>
<!-- Slick Slider js -->
<script src="{{ asset('wowdash/js/lib/slick.min.js') }}"></script>
<!-- prism js -->
<script src="{{ asset('wowdash/js/lib/prism.js') }}"></script>
<!-- file upload js -->
<script src="{{ asset('wowdash/js/lib/file-upload.js') }}"></script>
<!-- audioplayer -->
<script src="{{ asset('wowdash/js/lib/audioplayer.js') }}"></script>
<!-- main js -->
<script src="{{ asset('wowdash/js/app.js') }}"></script>

<script>
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
@include('partials.wowdash-scripts')
</body>
</html> 