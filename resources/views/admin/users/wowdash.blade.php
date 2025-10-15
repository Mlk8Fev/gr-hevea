<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - FPH-CI</title>
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
    <div class="navbar-header">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <button type="button" class="sidebar-toggle">
                        <i class="ri-menu-line"></i>
                        <i class="ri-arrow-right-line"></i>
                    </button>
                    <button type="button" class="sidebar-mobile-toggle">
                        <i class="ri-menu-line"></i>
                    </button>
                    <form class="navbar-search">
                        <input type="text" name="search" placeholder="Rechercher">
                        <i class="ri-search-line"></i>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                    
                    <div class="dropdown">
                        <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                            <i class="ri-eye-line text-primary-light text-xl"></i>
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                                </div>
                                <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('wowdash/images/avatar/avatar1.png') }}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm">
                            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ auth()->user()->full_name }}</h6>
                                    <span class="text-secondary-light fw-medium text-sm">{{ auth()->user()->fonction->nom ?? 'Aucune fonction' }}</span>
                                    
                                </div>
                                <button type="button" class="hover-text-danger">
                                    <i class="ri-eye-line icon text-xl"></i> 
                                </button>
                            </div>
                            <ul class="to-top-list">
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('profile') }}"> 
                                    <i class="ri-user-line icon text-xl"></i> Mon Profil</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3 w-100 border-0 bg-transparent"> 
                                        <i class="ri-shut-down-line"></i> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Gestion des Utilisateurs</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
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
                    <form class="navbar-search" method="GET" action="{{ route('admin.users.index') }}">
                        <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                        <i class="ri-search-line"></i>
                    </form>
                    <form method="GET" action="{{ route('admin.users.index') }}" class="d-inline">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" name="status" onchange="this.form.submit()">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </form>
                    <form method="GET" action="{{ route('admin.users.index') }}" class="d-inline">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('status') && request('status') !== 'all')
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" name="fonction_id" onchange="this.form.submit()">
                            <option value="all" {{ request('fonction_id') == 'all' || !request('fonction_id') ? 'selected' : '' }}>Toutes les fonctions</option>
                            @foreach($fonctions as $fonction)
                                <option value="{{ $fonction->id }}" {{ request('fonction_id') == $fonction->id ? 'selected' : '' }}>{{ $fonction->nom }}</option>
                            @endforeach
                        </select>
                    </form>
                    <!-- Le filtre par coopérative est supprimé -->

                    @if(request('search') || (request('status') && request('status') !== 'all') || (request('fonction_id') && request('fonction_id') !== 'all'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm px-12 py-6 radius-8 d-flex align-items-center gap-2">
                            <i class="ri-close-line"></i>
                            Effacer les filtres
                        </a>
                    @endif
                </div>
                <button type="button" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="ri-add-line icon text-xl line-height-1"></i>
                    Ajouter un utilisateur
                </button>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Utilisateur</th>
                                <th scope="col">Secteur & Fonction</th>
                                <th scope="col" class="text-center">Statut</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $user->full_name }}</div>
                                        <div class="text-muted text-sm">{{ $user->email }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($user->secteurRelation)
                                            <span class="bg-info-focus text-info-600 border border-info-main px-24 py-4 radius-4 fw-medium text-sm">
                                                {{ $user->secteurRelation->nom }}
                                            </span>
                                        @else
                                            <span class="bg-neutral-200 text-neutral-600 border border-neutral-400 px-24 py-4 radius-4 fw-medium text-sm">
                                                Non défini
                                            </span>
                                        @endif
                                        <span class="text-sm text-muted">
                                            {{ $user->fonction ? $user->fonction->nom : 'Non défini' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($user->status === 'active')
                                        <span class="bg-success-focus text-success-600 border border-success-main px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                                    @else
                                        <span class="bg-neutral-200 text-neutral-600 border border-neutral-400 px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <!-- Bouton Voir -->
                                        <button type="button" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle show-user-btn" 
                                                data-user="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-username="{{ $user->username }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}"
                                                data-secteur="{{ $user->secteur }}"
                                                data-fonction="{{ $user->fonction ? $user->fonction->nom : 'Non défini' }}"
                                                data-cooperative="{{ $user->cooperative ? $user->cooperative->nom : 'Non défini' }}"
                                                data-siege="{{ $user->siege ? 'Oui' : 'Non' }}"
                                                data-status="{{ $user->status }}"
                                                data-created="{{ $user->created_at->format('d/m/Y H:i') }}"
                                                data-updated="{{ $user->updated_at->format('d/m/Y H:i') }}"
                                                title="Voir">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        
                                        <!-- Bouton Modifier (Vert) -->
                                        <button type="button" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle edit-user-btn" 
                                                data-user="{{ $user->id }}"
                                                data-username="{{ $user->username }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}"
                                                data-secteur="{{ $user->secteur }}"
                                                data-fonction="{{ $user->fonction ? $user->fonction->id : '' }}"
                                                data-cooperative="{{ $user->cooperative ? $user->cooperative->id : '' }}"
                                                data-siege="{{ $user->siege ? '1' : '0' }}"
                                                data-status="{{ $user->status }}"
                                                title="Modifier">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        
                                        <!-- Bouton Activer/Désactiver -->
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-{{ $user->status === 'active' ? 'warning' : 'success' }}-focus text-{{ $user->status === 'active' ? 'warning' : 'success' }}-600 bg-hover-{{ $user->status === 'active' ? 'warning' : 'success' }}-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" 
                                                    title="{{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                                <i class="ri-{{ $user->status === 'active' ? 'close-line' : 'check-line' }} menu-icon"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Bouton Supprimer (Rouge) -->
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
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

                <!-- Navigation pagination -->
                <nav aria-label="Navigation des pages">
                    <ul class="pagination pagination-sm mb-0">
                        <!-- Page précédente -->
                        @if($users->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link bg-light border-0 text-muted">
                                    Précédent
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $users->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                    Précédent
                                </a>
                            </li>
                        @endif

                        <!-- Pages numérotées -->
                        @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if($page == $users->currentPage())
                                <li class="page-item active">
                                    <span class="page-link bg-primary border-0 text-white fw-semibold">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a href="{{ $users->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        <!-- Page suivante -->
                        @if($users->hasMorePages())
                            <li class="page-item">
                                <a href="{{ $users->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
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
</main>

<!-- Modal Ajouter Utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">
                    <i class="ri-user-add-line me-2"></i>Ajouter un utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur *</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nom complet *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Prénom + Nom" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rôle *</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Sélectionner un rôle</option>
                                <option value="superadmin" {{ old('role')=='superadmin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                                <option value="manager" {{ old('role')=='manager' ? 'selected' : '' }}>Manager</option>
                                <option value="agc" {{ old('role')=='agc' ? 'selected' : '' }}>Agent Gestion Qualité</option>
                                <option value="cs" {{ old('role')=='cs' ? 'selected' : '' }}>Chef Secteur</option>
                                <option value="ac" {{ old('role')=='ac' ? 'selected' : '' }}>Assistante Comptable</option>
                                <option value="rt" {{ old('role')=='rt' ? 'selected' : '' }}>Responsable Traçabilité</option>
                                <option value="rd" {{ old('role')=='rd' ? 'selected' : '' }}>Responsable Durabilité</option>
                                <option value="comp" {{ old('role')=='comp' ? 'selected' : '' }}>Comptable Siège</option>
                                <option value="ctu" {{ old('role')=='ctu' ? 'selected' : '' }}>Contrôleur Usine</option>
                                <option value="rcoop" {{ old('role')=='rcoop' ? 'selected' : '' }}>Responsable Coopérative</option>
                                <option value="user" {{ old('role')=='user' ? 'selected' : '' }}>Utilisateur</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fonction_id" class="form-label">Fonction *</label>
                            <select class="form-select @error('fonction_id') is-invalid @enderror" id="fonction_id" name="fonction_id" required>
                                <option value="">Sélectionner une fonction</option>
                                @foreach($fonctions as $fonction)
                                    <option value="{{ $fonction->id }}" {{ old('fonction_id')==$fonction->id ? 'selected' : '' }}>{{ $fonction->nom }}</option>
                                @endforeach
                            </select>
                            @error('fonction_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cooperative_id" class="form-label">Coopérative</label>
                            <input type="text" class="form-control @error('cooperative_id') is-invalid @enderror" 
                                   id="cooperative_display" name="cooperative_display" 
                                   placeholder="Tapez le nom de la coopérative..." 
                                   list="cooperatives-list" 
                                   value="{{ old('cooperative_id') ? ($cooperatives->find(old('cooperative_id'))->nom ?? '') . ' (' . ($cooperatives->find(old('cooperative_id'))->code ?? '') . ')' : '' }}">
                            <datalist id="cooperatives-list">
                                @foreach($cooperatives as $cooperative)
                                    <option value="{{ $cooperative->nom }} ({{ $cooperative->code }})" data-id="{{ $cooperative->id }}">
                                @endforeach
                            </datalist>
                            <input type="hidden" id="cooperative_id" name="cooperative_id" value="{{ old('cooperative_id') }}">
                            <small class="form-text text-muted">Obligatoire si la fonction nécessite une coopérative</small>
                            @error('cooperative_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="secteur" class="form-label">Secteur</label>
                            <select class="form-select @error('secteur') is-invalid @enderror" id="secteur" name="secteur">
                                <option value="">Sélectionner un secteur</option>
                                @foreach($secteurs as $secteur)
                                    <option value="{{ $secteur->code }}" {{ old('secteur')==$secteur->code ? 'selected' : '' }}>{{ $secteur->code }} - {{ $secteur->nom }}</option>
                                @endforeach
                            </select>
                            @error('secteur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="siege" name="siege" value="1" {{ old('siege') ? 'checked' : '' }}>
                                <label class="form-check-label" for="siege">
                                    Utilisateur du siège
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Statut *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Sélectionner un statut</option>
                                <option value="active" {{ old('status','active')=='active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="ri-user-edit-line me-2"></i>Modifier l'utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_username" class="form-label">Nom d'utilisateur *</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label">Nom complet *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" placeholder="Prénom + Nom" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label">Nouveau mot de passe (optionnel)</label>
                            <input type="password" class="form-control" id="edit_password" name="password" minlength="8" placeholder="Laissez vide pour ne pas changer">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_role" class="form-label">Rôle *</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="">Sélectionner un rôle</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="agc">Agent Gestion Qualité</option>
                                <option value="cs">Chef Secteur</option>
                                <option value="ac">Assistante Comptable</option>
                                <option value="rt">Responsable Traçabilité</option>
                                <option value="rd">Responsable Durabilité</option>
                                <option value="comp">Comptable Siège</option>
                                <option value="ctu">Contrôleur Usine</option>
                                <option value="rcoop">Responsable Coopérative</option>
                                <option value="user">Utilisateur</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_fonction_id" class="form-label">Fonction *</label>
                            <select class="form-select" id="edit_fonction_id" name="fonction_id" required>
                                <option value="">Sélectionner une fonction</option>
                                @foreach($fonctions as $fonction)
                                    <option value="{{ $fonction->id }}">{{ $fonction->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_cooperative_id" class="form-label">Coopérative</label>
                            <input type="text" class="form-control" 
                                   id="edit_cooperative_display" name="cooperative_display" 
                                   placeholder="Tapez le nom de la coopérative..." 
                                   list="edit-cooperatives-list">
                            <datalist id="edit-cooperatives-list">
                                @foreach($cooperatives as $cooperative)
                                    <option value="{{ $cooperative->nom }} ({{ $cooperative->code }})" data-id="{{ $cooperative->id }}">
                                @endforeach
                            </datalist>
                            <input type="hidden" id="edit_cooperative_id" name="cooperative_id">
                            <small class="form-text text-muted">Obligatoire si la fonction nécessite une coopérative</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_secteur" class="form-label">Secteur</label>
                            <select class="form-select" id="edit_secteur" name="secteur">
                                <option value="">Sélectionner un secteur</option>
                                @foreach($secteurs as $secteur)
                                    <option value="{{ $secteur->code }}">{{ $secteur->code }} - {{ $secteur->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_siege" name="siege" value="1">
                                <label class="form-check-label" for="edit_siege">
                                    Utilisateur du siège
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label">Statut *</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="">Sélectionner un statut</option>
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Voir Utilisateur -->
<div class="modal fade" id="showUserModal" tabindex="-1" aria-labelledby="showUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showUserModalLabel">
                    <i class="ri-user-line me-2"></i>Détails de l'utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Informations de base</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">ID :</td>
                                <td id="show_user_id"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nom d'utilisateur :</td>
                                <td id="show_username"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nom complet :</td>
                                <td id="show_name"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email :</td>
                                <td id="show_email"></td>
                                </tr>
                            <tr>
                                <td class="fw-bold">Rôle :</td>
                                <td id="show_role"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Informations professionnelles</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Fonction :</td>
                                <td id="show_fonction"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Coopérative :</td>
                                <td id="show_cooperative"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Secteur :</td>
                                <td id="show_secteur"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Utilisateur du siège :</td>
                                <td id="show_siege"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Statut :</td>
                                <td id="show_status"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">Informations système</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Créé le :</td>
                                <td id="show_created"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Dernière modification :</td>
                                <td id="show_updated"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Fermer
                </button>
            </div>
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
$(document).ready(function() {
    // Gestion du bouton Voir
    $('.show-user-btn').on('click', function() {
        var userId = $(this).data('user');
        var name = $(this).data('name');
        var username = $(this).data('username');
        var email = $(this).data('email');
        var role = $(this).data('role');
        var secteur = $(this).data('secteur');
        var fonction = $(this).data('fonction');
        var cooperative = $(this).data('cooperative');
        var siege = $(this).data('siege');
        var status = $(this).data('status');
        var created = $(this).data('created');
        var updated = $(this).data('updated');

        // Remplir le modal de visualisation
        $('#show_user_id').text(userId);
        $('#show_username').text(username);
        $('#show_name').text(name);
        $('#show_email').text(email);
        $('#show_role').text(role);
        $('#show_secteur').text(secteur || 'Non défini');
        $('#show_fonction').text(fonction);
        $('#show_cooperative').text(cooperative);
        $('#show_siege').text(siege);
        $('#show_status').text(status === 'active' ? 'Actif' : 'Inactif');
        $('#show_created').text(created);
        $('#show_updated').text(updated);

        // Ouvrir le modal
        $('#showUserModal').modal('show');
    });

    // Gestion du bouton Éditer
    $('.edit-user-btn').on('click', function() {
        var userId = $(this).data('user');
        var username = $(this).data('username');
        var name = $(this).data('name');
        var email = $(this).data('email');
        var role = $(this).data('role');
        var secteur = $(this).data('secteur');
        var fonction = $(this).data('fonction');
        var cooperative = $(this).data('cooperative');
        var siege = $(this).data('siege');
        var status = $(this).data('status');

        // Afficher les données récupérées dans la console pour déboguer
        console.log('=== DONNÉES RÉCUPÉRÉES POUR L\'ÉDITION ===');
        console.log('ID:', userId);
        console.log('Username:', username);
        console.log('Name:', name);
        console.log('Email:', email);
        console.log('Role:', role);
        console.log('Secteur:', secteur);
        console.log('Fonction:', fonction);
        console.log('Coopérative:', cooperative);
        console.log('Siège:', siege);
        console.log('Status:', status);
        console.log('==========================================');

        // Remplir TOUS les champs du formulaire d'édition avec les données existantes
        $('#edit_username').val(username);
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#edit_role').val(role);
        $('#edit_secteur').val(secteur);
        $('#edit_fonction_id').val(fonction);
        $('#edit_cooperative_id').val(cooperative);
        $('#edit_status').val(status);
        
    // Gérer la checkbox siège
    if (siege == '1') {
        $('#edit_siege').prop('checked', true);
    } else {
        $('#edit_siege').prop('checked', false);
    }
    
    // Gérer la coopérative pour l'édition
    if (cooperative) {
        // Trouver le nom de la coopérative à partir de l'ID
        var cooperativeOption = $('#edit-cooperatives-list option[data-id="' + cooperative + '"]');
        if (cooperativeOption.length > 0) {
            $('#edit_cooperative_display').val(cooperativeOption.val());
            $('#edit_cooperative_id').val(cooperative);
        }
    } else {
        $('#edit_cooperative_display').val('');
        $('#edit_cooperative_id').val('');
    }

        // Mettre à jour l'action du formulaire
        $('#editUserForm').attr('action', '/admin/users/' + userId);

        // Ouvrir le modal
        $('#editUserModal').modal('show');
    });

    // Réinitialiser le formulaire d'ajout quand le modal se ferme
    $('#addUserModal').on('hidden.bs.modal', function () {
        $('#addUserForm')[0].reset();
    });

    // Réinitialiser le formulaire d'édition quand le modal se ferme
    $('#editUserModal').on('hidden.bs.modal', function () {
        $('#editUserForm')[0].reset();
    });

    // Gestion des erreurs de validation
    @if($errors->any())
        $('#addUserModal').modal('show');
    @endif

    // Gestion de la suppression avec effet visuel
    $(".remove-item-btn").on("click", function() {
        $(this).closest("tr").addClass("d-none");
    });

    // Validation intelligente du champ coopérative (Modal Ajout)
    $('#fonction_id').on('change', function() {
        var selectedFonction = $(this).val();
        var cooperativeSelect = $('#cooperative_id');
        
        if (selectedFonction) {
            // Récupérer le nom de la fonction sélectionnée
            var fonctionText = $(this).find('option:selected').text();
            
            // Vérifier si la fonction nécessite une coopérative
            var cooperativeRequired = ['Responsable Coopérative', 'Chef de Coopérative'].includes(fonctionText);
            
            if (cooperativeRequired) {
                cooperativeSelect.prop('required', true);
                cooperativeSelect.addClass('is-invalid');
                cooperativeSelect.siblings('.form-text').addClass('text-danger').removeClass('text-muted');
            } else {
                cooperativeSelect.prop('required', false);
                cooperativeSelect.removeClass('is-invalid');
                cooperativeSelect.siblings('.form-text').removeClass('text-danger').addClass('text-muted');
            }
        }
    });

    // Validation intelligente du champ coopérative (Modal Édition)
    $('#edit_fonction_id').on('change', function() {
        var selectedFonction = $(this).val();
        var cooperativeSelect = $('#edit_cooperative_id');
        
        if (selectedFonction) {
            // Récupérer le nom de la fonction sélectionnée
            var fonctionText = $(this).find('option:selected').text();
            
            // Vérifier si la fonction nécessite une coopérative
            var cooperativeRequired = ['Responsable Coopérative', 'Chef de Coopérative'].includes(fonctionText);
            
            if (cooperativeRequired) {
                cooperativeSelect.prop('required', true);
                cooperativeSelect.addClass('is-invalid');
                cooperativeSelect.siblings('.form-text').addClass('text-danger').removeClass('text-muted');
            } else {
                cooperativeSelect.prop('required', false);
                cooperativeSelect.removeClass('is-invalid');
                cooperativeSelect.siblings('.form-text').removeClass('text-danger').addClass('text-muted');
            }
        }
    });

    // Gérer la sélection de coopérative avec datalist (Formulaire de création)
    $('#cooperative_display').on('input', function() {
        const input = $(this);
        const value = input.val();
        const datalist = $('#cooperatives-list');
        
        // Trouver l'option correspondante
        const option = datalist.find(`option[value="${value}"]`);
        if (option.length > 0) {
            $('#cooperative_id').val(option.data('id'));
        } else {
            $('#cooperative_id').val('');
        }
    });
    
    // Gérer la sélection de coopérative avec datalist (Formulaire d'édition)
    $('#edit_cooperative_display').on('input', function() {
        const input = $(this);
        const value = input.val();
        const datalist = $('#edit-cooperatives-list');
        
        // Trouver l'option correspondante
        const option = datalist.find(`option[value="${value}"]`);
        if (option.length > 0) {
            $('#edit_cooperative_id').val(option.data('id'));
        } else {
            $('#edit_cooperative_id').val('');
        }
    });

    // Vérifier au chargement de la page
    $('#fonction_id').trigger('change');
    $('#edit_fonction_id').trigger('change');
});
</script>

{{-- Ouvrir automatiquement le modal si des erreurs de validation existent --}}
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('addUserModal'));
        modal.show();
    });
</script>
@endif

<script>
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}
</script>

</body>
</html> 