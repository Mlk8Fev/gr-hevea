<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
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
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Dashboard</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
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

        @if(auth()->check() && auth()->user()->role === 'agc')
<!-- Header AGC avec secteur -->
<div class="row mb-24">
    <div class="col-12">
        <div class="card p-24 radius-12 bg-gradient-primary text-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-2 text-white">
                        <iconify-icon icon="ri:user-settings-line" class="me-2"></iconify-icon>
                        Tableau de Bord AGC
                    </h4>
                    <p class="mb-0 opacity-75">
                        <iconify-icon icon="ri:map-pin-line" class="me-1"></iconify-icon>
                        Secteur assigné: <strong>{{ auth()->user()->secteur ?? 'Non assigné' }}</strong>
                    </p>
                </div>
                <div class="text-end">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="ri:calendar-line" class="text-xl"></iconify-icon>
                        <span>{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KPIs améliorés -->
        <div class="row gy-4 mb-24">
            @foreach($stats as $statKey => $stat)
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
        <div class="card h-100 radius-12 border-0 shadow-sm">
            <div class="card-body p-24">
                <div class="d-flex align-items-center justify-content-between mb-16">
                    <div class="w-48-px h-48-px radius-12 d-flex justify-content-center align-items-center bg-{{ $stat['color'] }}-100">
                        <iconify-icon icon="{{ $stat['icon'] }}" class="text-{{ $stat['color'] }}-600 text-xl"></iconify-icon>
                    </div>
                    <div class="text-end">
                        <h3 class="mb-0 fw-bold text-{{ $stat['color'] }}-600">{{ $stat['count'] }}</h3>
                        <small class="text-muted">{{ $stat['label'] }}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-sm text-muted">Mon secteur</span>
                    <div class="d-flex align-items-center gap-1">
                        <iconify-icon icon="ri:arrow-up-line" class="text-success text-sm"></iconify-icon>
                        <small class="text-success fw-medium">+12%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Actions rapides AGC -->
<div class="row gy-4 mb-24">
    <div class="col-12">
        <div class="card p-24 radius-12 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-20">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <iconify-icon icon="ri:flashlight-line" class="text-primary"></iconify-icon>
                    Actions Rapides
                </h5>
                <span class="badge bg-primary-100 text-primary-600 px-12 py-4 radius-8">
                    Secteur {{ auth()->user()->secteur ?? 'N/A' }}
                </span>
            </div>
            
            <div class="row g-3">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <a href="{{ route('admin.producteurs.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-primary hover-text-white transition-all">
                        <iconify-icon icon="ri:user-3-line" class="text-2xl mb-8"></iconify-icon>
                        <span class="fw-semibold">Producteurs</span>
                        <small class="text-muted">Gérer les producteurs</small>
                    </a>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <a href="{{ route('admin.connaissements.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-info hover-text-white transition-all">
                        <iconify-icon icon="ri:truck-line" class="text-2xl mb-8"></iconify-icon>
                        <span class="fw-semibold">Connaissements</span>
                        <small class="text-muted">Livraisons</small>
                    </a>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <a href="{{ route('admin.farmer-lists.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-success hover-text-white transition-all">
                        <iconify-icon icon="ri:file-list-line" class="text-2xl mb-8"></iconify-icon>
                        <span class="fw-semibold">Farmer Lists</span>
                        <small class="text-muted">Listes producteurs</small>
                    </a>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <a href="{{ route('admin.tickets-pesee.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-warning hover-text-white transition-all">
                        <iconify-icon icon="ri:scales-line" class="text-2xl mb-8"></iconify-icon>
                        <span class="fw-semibold">Tickets Pesée</span>
                        <small class="text-muted">Pesées</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($extras))
