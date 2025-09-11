@extends("layouts.app")

@section("title", "Statistiques")

@section("content")
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Statistiques Basiques</h6>
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

            <!-- Filtres par Date -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-filter-2-line"></i> Filtres de Période
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.statistiques.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label for="date_debut" class="form-label">Date de Début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                           value="{{ $dateDebut->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="date_fin" class="form-label">Date de Fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                           value="{{ $dateFin->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-search-line"></i> Filtrer
                                    </button>
                                    <a href="{{ route('admin.statistiques.avancees') }}" class="btn btn-outline-info">
                                        <i class="ri-bar-chart-line"></i> Statistiques Avancées
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    <!-- KPIs Principaux -->
    <div class="row gy-4 mb-32">
        <div class="col-xxl-3 col-sm-6">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                        <div>
                            <span class="mb-12 w-44-px h-44-px text-primary-600 bg-primary-light border border-primary-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                <iconify-icon icon="hugeicons:seed" class="icon"></iconify-icon>
                            </span>
                            <span class="mb-1 fw-medium text-secondary-light text-md">Total Graines</span>
                            <h6 class="fw-semibold text-primary-light mb-1">{{ number_format($stats['total_graines'] ?? 0, 0, ',', ' ') }} Kg</h6>
                        </div>
                    </div>
                    <p class="text-sm mb-0">Production totale <span class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+12%</span> ce mois</p>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                        <div>
                            <span class="mb-12 w-44-px h-44-px text-warning-main bg-warning-light border border-warning-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                <iconify-icon icon="hugeicons:invoice-03" class="icon"></iconify-icon>
                            </span>
                            <span class="mb-1 fw-medium text-secondary-light text-md">Tickets Pesée</span>
                            <h6 class="fw-semibold text-primary-light mb-1">{{ number_format($stats['nombre_tickets'] ?? 0) }}</h6>
                        </div>
                    </div>
                    <p class="text-sm mb-0">Documents générés <span class="bg-warning-focus px-1 rounded-2 fw-medium text-warning-main text-sm">+8%</span> ce mois</p>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                        <div>
                            <span class="mb-12 w-44-px h-44-px text-lilac bg-lilac-light border border-lilac-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                <iconify-icon icon="solar:users-group-rounded-outline" class="icon"></iconify-icon>
                            </span>
                            <span class="mb-1 fw-medium text-secondary-light text-md">Coopératives</span>
                            <h6 class="fw-semibold text-primary-light mb-1">{{ number_format($stats['nombre_cooperatives'] ?? 0) }}</h6>
                        </div>
                    </div>
                    <p class="text-sm mb-0">Partenaires actifs <span class="bg-info-focus px-1 rounded-2 fw-medium text-info-main text-sm">Stable</span> cette semaine</p>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-3 col-sm-6">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                        <div>
                            <span class="mb-12 w-44-px h-44-px text-pink bg-pink-light border border-pink-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                <iconify-icon icon="solar:buildings-outline" class="icon"></iconify-icon>
                            </span>
                            <span class="mb-1 fw-medium text-secondary-light text-md">Centres Collecte</span>
                            <h6 class="fw-semibold text-primary-light mb-1">{{ number_format($stats['nombre_centres'] ?? 0) }}</h6>
                        </div>
                    </div>
                    <p class="text-sm mb-0">Points de collecte <span class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+2</span> ce mois</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section principale avec graphique d'évolution -->
    <div class="row gy-4 mb-32">
        <div class="col-xxl-8">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Évolution de la Production</h6>
                        <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                            <option>Cette Année</option>
                            <option>6 Derniers Mois</option>
                            <option>3 Derniers Mois</option>
                            <option>Ce Mois</option>
                        </select>
                    </div>
                    
                    <div class="d-flex flex-wrap align-items-center mt-3 gap-3 mb-28">
                        <div class="d-flex align-items-center gap-2">
                            <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                            <span class="text-secondary-light text-sm fw-semibold">Production: 
                                <span class="text-primary-light fw-bold">{{ number_format($stats['total_graines'] ?? 0, 0, ',', ' ') }} Kg</span>
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="w-12-px h-12-px radius-2 bg-warning-main"></span>
                            <span class="text-secondary-light text-sm fw-semibold">Tickets: 
                                <span class="text-primary-light fw-bold">{{ number_format($stats['nombre_tickets'] ?? 0) }}</span>
                            </span>
                        </div>
                    </div>
                    
                    <div id="evolutionChart" class="apexcharts-tooltip-style-1" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-4">
            <div class="card radius-8 border-0 h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Top Coopératives</h6>
                        <a href="javascript:void(0)" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            Voir Tout
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                    
                    @if(isset($stats['top_cooperatives']) && $stats['top_cooperatives']->count() > 0)
                        <div class="mt-32">
                            @foreach($stats['top_cooperatives'] as $index => $coop)
                            <div class="d-flex align-items-center justify-content-between gap-3 {{ $loop->last ? '' : 'mb-24' }}">
                                <div class="d-flex align-items-center">
                                    <span class="w-40-px h-40-px bg-primary-light text-primary-600 rounded-circle d-flex justify-content-center align-items-center flex-shrink-0 me-12 fw-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-medium">{{ $coop['nom'] }}</h6>
                                        <span class="text-sm text-secondary-light fw-medium">Production totale</span>
                                    </div>
                                </div>
                                <span class="text-primary-light text-md fw-medium">{{ number_format($coop['total_graines'], 0, ',', ' ') }} Kg</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-32">
                            <span class="w-80-px h-80-px bg-neutral-100 rounded-circle d-flex justify-content-center align-items-center mx-auto mb-12">
                                <iconify-icon icon="solar:buildings-outline" class="text-secondary-light text-xxl"></iconify-icon>
                            </span>
                            <h6 class="text-md mb-4">Aucune donnée disponible</h6>
                            <p class="text-sm text-secondary-light mb-0">Les données des coopératives apparaîtront ici</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Statut des Tickets</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="text-success">{{ number_format($stats['tickets_valides'] ?? 0) }}</h4>
                                    <p class="text-muted">Validés</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-warning">{{ number_format($stats['tickets_en_attente'] ?? 0) }}</h4>
                                    <p class="text-muted">En Attente</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-info">{{ number_format($stats['moyenne_poids_net'] ?? 0, 2) }}</h4>
                                    <p class="text-muted">Poids Moyen (Kg)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Évolution Mensuelle</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="evolutionChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Apex Chart js -->
<script src="{{ asset('wowdash/js/lib/apexcharts.min.js') }}"></script>
<script>
// Configuration des graphiques ApexCharts style WowDash
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation des graphiques WowDash...');
    
    // Données par défaut si aucune donnée n'est disponible
    const defaultData = [
        { mois: '2024-01', total: 1200 },
        { mois: '2024-02', total: 1850 },
        { mois: '2024-03', total: 2100 },
        { mois: '2024-04', total: 1950 },
        { mois: '2024-05', total: 2400 },
        { mois: '2024-06', total: 2800 }
    ];
    
    const evolutionData = @json($stats['evolution_mensuelle'] ?? []);
    const chartData = evolutionData.length > 0 ? evolutionData : defaultData;
    
    // Graphique d'évolution style WowDash
    if (document.getElementById('evolutionChart')) {
        var options = {
            series: [{
                name: 'Production (Kg)',
                data: chartData.map(item => Math.round(item.total || 0))
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                fontFamily: 'Inter, sans-serif'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#487FFF']
            },
            xaxis: {
                categories: chartData.map(item => {
                    const [year, month] = item.mois.split('-');
                    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 
                                      'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                    return monthNames[parseInt(month) - 1] + ' ' + year;
                }),
                labels: {
                    style: {
                        colors: '#64748B',
                        fontSize: '12px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748B',
                        fontSize: '12px'
                    },
                    formatter: function (value) {
                        return Math.round(value).toLocaleString() + ' Kg';
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.4,
                    gradientToColors: ['#487FFF'],
                    inverseColors: false,
                    opacityFrom: 0.8,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            colors: ['#487FFF'],
            grid: {
                show: true,
                borderColor: '#E2E8F0',
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                },
                y: {
                    formatter: function (value) {
                        return Math.round(value).toLocaleString() + ' Kg';
                    }
                }
            },
            markers: {
                size: 0,
                strokeWidth: 0,
                hover: {
                    size: 6
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#evolutionChart"), options);
        chart.render();
        console.log('✅ Graphique d\'évolution créé');
    }
});
</script>
@endpush
