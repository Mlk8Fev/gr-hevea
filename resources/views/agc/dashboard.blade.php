<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard AGC - FPH-CI</title>
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
        <!-- Header avec salutation personnalisée -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div>
                <h4 class="fw-bold mb-2 text-primary">
                    <i class="ri-dashboard-3-line me-2"></i>
                    Dashboard AGC - {{ $secteur->nom }}
                </h4>
                <p class="text-muted mb-0">
                    Bienvenue {{ $user->full_name }} ! 
                    <span class="text-success">
                        <i class="ri-check-line me-1"></i>
                        Secteur {{ $secteur->code }} - {{ $secteur->nom }}
                    </span>
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-focus text-primary-600 px-3 py-2">
                    <i class="ri-user-line me-1"></i>
                    Assistant Gestion de Qualité
                </span>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="row gy-4 mb-24">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="bg-primary-focus rounded-circle p-3">
                                <i class="ri-group-line text-primary fs-4"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="fw-bold mb-0 text-primary">{{ $stats['total_cooperatives'] }}</h3>
                                <p class="text-muted mb-0 small">Coopératives</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Dans votre secteur</span>
                            <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-eye-line me-1"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="bg-success-focus rounded-circle p-3">
                                <i class="ri-user-3-line text-success fs-4"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="fw-bold mb-0 text-success">{{ $stats['total_producteurs'] }}</h3>
                                <p class="text-muted mb-0 small">Producteurs</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Dans votre secteur</span>
                            <a href="{{ route('admin.producteurs.index') }}" class="btn btn-sm btn-outline-success">
                                <i class="ri-eye-line me-1"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="bg-warning-focus rounded-circle p-3">
                                <i class="ri-file-list-line text-warning fs-4"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="fw-bold mb-0 text-warning">{{ $stats['total_connaissements'] }}</h3>
                                <p class="text-muted mb-0 small">Connaissements</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Dans votre secteur</span>
                            <a href="{{ route('admin.connaissements.index') }}" class="btn btn-sm btn-outline-warning">
                                <i class="ri-eye-line me-1"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="bg-info-focus rounded-circle p-3">
                                <i class="ri-file-chart-line text-info fs-4"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="fw-bold mb-0 text-info">{{ $stats['connaissements_avec_farmer_list'] }}</h3>
                                <p class="text-muted mb-0 small">Farmer Lists</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small">Complétées</span>
                            <a href="{{ route('admin.farmer-lists.index') }}" class="btn btn-sm btn-outline-info">
                                <i class="ri-eye-line me-1"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barres de progression des tâches -->
        <div class="row gy-4 mb-24">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">
                            <i class="ri-file-text-line me-2 text-primary"></i>
                            Documents Coopératives
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold">Progression</span>
                            <span class="badge bg-primary-focus text-primary-600">{{ $stats['progression_cooperatives'] }}%</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $stats['progression_cooperatives'] }}%" 
                                 aria-valuenow="{{ $stats['progression_cooperatives'] }}" 
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>{{ $stats['cooperatives_avec_documents'] }} / {{ $stats['total_cooperatives'] }} complétées</span>
                            <span>{{ $stats['total_cooperatives'] - $stats['cooperatives_avec_documents'] }} restantes</span>
                        </div>
                        @if(count($cooperativesDetails) > 0)
                            <div class="mt-3">
                                <small class="text-muted">Documents manquants par coopérative :</small>
                                <div class="mt-2">
                                    @foreach(array_slice($cooperativesDetails, 0, 3) as $detail)
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="ri-alert-line text-danger"></i>
                                                <span class="small fw-semibold">{{ $detail['cooperative']->nom }} ({{ $detail['cooperative']->code }})</span>
                                            </div>
                                            <div class="ms-4">
                                                <small class="text-muted">
                                                    Manque {{ $detail['total_manquants'] }} document(s) : 
                                                    {{ implode(', ', $detail['documents_manquants']) }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(count($cooperativesDetails) > 3)
                                        <small class="text-muted">+ {{ count($cooperativesDetails) - 3 }} autres coopératives...</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">
                            <i class="ri-user-3-line me-2 text-success"></i>
                            Documents Producteurs
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold">Progression</span>
                            <span class="badge bg-success-focus text-success-600">{{ $stats['progression_producteurs'] }}%</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $stats['progression_producteurs'] }}%" 
                                 aria-valuenow="{{ $stats['progression_producteurs'] }}" 
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>{{ $stats['producteurs_avec_documents'] }} / {{ $stats['total_producteurs'] }} complétés</span>
                            <span>{{ $stats['total_producteurs'] - $stats['producteurs_avec_documents'] }} restants</span>
                        </div>
                        @if(count($producteursDetails) > 0)
                            <div class="mt-3">
                                <small class="text-muted">Documents manquants par producteur :</small>
                                <div class="mt-2">
                                    @foreach(array_slice($producteursDetails, 0, 3) as $detail)
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="ri-alert-line text-danger"></i>
                                                <span class="small fw-semibold">{{ $detail['producteur']->nom }} {{ $detail['producteur']->prenom }}</span>
                                            </div>
                                            <div class="ms-4">
                                                <small class="text-muted">
                                                    Manque {{ $detail['total_manquants'] }} document(s) : 
                                                    {{ implode(', ', $detail['documents_manquants']) }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(count($producteursDetails) > 3)
                                        <small class="text-muted">+ {{ count($producteursDetails) - 3 }} autres producteurs...</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">
                            <i class="ri-file-chart-line me-2 text-warning"></i>
                            Farmer Lists
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold">Progression</span>
                            <span class="badge bg-warning-focus text-warning-600">{{ $stats['progression_farmer_lists'] }}%</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $stats['progression_farmer_lists'] }}%" 
                                 aria-valuenow="{{ $stats['progression_farmer_lists'] }}" 
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>{{ $stats['connaissements_avec_farmer_list'] }} / {{ $stats['total_connaissements'] }} complétées</span>
                            <span>{{ $stats['total_connaissements'] - $stats['connaissements_avec_farmer_list'] }} restantes</span>
                        </div>
                        @if($connaissementsSansFarmerList->count() > 0)
                            <div class="mt-3">
                                <small class="text-muted">Connaissements sans farmer list :</small>
                                <div class="mt-2">
                                    @foreach($connaissementsSansFarmerList->take(3) as $conn)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="ri-alert-line text-warning"></i>
                                            <span class="small">{{ $conn->numero_livraison }} - {{ $conn->cooperative->nom }}</span>
                                        </div>
                                    @endforeach
                                    @if($connaissementsSansFarmerList->count() > 3)
                                        <small class="text-muted">+ {{ $connaissementsSansFarmerList->count() - 3 }} autres...</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Résumé des documents manquants par type -->
        <div class="row gy-4 mb-24">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">
                            <i class="ri-file-list-3-line me-2 text-info"></i>
                            Résumé des Documents Manquants
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="row">
                            <!-- Documents Coopératives -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="ri-building-line me-2"></i>
                                    Documents Coopératives Manquants
                                </h6>
                                @php
                                    $coopDocsManquants = [];
                                    foreach($cooperativesDetails as $detail) {
                                        foreach($detail['documents_manquants'] as $doc) {
                                            $coopDocsManquants[$doc] = ($coopDocsManquants[$doc] ?? 0) + 1;
                                        }
                                    }
                                @endphp
                                @if(count($coopDocsManquants) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($coopDocsManquants as $doc => $count)
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <span class="small">{{ $doc }}</span>
                                            <span class="badge bg-danger-focus text-danger-600">{{ $count }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="ri-check-circle-line text-success fs-3 mb-2"></i>
                                        <p class="text-muted mb-0 small">Tous les documents coopératives sont complets !</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Documents Producteurs -->
                            <div class="col-md-6">
                                <h6 class="text-success mb-3">
                                    <i class="ri-user-line me-2"></i>
                                    Documents Producteurs Manquants
                                </h6>
                                @php
                                    $prodDocsManquants = [];
                                    foreach($producteursDetails as $detail) {
                                        foreach($detail['documents_manquants'] as $doc) {
                                            $prodDocsManquants[$doc] = ($prodDocsManquants[$doc] ?? 0) + 1;
                                        }
                                    }
                                @endphp
                                @if(count($prodDocsManquants) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($prodDocsManquants as $doc => $count)
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <span class="small">{{ $doc }}</span>
                                            <span class="badge bg-danger-focus text-danger-600">{{ $count }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="ri-check-circle-line text-success fs-3 mb-2"></i>
                                        <p class="text-muted mb-0 small">Tous les documents producteurs sont complets !</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row gy-4 mb-24">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">
                            <i class="ri-flashlight-line me-2 text-primary"></i>
                            Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="ri-group-line"></i>
                                    <span>Gérer Coopératives</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.producteurs.index') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="ri-user-3-line"></i>
                                    <span>Gérer Producteurs</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.connaissements.index') }}" class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="ri-file-list-line"></i>
                                    <span>Gérer Connaissements</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.farmer-lists.index') }}" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="ri-file-chart-line"></i>
                                    <span>Gérer Farmer Lists</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activités récentes -->
        @if($recentesActivites->count() > 0)
        <div class="row gy-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">
                            <i class="ri-time-line me-2 text-info"></i>
                            Activités Récentes (7 derniers jours)
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="timeline">
                            @foreach($recentesActivites as $activite)
                            <div class="timeline-item d-flex align-items-start gap-3 mb-3">
                                <div class="bg-{{ $activite['color'] }}-focus rounded-circle p-2">
                                    <i class="{{ $activite['icon'] }} text-{{ $activite['color'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 fw-medium">{{ $activite['message'] }}</p>
                                    <small class="text-muted">{{ $activite['date']->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
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