<!-- Graphiques et analyses -->
<div class="row gy-4 mb-24">
    <div class="col-xxl-8 col-xl-8">
        <div class="card p-24 radius-12 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-20">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <iconify-icon icon="ri:bar-chart-2-line" class="text-primary"></iconify-icon>
                    Production des 30 derniers jours
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                        <iconify-icon icon="ri:trending-up-line" class="me-1"></iconify-icon>
                        +15%
                    </span>
                </div>
            </div>
            <div id="chart-livraisons30j" style="height: 320px;"></div>
        </div>
    </div>
    
    <div class="col-xxl-4 col-xl-4">
        <div class="card p-24 radius-12 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-20">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <iconify-icon icon="ri:file-warning-line" class="text-danger"></iconify-icon>
                    Traçabilité
                </h5>
                <span class="badge bg-danger-100 text-danger-600 px-8 py-2 radius-6">
                    {{ $extras['kpis']['completionRate'] ?? 0 }}%
                </span>
            </div>
            <div id="chart-docs" style="height: 200px;"></div>
            <div class="mt-16">
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-sm text-muted">Complétion Farmer Lists</span>
                    <span class="fw-semibold text-primary">{{ $extras['kpis']['completionRate'] ?? 0 }}%</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: {{ $extras['kpis']['completionRate'] ?? 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analyses détaillées -->
<div class="row gy-4 mb-24">
    <div class="col-xxl-6 col-xl-6">
        <div class="card p-24 radius-12 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-20">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <iconify-icon icon="ri:community-line" class="text-success"></iconify-icon>
                    Parcelles par coopérative
                </h5>
                <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                    Top 8
                </span>
            </div>
            <div id="chart-parcelles-coop" style="height: 280px;"></div>
        </div>
    </div>
    
    <div class="col-xxl-6 col-xl-6">
        <div class="card p-24 radius-12 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-20">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <iconify-icon icon="ri:trophy-line" class="text-warning"></iconify-icon>
                    Top coopératives
                </h5>
                <span class="badge bg-warning-100 text-warning-600 px-8 py-2 radius-6">
                    Par poids net
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Coopérative</th>
                            <th class="border-0 text-end">Livraisons</th>
                            <th class="border-0 text-end">Poids (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($extras['tables']['topCoops'] ?? []) as $index => $row)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary-100 text-primary-600 px-8 py-2 radius-6 fw-semibold">
                                        #{{ $index + 1 }}
                                    </span>
                                    <span class="fw-medium">{{ $row['cooperative'] }}</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <span class="fw-semibold">{{ $row['livraisons'] }}</span>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-success">{{ number_format($row['poids_net'], 0, ',', ' ') }}</span>
                            </td>
                        </tr>
                        @endforeach
                        @if(empty($extras['tables']['topCoops']))
                        <tr>
                            <td colspan="3" class="text-center text-muted py-20">
                                <iconify-icon icon="ri:inbox-line" class="text-2xl mb-8"></iconify-icon>
                                <div>Aucune donnée disponible</div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Activité récente -->
<div class="row gy-4">
    <div class="col-12">
        <div class="card p-24 radius-12 border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-20">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <iconify-icon icon="ri:time-line" class="text-info"></iconify-icon>
                    Livraisons récentes
                </h5>
                <a href="{{ route('admin.connaissements.index') }}" class="btn btn-sm btn-outline-primary">
                    Voir tout
                    <iconify-icon icon="ri:arrow-right-line" class="ms-1"></iconify-icon>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">N° Livraison</th>
                            <th class="border-0">Coopérative</th>
                            <th class="border-0">Date</th>
                            <th class="border-0 text-end">Poids net</th>
                            <th class="border-0 text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($extras['tables']['recentLivraisons'] ?? []) as $row)
                        <tr>
                            <td>
                                <span class="fw-semibold text-primary">{{ $row['numero'] }}</span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $row['coop'] }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $row['date'] }}</span>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-success">{{ number_format($row['poids_net'], 0, ',', ' ') }} kg</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                                    <iconify-icon icon="ri:check-line" class="me-1"></iconify-icon>
                                    Validé
                                </span>
                            </td>
                        </tr>
            @endforeach
                        @if(empty($extras['tables']['recentLivraisons']))
                        <tr>
                            <td colspan="5" class="text-center text-muted py-20">
                                <iconify-icon icon="ri:inbox-line" class="text-2xl mb-8"></iconify-icon>
                                <div>Aucune livraison récente</div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('wowdash/js/lib/apexcharts.min.js') }}"></script>
