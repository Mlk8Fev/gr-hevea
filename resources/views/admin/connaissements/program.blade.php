<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmer la Livraison - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar', ['navigation' => $navigation])
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Programmer la Livraison</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.connaissements.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        Connaissements
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Programmer {{ $connaissement->numero }}</li>
            </ul>
        </div>
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>
                <strong>Erreurs :</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="mb-0">Informations de Programmation</h6>
            </div>
            <div class="card-body p-24">
                <!-- Informations du connaissement -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <iconify-icon icon="majesticons:file-list-line" class="icon me-2"></iconify-icon>
                            Détails du Connaissement
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Numéro :</label>
                            <p class="form-control-plaintext">{{ $connaissement->numero }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Coopérative :</label>
                            <p class="form-control-plaintext">{{ $connaissement->cooperative->nom }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Centre de Collecte :</label>
                            <p class="form-control-plaintext">{{ $connaissement->centreCollecte->nom }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Transporteur :</label>
                            <p class="form-control-plaintext">{{ $connaissement->transporteur_nom }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Poids Brut Estimé :</label>
                            <p class="form-control-plaintext">{{ number_format($connaissement->poids_brut_estime, 2) }} kg</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Nombre de Sacs :</label>
                            <p class="form-control-plaintext">{{ $connaissement->nombre_sacs }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Formulaire de programmation -->
                <form action="{{ route('admin.connaissements.store-program', $connaissement) }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <iconify-icon icon="lucide:calendar" class="icon me-2"></iconify-icon>
                                Programmation de la Livraison
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_reception" class="form-label">Date de Réception *</label>
                                <input type="date" class="form-control @error('date_reception') is-invalid @enderror" 
                                       id="date_reception" name="date_reception" 
                                       value="{{ old('date_reception') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('date_reception')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="heure_arrivee" class="form-label">Heure d'Arrivée *</label>
                                <input type="time" class="form-control @error('heure_arrivee') is-invalid @enderror" 
                                       id="heure_arrivee" name="heure_arrivee" 
                                       value="{{ old('heure_arrivee') }}" required>
                                @error('heure_arrivee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>Information :</strong> La programmation permettra de planifier la réception de la livraison au centre de collecte.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.connaissements.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="ri-calendar-line me-1"></i> Programmer la Livraison
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html> 