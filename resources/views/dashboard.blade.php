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

        <!-- Statistiques -->
        <div class="row gy-4 mb-24">
            @foreach($stats as $statKey => $stat)
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="radius-8 h-100 text-center p-20 bg-{{ $stat['color'] }}-light">
                    <span class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-xl mb-12 bg-{{ $stat['color'] }}-200 border border-{{ $stat['color'] }}-400 text-{{ $stat['color'] }}-600">
                        <i class="{{ $stat['icon'] }}"></i>
                    </span>
                    <span class="text-neutral-700 d-block">{{ $stat['label'] }}</span>
                    <h6 class="mb-0 mt-4">{{ $stat['count'] }}</h6>
                </div>
            </div>
            @endforeach
        </div>

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
    </div>
</main>

<!-- Scripts -->
<script src="{{ asset('wowdash/js/lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/iconify-icon.min.js') }}"></script>
<script src="{{ asset('wowdash/js/app.js') }}"></script>
</body>
</html>