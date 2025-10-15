<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs d'Audit - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
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
            <h6 class="fw-semibold mb-0">Logs d'Audit</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Logs d'Audit</li>
            </ul>
        </div>

        <!-- Statistiques -->
        <div class="row g-3 mb-24">
            <div class="col-md-3">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-primary-100 text-primary">
                            <i class="ri-bar-chart-line text-xl"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">{{ number_format($stats['total']) }}</h6>
                            <span class="text-muted text-sm">Total des actions</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-success-100 text-success">
                            <i class="ri-check-line text-xl"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">{{ number_format($stats['successful']) }}</h6>
                            <span class="text-muted text-sm">Actions réussies</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-danger-100 text-danger">
                            <i class="ri-error-warning-line text-xl"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">{{ number_format($stats['failed']) }}</h6>
                            <span class="text-muted text-sm">Actions échouées</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-warning-100 text-warning">
                            <i class="ri-percent-line text-xl"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">{{ $stats['success_rate'] }}%</h6>
                            <span class="text-muted text-sm">Taux de réussite</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card p-24 radius-12 border-0 shadow-sm mb-24">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Action</label>
                    <select name="action" class="form-select">
                        <option value="">Toutes les actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ $action }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Module</label>
                    <select name="module" class="form-select">
                        <option value="">Tous les modules</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                {{ ucfirst($module) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Utilisateur</label>
                    <select name="user_id" class="form-select">
                        <option value="">Tous les utilisateurs</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date de début</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date de fin</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="is_successful" class="form-select">
                        <option value="">Tous</option>
                        <option value="1" {{ request('is_successful') === '1' ? 'selected' : '' }}>Succès</option>
                        <option value="0" {{ request('is_successful') === '0' ? 'selected' : '' }}>Échec</option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1"></i>
                            Filtrer
                        </button>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">
                            <i class="ri-search-line me-1"></i>
                            Réinitialiser
                        </a>
                        <a href="{{ route('admin.audit-logs.export-pdf', request()->query()) }}" class="btn btn-outline-danger">
                            <i class="ri-search-line me-1"></i>
                            Export PDF
                        </a>
                        <a href="{{ route('admin.audit-logs.export-excel', request()->query()) }}" class="btn btn-outline-success">
                            <i class="ri-search-line me-1"></i>
                            Export Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des logs -->
        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <span class="text-md fw-medium text-secondary-light mb-0">Logs d'Audit</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 fw-semibold text-secondary-light text-sm py-16 px-24">Date/Heure & Objet</th>
                                <th class="border-0 fw-semibold text-secondary-light text-sm py-16 px-24">Action & Module</th>
                                <th class="border-0 fw-semibold text-secondary-light text-sm py-16 px-24">Utilisateur</th>
                                <th class="border-0 fw-semibold text-secondary-light text-sm py-16 px-24">Description</th>
                                <th class="border-0 fw-semibold text-secondary-light text-sm py-16 px-24">IP & Statut</th>
                                <th class="border-0 fw-semibold text-secondary-light text-sm py-16 px-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                            <tr>
                                <!-- Date/Heure & Objet -->
                                <td class="py-16 px-24">
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-calendar-line text-primary text-sm"></i>
                                            <span class="fw-semibold text-dark text-sm">{{ $log->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-time-line text-muted text-sm"></i>
                                            <span class="text-muted text-sm">{{ $log->created_at->format('H:i:s') }}</span>
                                        </div>
                                        @if($log->object_type && $log->object_id)
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <i class="ri-file-text-line text-warning text-sm"></i>
                                                <span class="text-dark text-sm fw-medium">{{ $log->object_type }} #{{ $log->object_id }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Action & Module -->
                                <td class="py-16 px-24">
                                    <div class="d-flex flex-column gap-2">
                                        <span class="badge bg-{{ $log->action == 'CREATE' ? 'success' : ($log->action == 'UPDATE' ? 'primary' : ($log->action == 'DELETE' ? 'danger' : 'warning')) }} text-sm">
                                            {{ $log->action_name }}
                                        </span>
                                        <span class="text-dark fw-medium text-sm">{{ $log->module_name }}</span>
                                    </div>
                                </td>
                                
                                <!-- Utilisateur -->
                                <td class="py-16 px-24">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm bg-primary-100 text-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ri-user-line text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark text-sm">{{ $log->user_name ?? 'Système' }}</span>
                                            @if($log->user_role)
                                                <br><small class="text-muted text-xs">{{ ucfirst($log->user_role) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Description -->
                                <td class="py-16 px-24">
                                    <span class="text-dark text-sm">{{ Str::limit($log->description, 60) }}</span>
                                </td>
                                
                                <!-- IP & Statut -->
                                <td class="py-16 px-24">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="ri-global-line text-muted text-sm"></i>
                                            <span class="text-muted text-sm">{{ $log->ip_address }}</span>
                                        </div>
                                        @if($log->is_successful)
                                            <span class="badge bg-success text-sm">
                                                <i class="ri-check-line me-1"></i>
                                                Succès
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-sm">
                                                <i class="ri-close-line me-1"></i>
                                                Échec
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Actions -->
                                <td class="py-16 px-24">
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('admin.audit-logs.show', $log) }}" class="btn btn-outline-primary btn-sm" title="Voir les détails">
                                            <i class="ri-eye-line text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-24 px-24 text-center">
                                    <div class="d-flex flex-column align-items-center gap-3">
                                        <i class="ri-file-list-line text-4xl text-muted"></i>
                                        <div>
                                            <h6 class="fw-semibold text-dark mb-1">Aucun log d'audit trouvé</h6>
                                            <p class="text-muted mb-0">Aucun log ne correspond aux critères de recherche.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination avec filtres préservés -->
        @if($auditLogs->hasPages())
        <div class="row mt-24">
            <div class="col-12">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <!-- Informations de pagination -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted text-sm">
                                Affichage de 
                                <span class="fw-semibold text-primary">{{ $auditLogs->firstItem() }}</span>
                                à 
                                <span class="fw-semibold text-primary">{{ $auditLogs->lastItem() }}</span>
                                sur 
                                <span class="fw-semibold text-primary">{{ $auditLogs->total() }}</span>
                                logs d'audit
                            </span>
                        </div>

                        <!-- Navigation pagination intelligente -->
                        <nav aria-label="Navigation des pages">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Page précédente --}}
                                @if($auditLogs->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link bg-light border-0 text-muted">
                                            Précédent
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ $auditLogs->appends(request()->query())->previousPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
                                            Précédent
                                        </a>
                                    </li>
                                @endif

                                {{-- Pages intelligentes --}}
                                @php
                                    $currentPage = $auditLogs->currentPage();
                                    $lastPage = $auditLogs->lastPage();
                                    $startPage = max(1, $currentPage - 2);
                                    $endPage = min($lastPage, $currentPage + 2);
                                @endphp

                                {{-- Première page --}}
                                @if($startPage > 1)
                                    <li class="page-item">
                                        <a href="{{ $auditLogs->appends(request()->query())->url(1) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">1</a>
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
                                            <a href="{{ $auditLogs->appends(request()->query())->url($page) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $page }}</a>
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
                                        <a href="{{ $auditLogs->appends(request()->query())->url($lastPage) }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">{{ $lastPage }}</a>
                                    </li>
                                @endif

                                {{-- Page suivante --}}
                                @if($auditLogs->hasMorePages())
                                    <li class="page-item">
                                        <a href="{{ $auditLogs->appends(request()->query())->nextPageUrl() }}" class="page-link bg-white border-0 text-primary hover-bg-primary hover-text-white transition-all">
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
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html>
