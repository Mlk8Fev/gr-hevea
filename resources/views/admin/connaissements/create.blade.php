<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Connaissement - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
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
            <h6 class="fw-semibold mb-0">Nouveau Connaissement</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
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
                <li class="fw-medium">Nouveau</li>
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
                <h6 class="mb-0">Informations du Connaissement</h6>
            </div>
            <div class="card-body p-24">
                <form action="{{ route('admin.connaissements.store') }}" method="POST">
                    @csrf
                    
                    <!-- Informations de départ -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="ri-map-pin-line"></i>
                                Informations de Départ
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="secteur_id" class="form-label">Secteur *</label>
                                <select class="form-select @error('secteur_id') is-invalid @enderror" 
                                        id="secteur_id" name="secteur_id" required>
                                    <option value="">Sélectionner un secteur</option>
                                    @foreach($secteurs as $secteur)
                                        <option value="{{ $secteur->id }}" {{ old('secteur_id') == $secteur->id ? 'selected' : '' }}>
                                            {{ $secteur->nom }} ({{ $secteur->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('secteur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cooperative_id" class="form-label">Coopérative *</label>
                                <input type="text" class="form-control @error('cooperative_id') is-invalid @enderror" 
                                       id="cooperative_display" name="cooperative_display" 
                                       placeholder="Tapez le nom de la coopérative..." 
                                       list="cooperatives-list" 
                                       value="{{ old('cooperative_id') ? ($cooperatives->find(old('cooperative_id'))->nom ?? '') . ' (' . ($cooperatives->find(old('cooperative_id'))->code ?? '') . ')' : '' }}" 
                                       required>
                                <datalist id="cooperatives-list">
                                    @foreach($cooperatives as $cooperative)
                                        <option value="{{ $cooperative->nom }} ({{ $cooperative->code }})" data-id="{{ $cooperative->id }}">
                                    @endforeach
                                </datalist>
                                <input type="hidden" id="cooperative_id" name="cooperative_id" value="{{ old('cooperative_id') }}">
                                @error('cooperative_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="centre_collecte_id" class="form-label">Lieu de livraison *</label>
                                <select class="form-select @error('centre_collecte_id') is-invalid @enderror" 
                                        id="centre_collecte_id" name="centre_collecte_id" required>
                                    <option value="">Sélectionner un lieu de livraison</option>
                                    @foreach($centresCollecte as $centre)
                                        <option value="{{ $centre->id }}" {{ old('centre_collecte_id') == $centre->id ? 'selected' : '' }}>
                                            {{ $centre->nom }} ({{ $centre->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('centre_collecte_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lieu_depart" class="form-label">Lieu de Départ *</label>
                                <input type="text" class="form-control @error('lieu_depart') is-invalid @enderror" 
                                       id="lieu_depart" name="lieu_depart" value="{{ old('lieu_depart') }}" required>
                                @error('lieu_depart')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sous_prefecture" class="form-label">Sous-Préfecture *</label>
                                <input type="text" class="form-control @error('sous_prefecture') is-invalid @enderror" 
                                       id="sous_prefecture" name="sous_prefecture" value="{{ old('sous_prefecture') }}" required>
                                @error('sous_prefecture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations transport -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="ri-truck-line"></i>
                                Informations Transport
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="transporteur_nom" class="form-label">Nom du Transporteur *</label>
                                <input type="text" class="form-control @error('transporteur_nom') is-invalid @enderror" 
                                       id="transporteur_nom" name="transporteur_nom" value="{{ old('transporteur_nom') }}" required>
                                @error('transporteur_nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="transporteur_immatriculation" class="form-label">Immatriculation *</label>
                                <input type="text" class="form-control @error('transporteur_immatriculation') is-invalid @enderror" 
                                       id="transporteur_immatriculation" name="transporteur_immatriculation" value="{{ old('transporteur_immatriculation') }}" required>
                                @error('transporteur_immatriculation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="chauffeur_nom" class="form-label">Nom du Chauffeur *</label>
                                <input type="text" class="form-control @error('chauffeur_nom') is-invalid @enderror" 
                                       id="chauffeur_nom" name="chauffeur_nom" value="{{ old('chauffeur_nom') }}" required>
                                @error('chauffeur_nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="destinataire_type" class="form-label">Type de Destinataire *</label>
                                <select class="form-select @error('destinataire_type') is-invalid @enderror" 
                                        id="destinataire_type" name="destinataire_type" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="entrepot" {{ old('destinataire_type') == 'entrepot' ? 'selected' : '' }}>Entrepôt</option>
                                    <option value="cooperative" {{ old('destinataire_type') == 'cooperative' ? 'selected' : '' }}>Coopérative</option>
                                    <option value="acheteur" {{ old('destinataire_type') == 'acheteur' ? 'selected' : '' }}>Acheteur</option>
                                </select>
                                @error('destinataire_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations cargaison -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="ri-package-line"></i>
                                Informations Cargaison
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_sacs" class="form-label">Nombre de Sacs *</label>
                                <input type="number" class="form-control @error('nombre_sacs') is-invalid @enderror" 
                                       id="nombre_sacs" name="nombre_sacs" value="{{ old('nombre_sacs') }}" min="1" required>
                                @error('nombre_sacs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="poids_brut_estime" class="form-label">Poids Brut Estimé (kg) *</label>
                                <input type="number" step="0.01" class="form-control @error('poids_brut_estime') is-invalid @enderror" 
                                       id="poids_brut_estime" name="poids_brut_estime" value="{{ old('poids_brut_estime') }}" min="0.01" required>
                                @error('poids_brut_estime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="signature_cooperative" class="form-label">Signature Coopérative</label>
                                <textarea class="form-control @error('signature_cooperative') is-invalid @enderror" 
                                          id="signature_cooperative" name="signature_cooperative" rows="3">{{ old('signature_cooperative') }}</textarea>
                                @error('signature_cooperative')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.connaissements.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line"></i>
                            Créer le Connaissement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer la sélection de coopérative avec datalist
    const cooperativeDisplay = document.getElementById('cooperative_display');
    const cooperativeHidden = document.getElementById('cooperative_id');
    
    cooperativeDisplay.addEventListener('input', function() {
        const input = this;
        const value = input.value;
        const datalist = document.getElementById('cooperatives-list');
        
        // Trouver l'option correspondante
        const option = datalist.querySelector(`option[value="${value}"]`);
        if (option) {
            cooperativeHidden.value = option.getAttribute('data-id');
        } else {
            cooperativeHidden.value = '';
        }
    });
});
</script>
</body>
</html>
