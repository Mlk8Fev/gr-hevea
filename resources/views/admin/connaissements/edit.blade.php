<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Connaissement - FPH-CI</title>
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
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">
                Modifier le Connaissement {{ $connaissement->numero }}
            </h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.connaissements.index') }}" class="hover-text-primary">Connaissements</a>
                </li>
                <li>-</li>
                <li class="fw-medium">Modifier {{ $connaissement->numero }}</li>
            </ul>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.connaissements.update', $connaissement) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Informations de Base -->
                <div class="col-lg-6">
                    <div class="card h-100 p-0 radius-12">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="mb-0">Informations de Base</h6>
                        </div>
                        <div class="card-body p-24">
                            <div class="mb-3">
                                <label for="numero" class="form-label">Numéro de Connaissement</label>
                                <input type="text" class="form-control" value="{{ $connaissement->numero }}" readonly>
                                <small class="text-muted">Numéro généré automatiquement</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="cooperative_id" class="form-label">Coopérative *</label>
                                <input type="text" class="form-control @error('cooperative_id') is-invalid @enderror" 
                                       id="cooperative_display" name="cooperative_display" 
                                       placeholder="Tapez le nom de la coopérative..." 
                                       list="cooperatives-list" 
                                       value="{{ $connaissement->cooperative_id ? ($cooperatives->find($connaissement->cooperative_id)->nom ?? '') . ' (' . ($cooperatives->find($connaissement->cooperative_id)->sigle ?? '') . ')' : '' }}" 
                                       required>
                                <datalist id="cooperatives-list">
                                    @foreach($cooperatives as $cooperative)
                                        <option value="{{ $cooperative->nom }} ({{ $cooperative->sigle }})" data-id="{{ $cooperative->id }}">
                                    @endforeach
                                </datalist>
                                <input type="hidden" id="cooperative_id" name="cooperative_id" value="{{ $connaissement->cooperative_id }}">
                                @error('cooperative_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="centre_collecte_id" class="form-label">Centre de Collecte *</label>
                                <select name="centre_collecte_id" id="centre_collecte_id" class="form-select @error('centre_collecte_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un centre de collecte</option>
                                    @foreach($centresCollecte as $centre)
                                        <option value="{{ $centre->id }}" {{ $connaissement->centre_collecte_id == $centre->id ? 'selected' : '' }}>
                                            {{ $centre->code }} - {{ $centre->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('centre_collecte_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut</label>
                                <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror">
                                    <option value="programme" {{ $connaissement->statut == 'programme' ? 'selected' : '' }}>Programmé</option>
                                    <option value="valide" {{ $connaissement->statut == 'valide' ? 'selected' : '' }}>Validé pour ticket de pesée</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations de Transport -->
                <div class="col-lg-6">
                    <div class="card h-100 p-0 radius-12">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="mb-0">Informations de Transport</h6>
                        </div>
                        <div class="card-body p-24">
                            <div class="mb-3">
                                <label for="lieu_depart" class="form-label">Lieu de Départ *</label>
                                <input type="text" name="lieu_depart" id="lieu_depart" class="form-control @error('lieu_depart') is-invalid @enderror" 
                                       value="{{ old('lieu_depart', $connaissement->lieu_depart) }}" required>
                                @error('lieu_depart')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="sous_prefecture" class="form-label">Sous-Préfecture *</label>
                                <input type="text" name="sous_prefecture" id="sous_prefecture" class="form-control @error('sous_prefecture') is-invalid @enderror" 
                                       value="{{ old('sous_prefecture', $connaissement->sous_prefecture) }}" required>
                                @error('sous_prefecture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="transporteur_nom" class="form-label">Nom du Transporteur *</label>
                                <input type="text" name="transporteur_nom" id="transporteur_nom" class="form-control @error('transporteur_nom') is-invalid @enderror" 
                                       value="{{ old('transporteur_nom', $connaissement->transporteur_nom) }}" required>
                                @error('transporteur_nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="transporteur_immatriculation" class="form-label">Immatriculation du Camion *</label>
                                <input type="text" name="transporteur_immatriculation" id="transporteur_immatriculation" class="form-control @error('transporteur_immatriculation') is-invalid @enderror" 
                                       value="{{ old('transporteur_immatriculation', $connaissement->transporteur_immatriculation) }}" required>
                                @error('transporteur_immatriculation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="chauffeur_nom" class="form-label">Nom du Chauffeur *</label>
                                <input type="text" name="chauffeur_nom" id="chauffeur_nom" class="form-control @error('chauffeur_nom') is-invalid @enderror" 
                                       value="{{ old('chauffeur_nom', $connaissement->chauffeur_nom) }}" required>
                                @error('chauffeur_nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-24">
                <!-- Informations de Marchandise -->
                <div class="col-lg-6">
                    <div class="card h-100 p-0 radius-12">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="mb-0">Informations de Marchandise</h6>
                        </div>
                        <div class="card-body p-24">
                            <div class="mb-3">
                                <label for="destinataire_type" class="form-label">Type de Destinataire *</label>
                                <select name="destinataire_type" id="destinataire_type" class="form-select @error('destinataire_type') is-invalid @enderror" required>
                                    <option value="entrepot" {{ $connaissement->destinataire_type == 'entrepot' ? 'selected' : '' }}>Entrepôt</option>
                                    <option value="cooperative" {{ $connaissement->destinataire_type == 'cooperative' ? 'selected' : '' }}>Coopérative</option>
                                    <option value="acheteur" {{ $connaissement->destinataire_type == 'acheteur' ? 'selected' : '' }}>Acheteur</option>
                                </select>
                                @error('destinataire_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="destinataire_id" class="form-label">ID du Destinataire</label>
                                <input type="number" name="destinataire_id" id="destinataire_id" class="form-control @error('destinataire_id') is-invalid @enderror" 
                                       value="{{ old('destinataire_id', $connaissement->destinataire_id) }}">
                                @error('destinataire_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="nombre_sacs" class="form-label">Nombre de Sacs *</label>
                                <input type="number" name="nombre_sacs" id="nombre_sacs" class="form-control @error('nombre_sacs') is-invalid @enderror" 
                                       value="{{ old('nombre_sacs', $connaissement->nombre_sacs) }}" min="1" required>
                                @error('nombre_sacs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="poids_brut_estime" class="form-label">Poids Brut Estimé (kg) *</label>
                                <input type="number" name="poids_brut_estime" id="poids_brut_estime" class="form-control @error('poids_brut_estime') is-invalid @enderror" 
                                       value="{{ old('poids_brut_estime', $connaissement->poids_brut_estime) }}" step="0.01" min="0.01" required>
                                @error('poids_brut_estime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations de Validation -->
                <div class="col-lg-6">
                    <div class="card h-100 p-0 radius-12">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="mb-0">Informations de Validation</h6>
                        </div>
                        <div class="card-body p-24">
                            <div class="mb-3">
                                <label for="signature_cooperative" class="form-label">Signature de la Coopérative</label>
                                <input type="text" name="signature_cooperative" id="signature_cooperative" class="form-control @error('signature_cooperative') is-invalid @enderror" 
                                       value="{{ old('signature_cooperative', $connaissement->signature_cooperative) }}">
                                @error('signature_cooperative')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Créé par</label>
                                <input type="text" class="form-control" value="{{ $connaissement->createdBy->name ?? 'N/A' }}" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Date de création</label>
                                <input type="text" class="form-control" value="{{ $connaissement->created_at->format('d/m/Y H:i') }}" readonly>
                            </div>
                            
                            @if($connaissement->validatedBy)
                                <div class="mb-3">
                                    <label class="form-label">Validé par</label>
                                    <input type="text" class="form-control" value="{{ $connaissement->validatedBy->name }}" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Date de validation</label>
                                    <input type="text" class="form-control" value="{{ $connaissement->date_validation ? $connaissement->date_validation->format('d/m/Y H:i') : 'N/A' }}" readonly>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Boutons d'Action -->
            <div class="row mt-24">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.connaissements.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-close-line"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line"></i>
                            Mettre à Jour
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

@include('partials.wowdash-scripts')

<script>
// Validation côté client
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
    }
});

// Gérer la sélection de coopérative avec datalist
document.addEventListener('DOMContentLoaded', function() {
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