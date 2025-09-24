<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Basiques - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
    @include('partials.sidebar')
    <main class="dashboard-main">
        @include('partials.navbar-header')
        <div class="dashboard-main-body">
            <!-- Header avec filtres -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <div>
                    <h6 class="fw-semibold mb-0">📊 Statistiques Basiques</h6>
                    <p class="text-muted mb-0">Vue d'ensemble de la production et des performances</p>
                </div>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">Statistiques</li>
                </ul>
            </div>

            <!-- Filtres de période -->
            <div class="row mb-24">
                <div class="col-12">
                    <div class="card p-24 radius-12 border-0 shadow-sm">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold"> Date de début</label>
                                <input type="date" name="date_debut" class="form-control" 
                                       value="{{ $dateDebut->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">📅 Date de fin</label>
                                <input type="date" name="date_fin" class="form-control" 
                                       value="{{ $dateFin->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <iconify-icon icon="ri:search-line" class="me-1"></iconify-icon>
                                    Actualiser
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- KPIs Principaux -->
            <div class="row mb-24">
                <div class="col-xl-3 col-md-6 mb-24">
                    <div class="card p-24 radius-12 border-0 shadow-sm h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-8">
                                    <div class="w-40-px h-40-px radius-12 d-flex justify-content-center align-items-center bg-success-100">
                                        <iconify-icon icon="ri:seedling-line" class="text-success text-lg"></iconify-icon>
                                    </div>
                                    <span class="text-muted text-sm fw-medium">Production Totale</span>
                                </div>
                                <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['total_graines'], 0) }} kg</h3>
                                <div class="d-flex align-items-center gap-2 mt-8">
                                    @if($stats['evolution_production'] > 0)
                                        <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                                            <iconify-icon icon="ri:arrow-up-line" class="me-1"></iconify-icon>
                                            +{{ number_format($stats['evolution_production'], 1) }}%
                                        </span>
                                    @elseif($stats['evolution_production'] < 0)
                                        <span class="badge bg-danger-100 text-danger-600 px-8 py-2 radius-6">
                                            <iconify-icon icon="ri:arrow-down-line" class="me-1"></iconify-icon>
                                            {{ number_format($stats['evolution_production'], 1) }}%
                                        </span>
                                    @else
                                        <span class="badge bg-info-100 text-info-600 px-8 py-2 radius-6">
                                            <iconify-icon icon="ri:equal-line" class="me-1"></iconify-icon>
                                            Stable
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-24">
                    <div class="card p-24 radius-12 border-0 shadow-sm h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-8">
                                    <div class="w-40-px h-40-px radius-12 d-flex justify-content-center align-items-center bg-primary-100">
                                        <iconify-icon icon="ri:file-list-3-line" class="text-primary text-lg"></iconify-icon>
                                    </div>
                                    <span class="text-muted text-sm fw-medium">Tickets Validés</span>
                                </div>
                                <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['tickets_valides']) }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-8">
                                    <span class="badge bg-primary-100 text-primary-600 px-8 py-2 radius-6">
                                        {{ number_format($stats['taux_validation'], 1) }}% de validation
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-24">
                    <div class="card p-24 radius-12 border-0 shadow-sm h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-8">
                                    <div class="w-40-px h-40-px radius-12 d-flex justify-content-center align-items-center bg-info-100">
                                        <iconify-icon icon="ri:community-line" class="text-info text-lg"></iconify-icon>
                                    </div>
                                    <span class="text-muted text-sm fw-medium">Coopératives</span>
                                </div>
                                <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['nombre_cooperatives']) }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-8">
                                    <span class="text-muted text-sm">{{ $stats['nombre_secteurs'] }} secteurs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-24">
                    <div class="card p-24 radius-12 border-0 shadow-sm h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-8">
                                    <div class="w-40-px h-40-px radius-12 d-flex justify-content-center align-items-center bg-warning-100">
                                        <iconify-icon icon="ri:user-3-line" class="text-warning text-lg"></iconify-icon>
                                    </div>
                                    <span class="text-muted text-sm fw-medium">Producteurs</span>
                                </div>
                                <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['nombre_producteurs']) }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-8">
                                    <span class="text-muted text-sm">{{ number_format($stats['poids_moyen_par_ticket'], 1) }} kg/ticket</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques et Analyses -->
            <div class="row mb-24">
                <!-- Évolution Quotidienne -->
                <div class="col-xl-8 mb-24">
                    <div class="card p-24 radius-12 border-0 shadow-sm h-100">
                        <div class="d-flex align-items-center justify-content-between mb-20">
                            <h5 class="mb-0 d-flex align-items-center gap-2">
                                <iconify-icon icon="ri:line-chart-line" class="text-primary"></iconify-icon>
                                Évolution Quotidienne de la Production
                            </h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="toggleChart('production')">
                                    <iconify-icon icon="ri:bar-chart-line"></iconify-icon>
                                </button>
                                <button class="btn btn-outline-primary btn-sm" onclick="toggleChart('tickets')">
                                    <iconify-icon icon="ri:file-list-3-line"></iconify-icon>
                                </button>
                            </div>
                        </div>
                        <div id="evolutionChart" style="height: 300px;"></div>
                    </div>
                </div>

                <!-- Répartition par Secteur -->
                <div class="col-xl-4 mb-24">
                    <div class="card p-24 radius-12 border-0 shadow-sm h-100">
                        <h5 class="mb-20 d-flex align-items-center gap-2">
                            <iconify-icon icon="ri:pie-chart-line" class="text-primary"></iconify-icon>
                            Répartition par Secteur
                        </h5>
                        <div id="secteurChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Tableaux de Performance -->
            <div class="row mb-24">
                <!-- Top Coopératives -->
                <div class="col-xl-6 mb-24">
                    <div class="card p-24 radius-12 border-0 shadow-sm h-100">
                        <h5 class="mb-20 d-flex align-items-center gap-2">
                            <iconify-icon icon="ri:trophy-line" class="text-primary"></iconify-icon>
                            Top 5 Coopératives
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Rang</th>
                                        <th class="border-0">Coopérative</th>
                                        <th class="border-0">Production (kg)</th>
                                        <th class="border-0">Tickets</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['top_cooperatives'] as $index => $coop)
                                    <tr>
                                        <td>
                                            @if($index === 0)
                                                <span class="badge bg-warning-100 text-warning-600 px-8 py-2 radius-6"></span>
                                            @elseif($index === 1)
                                                <span class="badge bg-secondary-100 text-secondary-600 px-8 py-2 radius-6"></span>
                                            @elseif($index === 2)
                                                <span class="badge bg-info-100 text-info-600 px-8 py-2 radius-6">🥉</span>
                                            @else
                                                <span class="text-muted fw-semibold">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $coop['nom'] }}</div>
                                            <div class="text-muted text-sm">{{ $coop['secteur'] }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">{{ number_format($coop['total'], 0) }} kg</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-100 text-primary-600 px-6 py-1 radius-4">
                                                {{ $coop['tickets'] }} tickets
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Performance par Centre -->
                <div class="col-xl-6 mb-24">
                    <div class="card p-24 radius-12 border-0 shadow-sm h-100">
                        <h5 class="mb-20 d-flex align-items-center gap-2">
                            <iconify-icon icon="ri:building-2-line" class="text-primary"></iconify-icon>
                            Performance par Centre
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Centre</th>
                                        <th class="border-0">Production (kg)</th>
                                        <th class="border-0">Tickets</th>
                                        <th class="border-0">Moyenne</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['repartition_par_centre'] as $centre)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $centre['centre'] }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">{{ number_format($centre['total'], 0) }} kg</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-100 text-info-600 px-6 py-1 radius-4">
                                                {{ $centre['tickets'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ number_format($centre['total'] / $centre['tickets'], 1) }} kg</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Métriques de Qualité -->
            <div class="row">
                <div class="col-12">
                    <div class="card p-24 radius-12 border-0 shadow-sm">
                        <h5 class="mb-20 d-flex align-items-center gap-2">
                            <iconify-icon icon="ri:star-line" class="text-primary"></iconify-icon>
                            Métriques de Qualité
                        </h5>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="p-16 radius-8 bg-success-100">
                                    <h4 class="text-success fw-bold mb-0">{{ number_format($stats['moyenne_poids_net'], 1) }} kg</h4>
                                    <p class="text-muted mb-0">Poids moyen par ticket</p>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="p-16 radius-8 bg-info-100">
                                    <h4 class="text-info fw-bold mb-0">{{ number_format($stats['poids_max_ticket'], 1) }} kg</h4>
                                    <p class="text-muted mb-0">Poids maximum</p>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="p-16 radius-8 bg-warning-100">
                                    <h4 class="text-warning fw-bold mb-0">{{ number_format($stats['poids_min_ticket'], 1) }} kg</h4>
                                    <p class="text-muted mb-0">Poids minimum</p>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="p-16 radius-8 bg-primary-100">
                                    <h4 class="text-primary fw-bold mb-0">{{ number_format($stats['total_sacs']) }}</h4>
                                    <p class="text-muted mb-0">Total sacs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.wowdash-scripts')
    
    <!-- ApexCharts -->
    <script src="{{ asset('wowdash/js/lib/apexcharts.min.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour les graphiques
        const evolutionData = @json($stats['evolution_quotidienne']);
        const secteurData = @json($stats['repartition_secteurs']);
        
        let currentChartType = 'production';
        
        // Configuration du graphique d'évolution
        const evolutionOptions = {
            series: [{
                name: 'Production (kg)',
                data: evolutionData.map(item => item.total)
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                }
            },
            colors: ['#20c997'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: evolutionData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
                })
            },
            yaxis: {
                title: {
                    text: 'Production (kg)'
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toLocaleString() + " kg"
                    }
                }
            }
        };
        
        // Configuration du graphique de répartition par secteur
        const secteurOptions = {
            series: secteurData.map(item => item.total),
            chart: {
                type: 'donut',
                height: 300
            },
            labels: secteurData.map(item => item.secteur),
            colors: ['#20c997', '#007bff', '#6f42c1', '#fd7e14', '#dc3545', '#28a745'],
            legend: {
                position: 'bottom'
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toLocaleString() + " kg"
                    }
                }
            }
        };
        
        // Initialisation des graphiques
        const evolutionChart = new ApexCharts(document.querySelector("#evolutionChart"), evolutionOptions);
        const secteurChart = new ApexCharts(document.querySelector("#secteurChart"), secteurOptions);
        
        evolutionChart.render();
        secteurChart.render();
        
        // Fonction pour basculer entre production et tickets
        window.toggleChart = function(type) {
            if (type === currentChartType) return;
            
            currentChartType = type;
            
            if (type === 'production') {
                evolutionChart.updateOptions({
                    series: [{
                        name: 'Production (kg)',
                        data: evolutionData.map(item => item.total)
                    }],
                    yaxis: {
                        title: {
                            text: 'Production (kg)'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val.toLocaleString() + " kg"
                            }
                        }
                    }
                });
            } else {
                evolutionChart.updateOptions({
                    series: [{
                        name: 'Nombre de tickets',
                        data: evolutionData.map(item => item.tickets)
                    }],
                    yaxis: {
                        title: {
                            text: 'Nombre de tickets'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " tickets"
                            }
                        }
                    }
                });
            }
        };
    });
    </script>
</body>
</html>
