<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Centre de Collecte - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Modifier le Centre de Collecte</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.centres-collecte.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        Centres de Collecte
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Modifier {{ $centres_collecte->nom }}</li>
            </ul>
        </div>
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
        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="mb-0">Informations du Centre de Collecte</h6>
            </div>
            <div class="card-body p-24">
                <form action="{{ route('admin.centres-collecte.update', $centres_collecte) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Code *</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $centres_collecte->code) }}" 
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom *</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" name="nom" value="{{ old('nom', $centres_collecte->nom) }}" 
                                       placeholder="Ex: PK24, COTRAF, etc." required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse *</label>
                        <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                  id="adresse" name="adresse" rows="3" 
                                  placeholder="Adresse complète du centre de collecte" required>{{ old('adresse', $centres_collecte->adresse) }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut *</label>
                        <select class="form-select @error('statut') is-invalid @enderror" 
                                id="statut" name="statut" required>
                            <option value="">Sélectionner un statut</option>
                            <option value="actif" {{ old('statut', $centres_collecte->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ old('statut', $centres_collecte->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.centres-collecte.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Mettre à jour
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