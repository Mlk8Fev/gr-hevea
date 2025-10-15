<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $cooperative->nom }} - FPH-CI</title>
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
        <!-- Header avec informations de la coopérative -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div>
                <h4 class="fw-bold mb-2 text-primary">
                    <i class="ri-building-line me-2"></i>
                    {{ $cooperative->nom }}
                </h4>
                <p class="text-muted mb-0">
                    Bienvenue {{ $user->full_name }} ! 
                    <span class="text-success">
                        <i class="ri-check-line me-1"></i>
                        Dashboard de votre coopérative
                    </span>
                </p>
                <div class="d-flex align-items-center gap-3 mt-2">
                    <span class="badge bg-info">{{ $cooperative->secteur->code }} - {{ $cooperative->secteur->nom }}</span>
                    <span class="text-muted">•</span>
                    <span class="text-muted">Président: {{ $cooperative->president }}</span>
                </div>
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

        <!-- Statistiques principales -->
        <div class="row mb-24">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-16">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-2">Producteurs</h6>
                                <h4 class="fw-bold text-primary mb-0">{{ number_format($stats['total_producteurs']) }}</h4>
                                <small class="text-success">
                                    <i class="ri-arrow-up-line me-1"></i>
                                    Membres actifs
                                </small>
                            </div>
                            <div class="w-48-px h-48-px radius-12 d-flex justify-content-center align-items-center bg-primary-100">
                                <i class="ri-user-3-line text-primary text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 mb-16">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-2">Tickets de Pesée</h6>
                                <h4 class="fw-bold text-info mb-0">{{ number_format($stats['total_tickets']) }}</h4>
                                <small class="text-info">
                                    <i class="ri-scales-line me-1"></i>
                                    Total enregistrés
                                </small>
                            </div>
                            <div class="w-48-px h-48-px radius-12 d-flex justify-content-center align-items-center bg-info-100">
                                <i class="ri-scales-line text-info text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 mb-16">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-2">Poids du Mois</h6>
                                <h4 class="fw-bold text-success mb-0">{{ number_format($stats['poids_total_mois'], 2) }} kg</h4>
                                <small class="text-success">
                                    <i class="ri-calendar-line me-1"></i>
                                    {{ now()->format('F Y') }}
                                </small>
                            </div>
                            <div class="w-48-px h-48-px radius-12 d-flex justify-content-center align-items-center bg-success-100">
                                <i class="ri-scales-3-line text-success text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 col-md-6 mb-16">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-2">Chiffre d'Affaires</h6>
                                <h4 class="fw-bold text-warning mb-0">{{ number_format($stats['montant_total_mois'], 0) }} FCFA</h4>
                                <small class="text-warning">
                                    <i class="ri-money-dollar-circle-line me-1"></i>
                                    {{ now()->format('F Y') }}
                                </small>
                            </div>
                            <div class="w-48-px h-48-px radius-12 d-flex justify-content-center align-items-center bg-warning-100">
                                <i class="ri-money-dollar-circle-line text-warning text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accès rapide aux sections -->
        <div class="row mb-24">
            <div class="col-12">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="mb-0">
                            <i class="ri-dashboard-3-line me-2"></i>
                            Accès Rapide
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 mb-16">
                                <a href="{{ route('cooperative.producteurs.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-primary hover-text-white transition-all">
                                    <i class="ri-user-3-line text-2xl mb-8"></i>
                                    <span class="fw-semibold">Mes Producteurs</span>
                                    <small class="text-muted">{{ $stats['total_producteurs'] }} membres</small>
                                </a>
                            </div>
                            
                            <div class="col-xl-3 col-lg-6 col-md-6 mb-16">
                                <a href="{{ route('cooperative.tickets-pesee.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-info hover-text-white transition-all">
                                    <i class="ri-scales-line text-2xl mb-8"></i>
                                    <span class="fw-semibold">Tickets de Pesée</span>
                                    <small class="text-muted">{{ $stats['total_tickets'] }} tickets</small>
                                </a>
                            </div>
                            
                            <div class="col-xl-3 col-lg-6 col-md-6 mb-16">
                                <a href="{{ route('cooperative.connaissements.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-success hover-text-white transition-all">
                                    <i class="ri-truck-line text-2xl mb-8"></i>
                                    <span class="fw-semibold">Livraisons</span>
                                    <small class="text-muted">{{ $stats['total_connaissements'] }} connaissements</small>
                                </a>
                            </div>
                            
                            <div class="col-xl-3 col-lg-6 col-md-6 mb-16">
                                <a href="{{ route('cooperative.factures.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-20 radius-12 border-2 hover-bg-warning hover-text-white transition-all">
                                    <i class="ri-file-list-3-line text-2xl mb-8"></i>
                                    <span class="fw-semibold">Mes Factures</span>
                                    <small class="text-muted">{{ $stats['total_factures'] }} factures</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières activités -->
        <div class="row">
            <div class="col-xl-6 col-lg-12 mb-24">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">
                                <i class="ri-scales-line me-2"></i>
                                Derniers Tickets de Pesée
                            </h6>
                            <a href="{{ route('cooperative.tickets-pesee.index') }}" class="btn btn-sm btn-outline-primary">
                                Voir tout
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        @if($recentTickets->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($recentTickets as $ticket)
                                <div class="d-flex align-items-center justify-content-between p-16 radius-8 bg-light">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-info-100">
                                            <i class="ri-scales-line text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $ticket->numero_ticket }}</h6>
                                            <small class="text-muted">
                                                {{ $ticket->poids_net }} kg • 
                                                {{ $ticket->connaissement->secteur->code ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-sm text-muted">{{ $ticket->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm fw-semibold text-success">{{ $ticket->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-24">
                                <i class="ri-scales-line text-4xl text-muted mb-16"></i>
                                <p class="text-muted mb-0">Aucun ticket de pesée récent</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6 col-lg-12 mb-24">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">
                                <i class="ri-file-list-3-line me-2"></i>
                                Dernières Factures
                            </h6>
                            <a href="{{ route('cooperative.factures.index') }}" class="btn btn-sm btn-outline-primary">
                                Voir tout
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        @if($recentFactures->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($recentFactures as $facture)
                                <div class="d-flex align-items-center justify-content-between p-16 radius-8 bg-light">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100">
                                            <i class="ri-file-list-3-line text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $facture->numero_facture }}</h6>
                                            <small class="text-muted">
                                                {{ number_format($facture->montant_ttc, 0) }} FCFA • 
                                                <span class="badge bg-{{ $facture->statut === 'payee' ? 'success' : ($facture->statut === 'validee' ? 'info' : 'warning') }}">
                                                    {{ ucfirst($facture->statut) }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-sm text-muted">{{ $facture->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm fw-semibold text-success">{{ $facture->created_at->format('H:i') }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-24">
                                <i class="ri-file-list-3-line text-4xl text-muted mb-16"></i>
                                <p class="text-muted mb-0">Aucune facture récente</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')

</body>
</html>
