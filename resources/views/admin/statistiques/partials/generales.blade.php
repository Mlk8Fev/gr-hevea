<!-- Statistiques Générales Avancées FPH-CI -->
<div class="row gy-4 mb-32">
    <!-- KPIs Principaux Niveau Général -->
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-1 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Total Graines</p>
                        <h6 class="mb-0">{{ number_format($stats['total_graines'] ?? 0, 0, ',', ' ') }} Kg</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-eye-line text-white text-2xl mb-0"></i>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-success-main">
                        <i class="ri-eye-line text-xs"></i> +12%
                    </span> 
                    Production ce mois
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-2 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Tickets Pesée</p>
                        <h6 class="mb-0">{{ number_format($stats['nombre_tickets'] ?? 0) }}</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-eye-line text-white text-2xl mb-0"></i>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-success-main">
                        <i class="ri-eye-line text-xs"></i> +8%
                    </span> 
                    Documents traités ce mois
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Poids Moyen</p>
                        <h6 class="mb-0">{{ number_format($stats['moyenne_poids_net'] ?? 0, 2) }} Kg</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-warning-main rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-eye-line text-white text-2xl mb-0"></i>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-warning-main">
                        <i class="ri-eye-line text-xs"></i> Stable
                    </span> 
                    Par ticket cette période
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-4 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Total Sacs</p>
                        <h6 class="mb-0">{{ number_format($stats['total_sacs'] ?? 0) }}</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-eye-line text-white text-2xl mb-0"></i>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-info-main">
                        <i class="ri-eye-line text-xs"></i> {{ $stats['total_sacs'] ?? 0 }}
                    </span> 
                    Sacs collectés au total
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques et Tableaux -->
<div class="row gy-4 mb-32">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-line-chart-line text-success"></i> Évolution Mensuelle de la Production
                </h5>
            </div>
            <div class="card-body">
                <canvas id="generalEvolutionChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line text-primary"></i> Répartition des Statuts
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-success">{{ number_format($stats['tickets_valides'] ?? 0) }}</h5>
                            <p class="text-muted mb-0">Validés</p>
                        </div>
                        <div class="col-6">
                            <h5 class="text-warning">{{ number_format($stats['tickets_en_attente'] ?? 0) }}</h5>
                            <p class="text-muted mb-0">En Attente</p>
                        </div>
                    </div>
                </div>
                
                @php
                    $totalTickets = ($stats['tickets_valides'] ?? 0) + ($stats['tickets_en_attente'] ?? 0);
                    $pourcentageValides = $totalTickets > 0 ? (($stats['tickets_valides'] ?? 0) / $totalTickets) * 100 : 0;
                    $pourcentageAttente = $totalTickets > 0 ? (($stats['tickets_en_attente'] ?? 0) / $totalTickets) * 100 : 0;
                @endphp
                
                <div class="progress mb-2" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $pourcentageValides }}%" 
                         aria-valuenow="{{ $pourcentageValides }}" 
                         aria-valuemin="0" aria-valuemax="100">
                        {{ number_format($pourcentageValides, 1) }}%
                    </div>
                    <div class="progress-bar bg-warning" role="progressbar" 
                         style="width: {{ $pourcentageAttente }}%" 
                         aria-valuenow="{{ $pourcentageAttente }}" 
                         aria-valuemin="0" aria-valuemax="100">
                        {{ number_format($pourcentageAttente, 1) }}%
                    </div>
                </div>
                
                <small class="text-muted">
                    <span class="badge bg-success me-2">Validés</span>
                    <span class="badge bg-warning">En Attente</span>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Top Coopératives et Secteurs -->
<div class="row gy-4 mb-32">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-trophy-line text-warning"></i> Top Coopératives Productrices
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['top_cooperatives']) && count($stats['top_cooperatives']) > 0)
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">Rang</th>
                                    <th>Coopérative</th>
                                    <th class="text-end">Production (Kg)</th>
                                    <th width="100">Progression</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxProduction = !empty($stats['top_cooperatives']) ? $stats['top_cooperatives'][0]['total'] ?? 1 : 1;
                                @endphp
                                @foreach($stats['top_cooperatives'] as $index => $coop)
                                    @php
                                        $pourcentage = $maxProduction > 0 ? ($coop['total'] / $maxProduction) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($index < 3)
                                                <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger') }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-medium">{{ $coop['nom'] }}</td>
                                        <td class="text-end fw-bold">{{ number_format($coop['total'], 0, ',', ' ') }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                     style="width: {{ $pourcentage }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ri-inbox-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-map-pin-line text-danger"></i> Production par Secteur
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['repartition_secteurs']) && count($stats['repartition_secteurs']) > 0)
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Secteur</th>
                                    <th class="text-end">Production (Kg)</th>
                                    <th width="120">Répartition</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalSecteurs = array_sum(array_column($stats['repartition_secteurs'], 'total'));
                                @endphp
                                @foreach($stats['repartition_secteurs'] as $secteur)
                                    @php
                                        $pourcentage = $totalSecteurs > 0 ? ($secteur['total'] / $totalSecteurs) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="fw-medium">
                                            <i class="ri-building-line text-muted me-2"></i>
                                            {{ $secteur['nom'] }}
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($secteur['total'], 0, ',', ' ') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: {{ $pourcentage }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ number_format($pourcentage, 1) }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ri-inbox-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Résumé des Entités -->
<div class="row mb-32">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-database-2-line text-info"></i> Résumé des Entités Système
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3 border-end">
                            <h4 class="text-primary mb-2">{{ number_format($stats['nombre_cooperatives'] ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <i class="ri-group-line text-primary me-1"></i>
                                Coopératives Totales
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border-end">
                            <h4 class="text-success mb-2">{{ number_format($stats['nombre_centres'] ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <i class="ri-building-2-line text-success me-1"></i>
                                Centres de Collecte
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border-end">
                            <h4 class="text-warning mb-2">{{ number_format($stats['nombre_connaissements'] ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <i class="ri-file-text-line text-warning me-1"></i>
                                Connaissements
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h4 class="text-info mb-2">{{ number_format($stats['nombre_tickets'] ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <i class="ri-file-list-3-line text-info me-1"></i>
                                Tickets Traités
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
