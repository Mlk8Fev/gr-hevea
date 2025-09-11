<!-- Statistiques de Qualité WowDash -->
<div class="row gy-4 mb-32">
    <!-- KPIs Qualité -->
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-success">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-success rounded-circle">
                        <i class="ri-star-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-success">{{ number_format($stats['gp_moyen'] ?? 0, 2) }}</h4>
                <p class="text-muted mb-0">GP Moyen</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-primary">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-primary rounded-circle">
                        <i class="ri-drop-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-primary">{{ number_format($stats['taux_humidite_moyen'] ?? 0, 1) }}%</h4>
                <p class="text-muted mb-0">Taux d'Humidité Moyen</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-warning">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-warning rounded-circle">
                        <i class="ri-leaf-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-warning">{{ number_format($stats['taux_impuretes_moyen'] ?? 0, 1) }}%</h4>
                <p class="text-muted mb-0">Taux d'Impuretés Moyen</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-info">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-info rounded-circle">
                        <i class="ri-scales-3-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-info">{{ number_format($stats['poids_100_graines_moyen'] ?? 0, 1) }}g</h4>
                <p class="text-muted mb-0">Poids 100 Graines Moyen</p>
            </div>
        </div>
    </div>
</div>

<!-- Distribution de la Qualité -->
<div class="row gy-4 mb-32">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line text-primary"></i> Distribution de la Qualité
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['distribution_qualite']) && count($stats['distribution_qualite']) > 0)
                    <div class="text-center mb-4">
                        <canvas id="qualiteChart" height="150"></canvas>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap">
                            <thead>
                                <tr>
                                    <th>Qualité</th>
                                    <th class="text-center">Nombre</th>
                                    <th width="120">Répartition</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalQualite = $stats['distribution_qualite']->sum('nombre');
                                    $qualiteColors = ['Excellente' => 'success', 'Bonne' => 'primary', 'Moyenne' => 'warning', 'Faible' => 'danger'];
                                @endphp
                                @foreach($stats['distribution_qualite'] as $qualite)
                                    @php
                                        $pourcentage = $totalQualite > 0 ? ($qualite->nombre / $totalQualite) * 100 : 0;
                                        $colorClass = $qualiteColors[$qualite->qualite] ?? 'secondary';
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $colorClass }}">{{ $qualite->qualite }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold">{{ $qualite->nombre }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar bg-{{ $colorClass }}" 
                                                         role="progressbar" 
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
                        <i class="ri-star-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucune donnée de qualité disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pulse-line text-success"></i> Indicateurs de Qualité Détaillés
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center p-3 border-end">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-success text-success rounded-circle">
                                    <i class="ri-star-fill"></i>
                                </div>
                            </div>
                            <h5 class="text-success">{{ number_format($stats['ga_moyen'] ?? 0, 3) }}</h5>
                            <p class="text-muted mb-0">GA Moyen</p>
                            <small class="text-muted">Germination Acidité</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center p-3">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-info text-info rounded-circle">
                                    <i class="ri-drop-fill"></i>
                                </div>
                            </div>
                            <h5 class="text-info">{{ number_format($stats['me_moyen'] ?? 0, 3) }}%</h5>
                            <p class="text-muted mb-0">ME Moyen</p>
                            <small class="text-muted">Matière Étrangère</small>
                        </div>
                    </div>
                </div>
                
                <div class="border-top pt-3 mt-3">
                    <h6 class="text-muted mb-3">Évaluation Globale de la Qualité</h6>
                    @php
                        $gpMoyen = $stats['gp_moyen'] ?? 0;
                        $qualiteGlobale = $gpMoyen >= 12 ? 'Excellente' : ($gpMoyen >= 10 ? 'Bonne' : ($gpMoyen >= 8 ? 'Moyenne' : 'Faible'));
                        $qualiteColor = $gpMoyen >= 12 ? 'success' : ($gpMoyen >= 10 ? 'primary' : ($gpMoyen >= 8 ? 'warning' : 'danger'));
                        $qualitePourcentage = min(($gpMoyen / 15) * 100, 100);
                    @endphp
                    
                    <div class="text-center mb-3">
                        <span class="badge bg-{{ $qualiteColor }} fs-6 px-3 py-2">{{ $qualiteGlobale }}</span>
                    </div>
                    
                    <div class="progress mb-2" style="height: 15px;">
                        <div class="progress-bar bg-{{ $qualiteColor }}" 
                             role="progressbar" 
                             style="width: {{ $qualitePourcentage }}%"
                             aria-valuenow="{{ $qualitePourcentage }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($qualitePourcentage, 1) }}%
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Faible (0-8)</small>
                        <small class="text-muted">Excellente (12+)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Coopératives par Qualité -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="ri-trophy-line text-warning"></i> Classement des Coopératives par Qualité
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active" onclick="sortQualityTable('gp')">
                                <i class="ri-star-line"></i> GP
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="sortQualityTable('humidite')">
                                <i class="ri-drop-line"></i> Humidité
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="sortQualityTable('impuretes')">
                                <i class="ri-leaf-line"></i> Impuretés
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($stats['qualite_par_cooperative']) && count($stats['qualite_par_cooperative']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap" id="qualityTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">Rang</th>
                                    <th>Coopérative</th>
                                    <th class="text-center">GP Moyen</th>
                                    <th class="text-center">Humidité (%)</th>
                                    <th class="text-center">Impuretés (%)</th>
                                    <th class="text-center">Qualité</th>
                                    <th width="120">Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['qualite_par_cooperative'] as $index => $coop)
                                    @php
                                        $gpMoyen = $coop['gp_moyen'] ?? 0;
                                        $qualiteLevel = $gpMoyen >= 12 ? 'Excellente' : ($gpMoyen >= 10 ? 'Bonne' : ($gpMoyen >= 8 ? 'Moyenne' : 'Faible'));
                                        $qualiteColor = $gpMoyen >= 12 ? 'success' : ($gpMoyen >= 10 ? 'primary' : ($gpMoyen >= 8 ? 'warning' : 'danger'));
                                        $performance = min(($gpMoyen / 15) * 100, 100);
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($index < 3)
                                                <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'success') }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <div class="avatar-title bg-soft-{{ $qualiteColor }} text-{{ $qualiteColor }} rounded-circle">
                                                        {{ strtoupper(substr($coop['nom'], 0, 2)) }}
                                                    </div>
                                                </div>
                                                <span class="fw-medium">{{ $coop['nom'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-{{ $qualiteColor }}">{{ number_format($gpMoyen, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $humidite = $coop['taux_humidite_moyen'] ?? 0;
                                                $humiditeColor = $humidite <= 12 ? 'success' : ($humidite <= 15 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="text-{{ $humiditeColor }}">{{ number_format($humidite, 1) }}%</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $impuretes = $coop['taux_impuretes_moyen'] ?? 0;
                                                $impuretesColor = $impuretes <= 2 ? 'success' : ($impuretes <= 5 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="text-{{ $impuretesColor }}">{{ number_format($impuretes, 1) }}%</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $qualiteColor }}">{{ $qualiteLevel }}</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $qualiteColor }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $performance }}%"
                                                     data-bs-toggle="tooltip" 
                                                     title="Performance: {{ number_format($performance, 1) }}%">
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
                        <i class="ri-star-line text-muted font-size-48 mb-3 d-block"></i>
                        <h5 class="text-muted">Aucune donnée de qualité</h5>
                        <p class="text-muted">Aucune donnée disponible pour la période sélectionnée</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tendances de Qualité -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-line-chart-line text-info"></i> Évolution de la Qualité dans le Temps
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['tendances_qualite']) && count($stats['tendances_qualite']) > 0)
                    <canvas id="tendancesChart" height="80"></canvas>
                @else
                    <div class="text-center py-4">
                        <i class="ri-line-chart-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucune donnée de tendance disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fonctions pour les statistiques de qualité
function sortQualityTable(criteria) {
    console.log('Tri par:', criteria);
    
    // Mise à jour de l'apparence des boutons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('.btn').classList.add('active');
}

// Initialiser les graphiques de qualité
function initQualityCharts() {
    // Graphique de distribution de la qualité
    const distributionData = @json($stats['distribution_qualite'] ?? []);
    
    if (document.getElementById('qualiteChart') && distributionData.length > 0) {
        const ctx = document.getElementById('qualiteChart').getContext('2d');
        
        const labels = distributionData.map(item => item.qualite);
        const data = distributionData.map(item => item.nombre);
        const colors = {
            'Excellente': '#198754',
            'Bonne': '#0d6efd', 
            'Moyenne': '#ffc107',
            'Faible': '#dc3545'
        };
        const backgroundColors = labels.map(label => colors[label] || '#6c757d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
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
    
    // Graphique des tendances
    const tendancesData = @json($stats['tendances_qualite'] ?? []);
    
    if (document.getElementById('tendancesChart') && tendancesData.length > 0) {
        const ctx = document.getElementById('tendancesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: tendancesData.map(item => {
                    const [year, month] = item.mois.split('-');
                    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 
                                      'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                    return monthNames[parseInt(month) - 1] + ' ' + year;
                }),
                datasets: [{
                    label: 'GP Moyen',
                    data: tendancesData.map(item => item.gp_moyen),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    yAxisID: 'y'
                }, {
                    label: 'Humidité Moyenne (%)',
                    data: tendancesData.map(item => item.humidite_moyenne),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Période'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'GP'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Humidité (%)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    initQualityCharts();
    
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush

<style>
.border-left-success {
    border-left: 4px solid #198754 !important;
}

.border-left-primary {
    border-left: 4px solid #0d6efd !important;
}

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.border-left-info {
    border-left: 4px solid #0dcaf0 !important;
}

.bg-soft-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-soft-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-soft-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-soft-danger {
    background-color: rgba(220, 53, 69, 0.1) !important;
}
</style>
