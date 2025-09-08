<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Connaissement - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Détails du Connaissement</h6>
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
                <li class="fw-medium">{{ $connaissement->numero_livraison }}</li>
            </ul>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <!-- Status Badge -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="ri-information-line me-2"></i>
                    <strong>Statut:</strong>
                    @if($connaissement->statut === 'programme')
                        <span class="badge bg-warning ms-2">Programmé</span>
                    @elseif($connaissement->statut === 'valide')
                        <span class="badge bg-success ms-2">Validé pour ticket de pesée</span>
                    @else
                        <span class="badge bg-secondary ms-2">Archivé</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="mb-0">Informations du Connaissement</h6>
            </div>
            <div class="card-body p-24">
                
                <!-- Informations de départ -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <iconify-icon icon="majesticons:map-pin-line" class="icon me-2"></iconify-icon>
                            Informations de Départ
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Secteur</label>
                            <p class="form-control-plaintext">{{ $connaissement->secteur ? $connaissement->secteur->nom . ' (' . $connaissement->secteur->code . ')' : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Coopérative</label>
                            <p class="form-control-plaintext">{{ $connaissement->cooperative->nom }} ({{ $connaissement->cooperative->code }})</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Centre de Collecte</label>
                            <p class="form-control-plaintext">{{ $connaissement->centreCollecte->nom }} ({{ $connaissement->centreCollecte->code }})</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lieu de Départ</label>
                            <p class="form-control-plaintext">{{ $connaissement->lieu_depart }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sous-Préfecture</label>
                            <p class="form-control-plaintext">{{ $connaissement->sous_prefecture }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Informations transport -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <iconify-icon icon="majesticons:truck-line" class="icon me-2"></iconify-icon>
                            Informations Transport
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nom du Transporteur</label>
                            <p class="form-control-plaintext">{{ $connaissement->transporteur_nom }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Immatriculation</label>
                            <p class="form-control-plaintext">{{ $connaissement->transporteur_immatriculation }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nom du Chauffeur</label>
                            <p class="form-control-plaintext">{{ $connaissement->chauffeur_nom }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type de Destinataire</label>
                            <p class="form-control-plaintext">{{ ucfirst($connaissement->destinataire_type) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Informations cargaison -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <iconify-icon icon="majesticons:package-line" class="icon me-2"></iconify-icon>
                            Informations Cargaison
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre de Sacs</label>
                            <p class="form-control-plaintext">{{ $connaissement->nombre_sacs }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Poids Brut Estimé (kg)</label>
                            <p class="form-control-plaintext">{{ number_format($connaissement->poids_brut_estime, 2) }}</p>
                        </div>
                    </div>
                    
                    @if($connaissement->signature_cooperative)
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Signature Coopérative</label>
                            <p class="form-control-plaintext">{{ $connaissement->signature_cooperative }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Informations de programmation -->
                @if($connaissement->date_reception)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <iconify-icon icon="lucide:calendar" class="icon me-2"></iconify-icon>
                            Informations de Programmation
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date de Réception</label>
                            <p class="form-control-plaintext">{{ $connaissement->date_reception->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Heure d'Arrivée</label>
                            <p class="form-control-plaintext">{{ $connaissement->heure_arrivee }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Informations de validation -->
                @if($connaissement->statut === 'valide' && $connaissement->poids_net_reel)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <iconify-icon icon="lucide:check-circle" class="icon me-2"></iconify-icon>
                            Informations de Validation
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Poids Net Réel (kg)</label>
                            <p class="form-control-plaintext">{{ number_format($connaissement->poids_net_reel, 2) }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date de Validation</label>
                            <p class="form-control-plaintext">{{ $connaissement->date_validation_reelle->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Boutons d'action -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.connaissements.index') }}" class="btn btn-secondary">
                        <iconify-icon icon="lucide:arrow-left" class="icon me-1"></iconify-icon>
                        Retour à la liste
                    </a>
                    <a href="{{ route('admin.connaissements.edit', $connaissement) }}" class="btn btn-warning">
                        <iconify-icon icon="lucide:edit" class="icon me-1"></iconify-icon>
                        Modifier
                    </a>
                    @if($connaissement->statut === 'programme' && !$connaissement->date_reception)
                        <a href="{{ route('admin.connaissements.program', $connaissement) }}" class="btn btn-primary">
                            <iconify-icon icon="lucide:calendar" class="icon me-1"></iconify-icon>
                            Programmer
                        </a>
                    @endif
                    @if($connaissement->statut === 'programme' && $connaissement->date_reception)
                        <a href="{{ route('admin.connaissements.validate', $connaissement) }}" class="btn btn-success">
                            <iconify-icon icon="lucide:check" class="icon me-1"></iconify-icon>
                            Valider
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html>
