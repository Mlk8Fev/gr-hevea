<!-- Statistiques Logistiques FPH-CI -->
<div class="row gy-4 mb-32">
    <!-- KPIs Logistiques -->
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-1 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Total Connaissements</p>
                        <h6 class="mb-0">{{ number_format($stats['total_connaissements'] ?? 0) }}</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-eye-line text-white text-2xl mb-0"></i>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-success-main">
                        <i class="ri-eye-line text-xs"></i> +5%
                    </span> 
                    Documents créés ce mois
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-2 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Connaissements Validés</p>
                        <h6 class="mb-0">{{ number_format($stats['connaissements_valides'] ?? 0) }}</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-eye-line text-white text-2xl mb-0"></i>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-success-main">
                        <i class="ri-eye-line text-xs"></i> Validés
                    </span> 
                    Statut traitement
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Temps Moyen</p>
                        <h6 class="mb-0">{{ number_format($stats['temps_moyen_traitement'] ?? 0, 1) }}h</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-warning-main rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-eye-line text-white text-2xl mb-0"></i>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-warning-main">
                        <i class="ri-eye-line text-xs"></i> Traitement
                    </span> 
                    Délai moyen par document
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-xxl-3 col-sm-6">
        <div class="card shadow-none border bg-gradient-start-4 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Efficacité</p>
                        <h6 class="mb-0">{{ number_format(($stats['connaissements_valides'] ?? 0) / max(($stats['total_connaissements'] ?? 1), 1) * 100, 1) }}%</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                        <i class="ri-truck-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-info">{{ count($stats['transporteurs_actifs'] ?? []) }}</h4>
                <p class="text-muted mb-0">Transporteurs Actifs</p>
            </div>
        </div>
    </div>
</div>

