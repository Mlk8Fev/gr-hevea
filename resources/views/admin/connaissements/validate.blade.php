<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valider le Connaissement - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Valider le Connaissement</h6>
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
                <li class="fw-medium">Valider {{ $connaissement->numero }}</li>
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
                <h6 class="mb-0">Validation du Connaissement</h6>
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
                    
                    @if($connaissement->date_reception)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Date de Réception Programmée :</label>
                            <p class="form-control-plaintext">{{ $connaissement->date_reception->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Heure d'Arrivée Programmée :</label>
                            <p class="form-control-plaintext">{{ $connaissement->heure_arrivee }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Formulaire de validation -->
                <form action="{{ route('admin.connaissements.store-validation', $connaissement) }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <iconify-icon icon="lucide:check" class="icon me-2"></iconify-icon>
                                Validation de la Livraison
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="poids_net_reel" class="form-label">Poids Net Réel (kg) *</label>
                                <input type="number" step="0.01" class="form-control @error('poids_net_reel') is-invalid @enderror" 
                                       id="poids_net_reel" name="poids_net_reel" 
                                       value="{{ old('poids_net_reel') }}" 
                                       placeholder="Poids réel mesuré" min="0.01" required>
                                @error('poids_net_reel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Poids réel mesuré à l'arrivée du camion
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="signature_fphci" class="form-label">Signature FPH-CI</label>
                                <textarea class="form-control @error('signature_fphci') is-invalid @enderror" 
                                          id="signature_fphci" name="signature_fphci" rows="3" 
                                          placeholder="Signature du contrôleur usine">{{ old('signature_fphci') }}</textarea>
                                @error('signature_fphci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="ri-error-warning-line me-2"></i>
                        <strong>Attention :</strong> Une fois validé pour ticket de pesée, le connaissement ne pourra plus être modifié.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.connaissements.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-check-line me-1"></i> Valider le Connaissement
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