<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Producteur - Farmer List {{ $connaissement->numero_livraison }} - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Ajouter Producteur - {{ $connaissement->numero_livraison }}</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.farmer-lists.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        Farmer Lists
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.farmer-lists.show', $connaissement) }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        {{ $connaissement->numero_livraison }}
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Ajouter Producteur</li>
            </ul>
        </div>

        <!-- Informations de la livraison -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informations de la Livraison</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>N° Livraison:</strong><br>
                                {{ $connaissement->numero_livraison }}
                            </div>
                            <div class="col-md-3">
                                <strong>Coopérative:</strong><br>
                                {{ $connaissement->cooperative->nom }}
                            </div>
                            <div class="col-md-3">
                                <strong>Poids Net:</strong><br>
                                {{ number_format($poidsNet, 2) }} kg
                            </div>
                            <div class="col-md-3">
                                <strong>Poids Restant:</strong><br>
                                <span class="text-primary fw-bold">{{ number_format($poidsRestant, 2) }} kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">Poids Restant</h6>
                        <p class="card-text">{{ number_format($poidsRestant, 2) }} kg</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">Sacs Restants</h6>
                        <p class="card-text">{{ $sacsRestant }} sacs</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire d'ajout de producteur -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Ajouter un Producteur</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.farmer-lists.store', $connaissement) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="producteur_id" class="form-label">Producteur *</label>
                                    <select name="producteur_id" id="producteur_id" class="form-select @error('producteur_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un producteur</option>
                                        @foreach($producteurs as $producteur)
                                            <option value="{{ $producteur->id }}" 
                                                    {{ old('producteur_id') == $producteur->id ? 'selected' : '' }}
                                                    data-secteur="{{ $producteur->secteur->nom ?? 'N/A' }}"
                                                    data-code="{{ $producteur->code_fphci ?? 'N/A' }}"
                                                    data-contact="{{ $producteur->contact ?? 'N/A' }}">
                                                {{ $producteur->nom }} {{ $producteur->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('producteur_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="quantite_livree" class="form-label">Quantité Livrée (kg) *</label>
                                    <input type="number" 
                                           name="quantite_livree" 
                                           id="quantite_livree" 
                                           class="form-control @error('quantite_livree') is-invalid @enderror" 
                                           value="{{ old('quantite_livree') }}" 
                                           step="0.01" 
                                           min="0.01" 
                                           max="{{ $poidsRestant }}"
                                           required>
                                    <div class="form-text">Maximum: {{ number_format($poidsRestant, 2) }} kg</div>
                                    @error('quantite_livree')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nombre_sacs" class="form-label">Nombre de Sacs *</label>
                                    <input type="number" name="nombre_sacs" id="nombre_sacs" 
                                           class="form-control @error('nombre_sacs') is-invalid @enderror" 
                                           value="{{ old('nombre_sacs') }}" 
                                           min="1" required>
                                    @error('nombre_sacs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_livraison" class="form-label">Date de Livraison *</label>
                                    <input type="date" 
                                           name="date_livraison" 
                                           id="date_livraison" 
                                           class="form-control @error('date_livraison') is-invalid @enderror" 
                                           value="{{ old('date_livraison', $dateLivraison) }}" 
                                           readonly
                                           required>
                                    <div class="form-text">Date de validation du ticket de pesée</div>
                                    @error('date_livraison')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="geolocalisation_precise" class="form-label">Géolocalisation Précise *</label>
                                    <select name="geolocalisation_precise" id="geolocalisation_precise" 
                                            class="form-control @error('geolocalisation_precise') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        <option value="oui" {{ old('geolocalisation_precise') == 'oui' ? 'selected' : '' }}>Oui</option>
                                        <option value="non" {{ old('geolocalisation_precise') == 'non' ? 'selected' : '' }}>Non</option>
                                    </select>
                                    @error('geolocalisation_precise')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informations du producteur sélectionné -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading">Informations du Producteur Sélectionné :</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Secteur :</strong> <span id="info-secteur">-</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Code :</strong> <span id="info-code">-</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Coopérative :</strong> {{ $connaissement->cooperative->nom }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Contact :</strong> <span id="info-contact">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <iconify-icon icon="ri-add-line"></iconify-icon> Ajouter le Producteur
                                </button>
                                <a href="{{ route('admin.farmer-lists.show', $connaissement) }}" 
                                   class="btn btn-secondary">
                                    <iconify-icon icon="ri-arrow-left-line"></iconify-icon> Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const producteurSelect = document.getElementById('producteur_id');
    const infoSecteur = document.getElementById('info-secteur');
    const infoCode = document.getElementById('info-code');
    const infoContact = document.getElementById('info-contact');

    producteurSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            infoSecteur.textContent = selectedOption.dataset.secteur;
            infoCode.textContent = selectedOption.dataset.code;
            infoContact.textContent = selectedOption.dataset.contact;
        } else {
            infoSecteur.textContent = '-';
            infoCode.textContent = '-';
            infoContact.textContent = '-';
        }
    });

    // Validation de la quantité
    const quantiteInput = document.getElementById('quantite_livree');
    quantiteInput.addEventListener('input', function() {
        const max = parseFloat('{{ $poidsRestant }}');
        const value = parseFloat(this.value);
        
        if (value > max) {
            this.setCustomValidity(`La quantité ne peut pas dépasser ${max.toFixed(2)} kg`);
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

@include('partials.wowdash-scripts')
</body>
</html> 