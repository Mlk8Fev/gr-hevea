<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FPH-CI</title>
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
                    <i class="ri-check-line me-2"></i>
                    Dashboard
                </h4>
                <p class="text-muted mb-0">
                    Bienvenue {{ auth()->user()->full_name }} ! 
                    <span class="text-success">
                        <i class="ri-search-line me-1"></i>
                        Connexion réussie au système
                    </span>
                </p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <div class="text-sm text-muted">{{ now()->format('l, d F Y') }}</div>
                    <div class="fw-semibold text-primary">{{ now()->format('H:i') }}</div>
                </div>
                <div class="w-48-px h-48-px radius-12 d-flex justify-content-center align-items-center bg-primary-100">
                    <i class="ri-dashboard-line text-primary text-xl"></i>
                </div>
            </div>
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

        <!-- KPIs Principaux -->
        <div class="row gy-4 mb-24">
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card h-100 radius-12 border-0 shadow-sm hover-shadow-lg transition-all">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <div class="w-56-px h-56-px radius-12 d-flex justify-content-center align-items-center bg-primary-100">
                                <i class="ri-user-3-line text-primary text-2xl"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0 fw-bold text-primary">{{ \App\Models\Producteur::count() }}</h3>
                                <small class="text-muted">Producteurs</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-sm text-muted">Total enregistrés</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-arrow-up-line text-success text-sm"></i>
                                <small class="text-success fw-medium">+8%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card h-100 radius-12 border-0 shadow-sm hover-shadow-lg transition-all">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <div class="w-56-px h-56-px radius-12 d-flex justify-content-center align-items-center bg-success-100">
                                <i class="ri-building-line text-success text-2xl"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0 fw-bold text-success">{{ \App\Models\Cooperative::count() }}</h3>
                                <small class="text-muted">Coopératives</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-sm text-muted">Actives</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-arrow-up-line text-success text-sm"></i>
                                <small class="text-success fw-medium">+12%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card h-100 radius-12 border-0 shadow-sm hover-shadow-lg transition-all">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <div class="w-56-px h-56-px radius-12 d-flex justify-content-center align-items-center bg-warning-100">
                                <i class="ri-truck-line text-warning text-2xl"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0 fw-bold text-warning">{{ \App\Models\Connaissement::count() }}</h3>
                                <small class="text-muted">Livraisons</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-sm text-muted">Ce mois</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-arrow-up-line text-success text-sm"></i>
                                <small class="text-success fw-medium">+15%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card h-100 radius-12 border-0 shadow-sm hover-shadow-lg transition-all">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <div class="w-56-px h-56-px radius-12 d-flex justify-content-center align-items-center bg-info-100">
                                <i class="ri-scales-line text-info text-2xl"></i>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0 fw-bold text-info">{{ \App\Models\TicketPesee::where('statut', 'valide')->count() }}</h3>
                                <small class="text-muted">Tickets Validés</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-sm text-muted">En attente paiement</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-arrow-up-line text-success text-sm"></i>
                                <small class="text-success fw-medium">+22%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides - Masquées pour les responsables de coopératives -->
        @if(auth()->user()->role !== 'rcoop')
        <div class="row gy-4 mb-24">
            <div class="col-12">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-20">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="ri-user-line text-primary"></i>
                            Actions Rapides
                        </h5>
                        <span class="badge bg-primary-100 text-primary-600 px-12 py-4 radius-8">
                            <i class="ri-search-line me-1"></i>
                            Accès direct
                        </span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <a href="{{ route('admin.producteurs.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-primary hover-text-white transition-all">
                                <i class="ri-user-3-line text-2xl mb-8"></i>
                                <span class="fw-semibold">Producteurs</span>
                                <small class="text-muted">Gérer les producteurs</small>
                            </a>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-success hover-text-white transition-all">
                                <i class="ri-building-line text-2xl mb-8"></i>
                                <span class="fw-semibold">Coopératives</span>
                                <small class="text-muted">Gestion coopératives</small>
                            </a>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <a href="{{ route('admin.connaissements.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-info hover-text-white transition-all">
                                <i class="ri-truck-line text-2xl mb-8"></i>
                                <span class="fw-semibold">Livraisons</span>
                                <small class="text-muted">Connaissements</small>
                            </a>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-warning hover-text-white transition-all">
                                <i class="ri-money-dollar-circle-line text-2xl mb-8"></i>
                                <span class="fw-semibold">Finance</span>
                                <small class="text-muted">Calculs financiers</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Graphiques et Analyses -->
        <div class="row gy-4 mb-24">
            <div class="col-xxl-8 col-xl-8">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-20">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="ri-user-line text-primary"></i>
                            Activité des 30 derniers jours
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                                <i class="ri-search-line me-1"></i>
                                +18%
                            </span>
                        </div>
                    </div>
                    <div id="chart-activite" style="height: 320px;"></div>
                </div>
            </div>
            
            <div class="col-xxl-4 col-xl-4">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-20">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="ri-map-pin-line text-info"></i>
                            Répartition par Secteur
                        </h5>
                        <span class="badge bg-info-100 text-info-600 px-8 py-2 radius-6">
                            {{ \App\Models\Secteur::count() }} secteurs
                        </span>
                    </div>
                    <div id="chart-secteurs" style="height: 200px;"></div>
                    <div class="mt-16">
                        <div class="d-flex align-items-center justify-content-between mb-8">
                            <span class="text-sm text-muted">Secteurs actifs</span>
                            <span class="fw-semibold text-primary">{{ \App\Models\Secteur::count() }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableaux de données -->
        <div class="row gy-4 mb-24">
            <div class="col-xxl-6 col-xl-6">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-20">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="ri-trophy-line text-warning"></i>
                            Top Coopératives
                        </h5>
                        <span class="badge bg-warning-100 text-warning-600 px-8 py-2 radius-6">
                            Par activité
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Coopérative</th>
                                    <th class="border-0 text-end">Producteurs</th>
                                    <th class="border-0 text-end">Secteur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Cooperative::withCount('producteurs')->with('secteur')->orderBy('producteurs_count', 'desc')->limit(5)->get() as $index => $coop)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary-100 text-primary-600 px-8 py-2 radius-6 fw-semibold">
                                                #{{ $index + 1 }}
                                            </span>
                                            <span class="fw-medium">{{ $coop->nom }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold">{{ $coop->producteurs_count }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success">{{ $coop->secteur->nom ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-xxl-6 col-xl-6">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-20">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="ri-time-line text-info"></i>
                            Activité Récente
                        </h5>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-sm btn-outline-primary">
                            Voir tout
                            <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Action</th>
                                    <th class="border-0">Utilisateur</th>
                                    <th class="border-0 text-end">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\AuditLog::with('user')->latest()->limit(5)->get() as $log)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-primary">{{ $log->action }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $log->user->name ?? 'Système' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-muted">{{ $log->created_at->format('d/m H:i') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Système -->
        <div class="row gy-4">
            <div class="col-12">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-20">
                        <h5 class="mb-0 d-flex align-items-center gap-2">
                            <i class="ri-information-line text-info"></i>
                            Informations Système
                        </h5>
                        <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-6">
                            <i class="ri-check-line me-1"></i>
                            Système opérationnel
                        </span>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="w-64-px h-64-px radius-12 d-flex justify-content-center align-items-center bg-primary-100 mx-auto mb-12">
                                    <i class="ri-database-line text-primary text-2xl"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">Base de Données</h6>
                                <p class="text-muted mb-0">Connectée</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="w-64-px h-64-px radius-12 d-flex justify-content-center align-items-center bg-success-100 mx-auto mb-12">
                                    <i class="ri-shield-check-line text-success text-2xl"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">Sécurité</h6>
                                <p class="text-muted mb-0">2FA Activé</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="w-64-px h-64-px radius-12 d-flex justify-content-center align-items-center bg-warning-100 mx-auto mb-12">
                                    <i class="ri-save-line text-warning text-2xl"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">Sauvegarde</h6>
                                <p class="text-muted mb-0">Automatique</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="w-64-px h-64-px radius-12 d-flex justify-content-center align-items-center bg-info-100 mx-auto mb-12">
                                    <i class="ri-server-line text-info text-2xl"></i>
                                </div>
                                <h6 class="fw-semibold mb-2">Serveur</h6>
                                <p class="text-muted mb-0">Stable</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="{{ asset('wowdash/js/lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/apexcharts.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/iconify-icon.min.js') }}"></script>
<script src="{{ asset('wowdash/js/app.js') }}"></script>

<script>
// Graphique d'activité
if (document.querySelector('#chart-activite')) {
    const options = {
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
            name: 'Activité', 
            data: [12, 19, 15, 25, 22, 18, 28, 32, 29, 35, 38, 42, 45, 48, 52, 49, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92, 95, 98]
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
        colors: ['#2563eb'],
        tooltip: {
            theme: 'light',
            style: { fontSize: '12px' }
        }
    };
    new ApexCharts(document.querySelector('#chart-activite'), options).render();
}

// Graphique secteurs
if (document.querySelector('#chart-secteurs')) {
    const options = {
        chart: { 
            type: 'donut', 
            height: 200,
            background: 'transparent'
        },
        series: [35, 25, 20, 15, 5],
        labels: ['Abengourou', 'Adzope', 'Divo', 'Gagnoa', 'Autres'],
        colors: ['#2563eb', '#16a34a', '#f59e0b', '#dc2626', '#6b7280'],
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
    };
    new ApexCharts(document.querySelector('#chart-secteurs'), options).render();
}
</script>
</body>
</html>