<!-- Centres de Collecte Actifs -->
<div class="row gy-4 mb-32">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-building-2-line text-primary"></i> Performance des Centres de Collecte
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['centres_actifs']) && count($stats['centres_actifs']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Centre de Collecte</th>
                                    <th class="text-center">Connaissements</th>
                                    <th class="text-center">Efficacité</th>
                                    <th width="150">Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['centres_actifs'] as $centre)
                                    @php
                                        $efficaciteClass = $centre->connaissements_count >= 10 ? 'success' : ($centre->connaissements_count >= 5 ? 'warning' : 'danger');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                        <i class="ri-building-line"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $centre->nom }}</h6>
                                                    <small class="text-muted">Centre de Collecte</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $centre->connaissements_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $efficaciteClass }}">
                                                {{ $centre->connaissements_count >= 10 ? 'Excellente' : ($centre->connaissements_count >= 5 ? 'Bonne' : 'Faible') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $maxConnaissements = $stats['centres_actifs']->max('connaissements_count') ?: 1;
                                                $pourcentage = ($centre->connaissements_count / $maxConnaissements) * 100;
                                            @endphp
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $efficaciteClass }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $pourcentage }}%"
                                                     data-bs-toggle="tooltip" 
                                                     title="{{ number_format($pourcentage, 1) }}% de performance">
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
                        <i class="ri-building-2-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucun centre de collecte actif</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line text-success"></i> Statut des Connaissements
                </h5>
            </div>
            <div class="card-body">
                @php
                    $totalConn = ($stats['connaissements_valides'] ?? 0) + ($stats['connaissements_en_attente'] ?? 0);
                    $pourcentageValides = $totalConn > 0 ? (($stats['connaissements_valides'] ?? 0) / $totalConn) * 100 : 0;
                    $pourcentageAttente = $totalConn > 0 ? (($stats['connaissements_en_attente'] ?? 0) / $totalConn) * 100 : 0;
                @endphp
                
                <div class="text-center mb-4">
                    <canvas id="connaissementsChart" height="150"></canvas>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-success">{{ $stats['connaissements_valides'] ?? 0 }}</h5>
                        <p class="text-muted mb-0">Validés</p>
                        <small class="text-success">{{ number_format($pourcentageValides, 1) }}%</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-warning">{{ $stats['connaissements_en_attente'] ?? 0 }}</h5>
                        <p class="text-muted mb-0">En Attente</p>
                        <small class="text-warning">{{ number_format($pourcentageAttente, 1) }}%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transporteurs et Routes -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-truck-line text-warning"></i> Top Transporteurs
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['transporteurs_actifs']) && count($stats['transporteurs_actifs']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap">
                            <thead>
                                <tr>
                                    <th>Transporteur</th>
                                    <th class="text-center">Livraisons</th>
                                    <th width="100">Activité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['transporteurs_actifs']->take(10) as $transporteur)
                                    @php
                                        $maxLivraisons = $stats['transporteurs_actifs']->first()->nombre_livraisons ?? 1;
                                        $pourcentage = ($transporteur->nombre_livraisons / $maxLivraisons) * 100;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <div class="avatar-title bg-soft-warning text-warning rounded-circle">
                                                        <i class="ri-truck-line"></i>
                                                    </div>
                                                </div>
                                                <span class="fw-medium">{{ $transporteur->transporteur }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $transporteur->nombre_livraisons }}</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-warning" 
                                                     role="progressbar" 
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
                        <i class="ri-truck-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucun transporteur actif</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-route-line text-info"></i> Routes les Plus Utilisées
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['routes_populaires']) && count($stats['routes_populaires']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th class="text-center">Fréquence</th>
                                    <th width="80">Usage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['routes_populaires']->take(8) as $route)
                                    @php
                                        $maxFrequence = $stats['routes_populaires']->first()->nombre_livraisons ?? 1;
                                        $pourcentage = ($route->nombre_livraisons / $maxFrequence) * 100;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-map-pin-line text-muted me-2"></i>
                                                <div>
                                                    <span class="fw-medium text-primary">{{ $route->origine }}</span>
                                                    <i class="ri-arrow-right-line mx-1 text-muted"></i>
                                                    <span class="text-success">{{ $route->destination }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $route->nombre_livraisons }}</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-info" 
                                                     role="progressbar" 
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
                        <i class="ri-route-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucune route enregistrée</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Métriques de Performance -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-dashboard-line text-danger"></i> Tableau de Bord Logistique
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>
                            <h5 class="text-primary">{{ number_format($stats['temps_moyen_traitement'] ?? 0, 1) }}h</h5>
                            <p class="text-muted mb-0">Temps Moyen de Traitement</p>
                            <small class="text-muted">
                                @if(($stats['temps_moyen_traitement'] ?? 0) <= 24)
                                    <span class="text-success">Excellent</span>
                                @elseif(($stats['temps_moyen_traitement'] ?? 0) <= 48)
                                    <span class="text-warning">Acceptable</span>
                                @else
                                    <span class="text-danger">Lent</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-success text-success rounded-circle">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                            </div>
                            <h5 class="text-success">{{ number_format($pourcentageValides, 1) }}%</h5>
                            <p class="text-muted mb-0">Taux de Validation</p>
                            <small class="text-muted">
                                @if($pourcentageValides >= 90)
                                    <span class="text-success">Excellent</span>
                                @elseif($pourcentageValides >= 75)
                                    <span class="text-warning">Bon</span>
                                @else
                                    <span class="text-danger">À améliorer</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-warning text-warning rounded-circle">
                                    <i class="ri-truck-line"></i>
                                </div>
                            </div>
                            <h5 class="text-warning">{{ count($stats['transporteurs_actifs'] ?? []) }}</h5>
                            <p class="text-muted mb-0">Transporteurs Actifs</p>
                            <small class="text-muted">Cette période</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-info text-info rounded-circle">
                                    <i class="ri-route-line"></i>
                                </div>
                            </div>
                            <h5 class="text-info">{{ count($stats['routes_populaires'] ?? []) }}</h5>
                            <p class="text-muted mb-0">Routes Utilisées</p>
                            <small class="text-muted">Différentes routes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialiser les graphiques logistiques
function initLogisticsCharts() {
    // Graphique en doughnut pour les connaissements
    const connaissementsValides = {{ $stats['connaissements_valides'] ?? 0 }};
    const connaissementsAttente = {{ $stats['connaissements_en_attente'] ?? 0 }};
    
    if (document.getElementById('connaissementsChart')) {
        const ctx = document.getElementById('connaissementsChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Validés', 'En Attente'],
                datasets: [{
                    data: [connaissementsValides, connaissementsAttente],
                    backgroundColor: ['#198754', '#ffc107'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 10,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initLogisticsCharts();
    
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
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

.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-soft-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-soft-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-soft-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
}
</style>
