<!-- Statistiques Financières FPH-CI -->
<div class="row gy-4 mb-32">
    <!-- KPIs Financiers -->
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-success">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-success rounded-circle">
                        <i class="ri-money-dollar-circle-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-success">{{ number_format($stats['montant_total_factures'] ?? 0, 0, ',', ' ') }} FCFA</h4>
                <p class="text-muted mb-0">Chiffre d'Affaires Total</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-primary">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-primary rounded-circle">
                        <i class="ri-cash-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-primary">{{ number_format($stats['montant_paye'] ?? 0, 0, ',', ' ') }} FCFA</h4>
                <p class="text-muted mb-0">Montant Encaissé</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-warning">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-warning rounded-circle">
                        <i class="ri-time-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-warning">{{ number_format($stats['montant_en_attente'] ?? 0, 0, ',', ' ') }} FCFA</h4>
                <p class="text-muted mb-0">En Attente de Paiement</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card text-center border-left-info">
            <div class="card-body">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title bg-info rounded-circle">
                        <i class="ri-percent-line font-size-24 text-white"></i>
                    </div>
                </div>
                <h4 class="text-info">{{ number_format($stats['taux_recouvrement'] ?? 0, 1) }}%</h4>
                <p class="text-muted mb-0">Taux de Recouvrement</p>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques Financiers -->