<script>
(function() {
    const series30j = @json($extras['charts']['livraisons30j'] ?? []);
    const docsCompletion = @json($extras['charts']['docsCompletion'] ?? ['complete'=>0,'missing'=>0]);
    const parcellesByCoop = @json($extras['charts']['parcellesByCoop'] ?? ['categories'=>[],'data'=>[]]);

    // Graphique production 30j
    if (document.querySelector('#chart-livraisons30j')) {
        new ApexCharts(document.querySelector('#chart-livraisons30j'), {
            chart: { 
                type: 'area', 
                height: 320, 
                toolbar: { show: false },
                background: 'transparent'
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: { 
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            series: [{ 
                name: 'Poids net (kg)', 
                data: series30j 
            }],
            xaxis: { 
                type: 'category', 
                labels: { rotate: -45 },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            grid: { borderColor: '#f1f5f9' },
            colors: ['#447748'],
            tooltip: {
                theme: 'light',
                style: { fontSize: '12px' }
            }
        }).render();
    }

    // Graphique traçabilité
    if (document.querySelector('#chart-docs')) {
        new ApexCharts(document.querySelector('#chart-docs'), {
            chart: { 
                type: 'donut', 
                height: 200,
                background: 'transparent'
            },
            series: [docsCompletion.complete || 0, docsCompletion.missing || 0],
            labels: ['Complet', 'Manquant'],
            colors: ['#16a34a','#f59e0b'],
            legend: { 
                position: 'bottom',
                fontSize: '12px'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%'
                    }
                }
            },
            tooltip: {
                theme: 'light'
            }
        }).render();
    }

    // Graphique parcelles par coop
    if (document.querySelector('#chart-parcelles-coop')) {
        new ApexCharts(document.querySelector('#chart-parcelles-coop'), {
            chart: { 
                type: 'bar', 
                height: 280, 
                toolbar: { show: false },
                background: 'transparent'
            },
            series: [{ 
                name: 'Parcelles', 
                data: parcellesByCoop.data 
            }],
            xaxis: { 
                categories: parcellesByCoop.categories,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            plotOptions: { 
                bar: { 
                    borderRadius: 6, 
                    columnWidth: '60%' 
                } 
            },
            colors: ['#2563eb'],
            grid: { borderColor: '#f1f5f9' },
            tooltip: {
                theme: 'light'
            }
        }).render();
    }
})();
</script>
@endpush
@endif
@endif

        @if(auth()->check() && auth()->user()->role !== 'agc')
        <!-- Actions rapides -->
        <div class="row gy-4">
            <div class="col-xxl-6">
                <div class="card h-100">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="fw-semibold mb-0">Actions rapides</h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary w-100 d-flex align-items-center gap-2">
                                    <i class="ri-user-add-line"></i>
                                    Nouvel utilisateur
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.cooperatives.create') }}" class="btn btn-success w-100 d-flex align-items-center gap-2">
                                    <i class="ri-building-line"></i>
                                    Nouvelle coopérative
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.producteurs.create') }}" class="btn btn-warning w-100 d-flex align-items-center gap-2">
                                    <i class="ri-user-3-line"></i>
                                    Nouveau producteur
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.tickets-pesee.create') }}" class="btn btn-info w-100 d-flex align-items-center gap-2">
                                    <i class="ri-file-add-line"></i>
                                    Nouveau ticket
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="card h-100">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="fw-semibold mb-0">Activité récente</h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                            <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                <div class="ms-2 me-auto">
                                    <div class="fw-semibold">{{ $notification['title'] }}</div>
                                    <small class="text-secondary-light">{{ $notification['message'] }}</small>
                                </div>
                                <small class="text-muted">{{ $notification['time'] }}</small>
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

<!-- Scripts -->
<script src="{{ asset('wowdash/js/lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/iconify-icon.min.js') }}"></script>
<script src="{{ asset('wowdash/js/app.js') }}"></script>
</body>
</html>