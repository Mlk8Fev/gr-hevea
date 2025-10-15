<!-- Statistiques des Coopératives FPH-CI -->
<div class="row gy-4 mb-32">
    <!-- KPIs Coopératives -->
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-1 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Total Coopératives</p>
                        <h6 class="mb-0">{{ number_format($stats['total_cooperatives'] ?? 0) }}</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-eye-line text-white text-2xl mb-0"></i>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-primary-main">
                        <i class="ri-eye-line text-xs"></i> Actives
                    </span> 
                    Partenaires enregistrés
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-success">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-success rounded-circle">
                        <i class="ri-checkbox-circle-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-success">{{ number_format($stats['cooperatives_avec_sechoir'] ?? 0) }}</h4>
                <p class="text-muted mb-0">Avec Séchoir</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-warning">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-warning rounded-circle">
                        <i class="ri-speed-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-warning">{{ number_format($stats['performance_moyenne'] ?? 0, 1) }}%</h4>
                <p class="text-muted mb-0">Performance Moyenne</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-info">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-info rounded-circle">
                        <i class="ri-map-2-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-info">{{ count($stats['repartition_geographique'] ?? []) }}</h4>
                <p class="text-muted mb-0">Secteurs Couverts</p>
            </div>
        </div>
    </div>
</div>

<!-- Tableau détaillé des coopératives -->
<div class="row mb-32">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="ri-list-check-2 text-primary"></i> Performance Détaillée des Coopératives
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active" onclick="sortTable('production')">
                                <i class="ri-sort-desc"></i> Production
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="sortTable('performance')">
                                <i class="ri-speed-line"></i> Performance
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="sortTable('tickets')">
                                <i class="ri-file-list-line"></i> Tickets
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($stats['cooperatives']) && count($stats['cooperatives']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap" id="cooperativesTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Coopérative</th>
                                    <th>Secteur</th>
                                    <th class="text-end">Production (Kg)</th>
                                    <th class="text-center">Nb Tickets</th>
                                    <th class="text-end">Poids Moyen</th>
                                    <th class="text-center">Séchoir</th>
                                    <th class="text-center">Performance</th>
                                    <th width="120">Progression</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxProduction = collect($stats['cooperatives'])->max('total_graines') ?: 1;
                                @endphp
                                @foreach($stats['cooperatives'] as $index => $coop)
                                    @php
                                        $progression = $maxProduction > 0 ? ($coop['total_graines'] / $maxProduction) * 100 : 0;
                                        $performanceClass = $coop['performance'] >= 80 ? 'success' : ($coop['performance'] >= 60 ? 'warning' : 'danger');
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-muted">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                        {{ strtoupper(substr($coop['nom'], 0, 2)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $coop['nom'] }}</h6>
                                                    <small class="text-muted">Coopérative</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="ri-map-pin-line me-1"></i>
                                                {{ $coop['secteur'] }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold">{{ number_format($coop['total_graines'], 0, ',', ' ') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ number_format($coop['nombre_tickets']) }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="text-muted">{{ number_format($coop['moyenne_poids'] ?? 0, 2) }} Kg</span>
                                        </td>
                                        <td class="text-center">
                                            @if($coop['a_sechoir'] == 'Oui')
                                                <span class="badge bg-success">
                                                    <i class="ri-check-line"></i> Oui
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="ri-close-line"></i> Non
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $performanceClass }}">
                                                {{ number_format($coop['performance'], 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $performanceClass }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $progression }}%"
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ number_format($progression, 1) }}% de la production max">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-group-line text-muted font-size-48 mb-3 d-block"></i>
                        <h5 class="text-muted">Aucune coopérative trouvée</h5>
                        <p class="text-muted">Aucune donnée disponible pour la période sélectionnée</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Répartition géographique -->
<div class="row gy-4 mb-32">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-map-2-line text-success"></i> Répartition Géographique
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['repartition_geographique']) && count($stats['repartition_geographique']) > 0)
                    <canvas id="repartitionChart" height="200"></canvas>
                @else
                    <div class="text-center py-4">
                        <i class="ri-map-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucune donnée géographique disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line text-info"></i> Analyse Séchoirs
                </h5>
            </div>
            <div class="card-body">
                @php
                    $avecSechoir = $stats['cooperatives_avec_sechoir'] ?? 0;
                    $total = $stats['total_cooperatives'] ?? 0;
                    $sansSechoir = $total - $avecSechoir;
                    $pourcentageAvec = $total > 0 ? ($avecSechoir / $total) * 100 : 0;
                    $pourcentageSans = $total > 0 ? ($sansSechoir / $total) * 100 : 0;
                @endphp
                
                <div class="row text-center mb-4">
                    <div class="col-6">
                        <h4 class="text-success mb-2">{{ $avecSechoir }}</h4>
                        <p class="text-muted mb-0">Avec Séchoir</p>
                        <small class="text-success">{{ number_format($pourcentageAvec, 1) }}%</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-danger mb-2">{{ $sansSechoir }}</h4>
                        <p class="text-muted mb-0">Sans Séchoir</p>
                        <small class="text-danger">{{ number_format($pourcentageSans, 1) }}%</small>
                    </div>
                </div>
                
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $pourcentageAvec }}%"
                         data-bs-toggle="tooltip" 
                         title="Avec séchoir: {{ $avecSechoir }} ({{ number_format($pourcentageAvec, 1) }}%)">
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" 
                         style="width: {{ $pourcentageSans }}%"
                         data-bs-toggle="tooltip" 
                         title="Sans séchoir: {{ $sansSechoir }} ({{ number_format($pourcentageSans, 1) }}%)">
                    </div>
                </div>
                
                <div class="text-center">
                    <span class="badge bg-success me-2">
                        <i class="ri-check-circle-line"></i> Avec Séchoir
                    </span>
                    <span class="badge bg-danger">
                        <i class="ri-close-circle-line"></i> Sans Séchoir
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fonctions pour les statistiques des coopératives
function sortTable(criteria) {
    // Logique de tri des tableaux
    console.log('Tri par:', criteria);
    
    // Mise à jour de l'apparence des boutons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('.btn').classList.add('active');
}

// Initialiser les graphiques des coopératives
function initCooperativesCharts() {
    const repartitionData = @json($stats['repartition_geographique'] ?? []);
    
    if (document.getElementById('repartitionChart') && Object.keys(repartitionData).length > 0) {
        const ctx = document.getElementById('repartitionChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(repartitionData),
                datasets: [{
                    data: Object.values(repartitionData),
                    backgroundColor: [
                        '#20c997', '#fd7e14', '#6f42c1', 
                        '#e83e8c', '#6c757d', '#17a2b8'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

// Initialiser les tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    initCooperativesCharts();
});
</script>
@endpush

<style>
.border-left-primary {
    border-left: 4px solid #0d6efd !important;
}

.border-left-success {
    border-left: 4px solid #198754 !important;
}

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.border-left-info {
    border-left: 4px solid #0dcaf0 !important;
}

.avatar-xs {
    width: 2rem;
    height: 2rem;
}

.avatar-title.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>