<div class="row gy-4 mb-32">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-line-chart-line text-success"></i> Évolution des Revenus
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenusChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line text-primary"></i> Répartition des Paiements
                </h5>
            </div>
            <div class="card-body">
                @php
                    $totalFactures = ($stats['factures_payees'] ?? 0) + ($stats['factures_en_attente'] ?? 0);
                    $pourcentagePayees = $totalFactures > 0 ? (($stats['factures_payees'] ?? 0) / $totalFactures) * 100 : 0;
                    $pourcentageAttente = $totalFactures > 0 ? (($stats['factures_en_attente'] ?? 0) / $totalFactures) * 100 : 0;
                @endphp
                
                <div class="text-center mb-4">
                    <canvas id="paiementsChart" height="150"></canvas>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-success">{{ $stats['factures_payees'] ?? 0 }}</h5>
                        <p class="text-muted mb-0">Factures Payées</p>
                        <small class="text-success">{{ number_format($pourcentagePayees, 1) }}%</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-warning">{{ $stats['factures_en_attente'] ?? 0 }}</h5>
                        <p class="text-muted mb-0">En Attente</p>
                        <small class="text-warning">{{ number_format($pourcentageAttente, 1) }}%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Coopératives par Revenus et Analyse Prix -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-trophy-line text-warning"></i> Top Coopératives par Revenus
                </h5>
            </div>
            <div class="card-body">
                @if(isset($stats['top_cooperatives_revenus']) && count($stats['top_cooperatives_revenus']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">Rang</th>
                                    <th>Coopérative</th>
                                    <th class="text-end">Montant (FCFA)</th>
                                    <th width="120">Part du CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalChiffre = collect($stats['top_cooperatives_revenus'])->sum('montant_total');
                                @endphp
                                @foreach($stats['top_cooperatives_revenus'] as $index => $coop)
                                    @php
                                        $partCA = $totalChiffre > 0 ? ($coop['montant_total'] / $totalChiffre) * 100 : 0;
                                        $maxMontant = $stats['top_cooperatives_revenus']->first()['montant_total'] ?? 1;
                                        $progression = ($coop['montant_total'] / $maxMontant) * 100;
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-3">
                                                    <div class="avatar-title bg-soft-success text-success rounded-circle">
                                                        {{ strtoupper(substr($coop['nom'], 0, 2)) }}
                                                    </div>
                                                </div>
                                                <span class="fw-medium">{{ $coop['nom'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($coop['montant_total'], 0, ',', ' ') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar bg-success" 
                                                         role="progressbar" 
                                                         style="width: {{ $progression }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ number_format($partCA, 1) }}%</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ri-money-dollar-circle-line text-muted font-size-48 mb-3 d-block"></i>
                        <p class="text-muted">Aucune donnée de revenus disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-calculator-line text-info"></i> Analyse des Prix
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center mb-4">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-info text-info rounded-circle">
                                    <i class="ri-price-tag-3-line"></i>
                                </div>
                            </div>
                            <h3 class="text-info mb-2">{{ number_format($stats['prix_moyen_kg'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                            <p class="text-muted mb-0">Prix Moyen par Kg</p>
                        </div>
                    </div>
                </div>
                
                <div class="border-top pt-3">
                    <div class="row text-center">
                        <div class="col-4">
                            <h6 class="text-success">Minimum</h6>
                            <p class="text-muted mb-0">{{ number_format(($stats['prix_moyen_kg'] ?? 0) * 0.85, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="col-4">
                            <h6 class="text-warning">Moyen</h6>
                            <p class="text-muted mb-0">{{ number_format($stats['prix_moyen_kg'] ?? 0, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="col-4">
                            <h6 class="text-danger">Maximum</h6>
                            <p class="text-muted mb-0">{{ number_format(($stats['prix_moyen_kg'] ?? 0) * 1.15, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>
                
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 15%"></div>
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 70%"></div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 15%"></div>
                </div>
                
                <div class="text-center mt-2">
                    <small class="text-muted">Répartition des prix par qualité</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau de Bord Financier -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-dashboard-line text-danger"></i> Tableau de Bord Financier
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3 border-end">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-success text-success rounded-circle">
                                    <i class="ri-file-list-3-line"></i>
                                </div>
                            </div>
                            <h5 class="text-success">{{ number_format($stats['nombre_factures'] ?? 0) }}</h5>
                            <p class="text-muted mb-0">Total Factures</p>
                            <small class="text-muted">Cette période</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border-end">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                    <i class="ri-percent-line"></i>
                                </div>
                            </div>
                            <h5 class="text-primary">{{ number_format($stats['taux_recouvrement'] ?? 0, 1) }}%</h5>
                            <p class="text-muted mb-0">Taux de Recouvrement</p>
                            <small class="text-muted">
                                @if(($stats['taux_recouvrement'] ?? 0) >= 90)
                                    <span class="text-success">Excellent</span>
                                @elseif(($stats['taux_recouvrement'] ?? 0) >= 75)
                                    <span class="text-warning">Bon</span>
                                @else
                                    <span class="text-danger">À améliorer</span>
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border-end">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-warning text-warning rounded-circle">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                            </div>
                            <h5 class="text-warning">{{ number_format((($stats['montant_total_factures'] ?? 0) / max(($stats['nombre_factures'] ?? 1), 1)), 0, ',', ' ') }}</h5>
                            <p class="text-muted mb-0">Montant Moyen/Facture</p>
                            <small class="text-muted">FCFA</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            @php
                                $evolutionTrend = count($stats['evolution_revenus'] ?? []) > 1 ? 
                                    (collect($stats['evolution_revenus'])->last()['total'] ?? 0) - (collect($stats['evolution_revenus'])->first()['total'] ?? 0) : 0;
                            @endphp
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-soft-info text-info rounded-circle">
                                    <i class="ri-{{ $evolutionTrend >= 0 ? 'arrow-up' : 'arrow-down' }}-line"></i>
                                </div>
                            </div>
                            <h5 class="text-{{ $evolutionTrend >= 0 ? 'success' : 'danger' }}">
                                {{ $evolutionTrend >= 0 ? '+' : '' }}{{ number_format($evolutionTrend, 0, ',', ' ') }}
                            </h5>
                            <p class="text-muted mb-0">Évolution</p>
                            <small class="text-muted">FCFA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialiser les graphiques financiers
function initFinancialCharts() {
    // Graphique d'évolution des revenus
    const evolutionData = @json($stats['evolution_revenus'] ?? []);
    
    if (document.getElementById('revenusChart') && evolutionData.length > 0) {
        const ctx = document.getElementById('revenusChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: evolutionData.map(item => {
                    const [year, month] = item.mois.split('-');
                    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 
                                      'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                    return monthNames[parseInt(month) - 1] + ' ' + year;
                }),
                datasets: [{
                    label: 'Revenus (FCFA)',
                    data: evolutionData.map(item => item.total),
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#198754',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                return 'Revenus: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        });
    }
    
    // Graphique en doughnut pour les paiements
    const facturesPayees = {{ $stats['factures_payees'] ?? 0 }};
    const facturesAttente = {{ $stats['factures_en_attente'] ?? 0 }};
    
    if (document.getElementById('paiementsChart')) {
        const ctx = document.getElementById('paiementsChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Factures Payées', 'En Attente'],
                datasets: [{
                    data: [facturesPayees, facturesAttente],
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
    initFinancialCharts();
    
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
</style>
