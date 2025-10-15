<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Ticket de Pesée - FPH-CI</title>
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
            <h6 class="fw-semibold mb-0">Créer un Ticket de Pesée</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('admin.tickets-pesee.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Liste des Tickets
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Nouveau Ticket</li>
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
        
        <div class="card p-24 radius-12">
            <form action="{{ route('admin.tickets-pesee.store') }}" method="POST">
                @csrf
                
                <!-- Sélection du Connaissement -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-file-list-line me-2"></i>
                            Sélection du Connaissement
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="connaissement_id" class="form-label">Connaissement Validé pour Ticket de Pesée *</label>
                        <select name="connaissement_id" id="connaissement_id" class="form-select @error('connaissement_id') is-invalid @enderror" required>
                            <option value="">Sélectionner un connaissement validé pour ticket de pesée</option>
                            @foreach($connaissements as $connaissement)
                                <option value="{{ $connaissement->id }}" 
                                    data-cooperative="{{ $connaissement->cooperative->nom }}"
                                    data-cooperative-id="{{ $connaissement->cooperative_id }}"
                                    data-centre="{{ $connaissement->centreCollecte->nom }}"
                                    data-centre-id="{{ $connaissement->centre_collecte_id }}"
                                    data-secteur="{{ $connaissement->secteur ? $connaissement->secteur->nom : 'N/A' }}"
                                    data-transporteur="{{ $connaissement->transporteur_nom }}"
                                    data-chauffeur="{{ $connaissement->chauffeur_nom }}"
                                    data-numero-livraison="{{ $connaissement->numero_livraison }}"
                                    data-nombre-sacs="{{ $connaissement->nombre_sacs }}"
                                    data-poids-brut="{{ $connaissement->poids_brut_estime }}"
                                    data-poids-net="{{ $connaissement->poids_net }}"
                                    data-lieu-depart="{{ $connaissement->lieu_depart }}"
                                    data-sous-prefecture="{{ $connaissement->sous_prefecture }}"
                                    data-transporteur-immatriculation="{{ $connaissement->transporteur_immatriculation }}">
                                    {{ $connaissement->numero_livraison }} - {{ $connaissement->cooperative->nom }} ({{ $connaissement->centreCollecte->nom }})
                                </option>
                            @endforeach
                        </select>
                        @error('connaissement_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Coopérative</label>
                        <input type="text" class="form-control" id="cooperative_display" readonly>
                    </div>
                </div>
                
                <!-- Champs cachés pour les données du connaissement -->
                <input type="hidden" name="transporteur" id="transporteur_hidden" value="{{ old('transporteur') }}">
                <input type="hidden" name="chauffeur" id="chauffeur_hidden" value="{{ old('chauffeur') }}">
                
                <!-- Informations Récupérées du Connaissement (Read-only) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-information-line me-2"></i>
                            Informations du Connaissement (Automatiques)
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">N° Livraison</label>
                        <input type="text" class="form-control" id="numero_livraison" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Secteur</label>
                        <input type="text" class="form-control" id="secteur" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Centre de Collecte</label>
                        <input type="text" class="form-control" id="centre_collecte" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Lieu de Départ</label>
                        <input type="text" class="form-control" id="lieu_depart" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sous-Préfecture</label>
                        <input type="text" class="form-control" id="sous_prefecture" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Transporteur</label>
                        <input type="text" class="form-control" id="transporteur" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Immatriculation</label>
                        <input type="text" class="form-control" id="transporteur_immatriculation" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Chauffeur</label>
                        <input type="text" class="form-control" id="chauffeur" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Nombre de Sacs</label>
                        <input type="text" class="form-control" id="nombre_sacs" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Poids Brut Estimé (kg)</label>
                        <input type="text" class="form-control" id="poids_brut" readonly>
                    </div>
                </div>
                
                <!-- Informations du Ticket de Pesée -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-file-text-line me-2"></i>
                            Informations du Ticket de Pesée
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="campagne" class="form-label">Campagne *</label>
                        <input type="text" class="form-control" id="campagne" name="campagne" value="2025" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="client" class="form-label">Client *</label>
                        <input type="text" class="form-control" id="client" name="client" value="COTRAF SA" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fournisseur" class="form-label">Fournisseur *</label>
                        <input type="text" class="form-control" id="fournisseur" name="fournisseur" value="FPH-CI" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="origine" class="form-label">Origine *</label>
                        <input type="text" class="form-control" id="origine" name="origine" value="{{ old('origine') }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination" class="form-label">Destination *</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination') }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="numero_camion" class="form-label">N° Camion *</label>
                        <input type="text" class="form-control" id="numero_camion" name="numero_camion" value="{{ old('numero_camion') }}" readonly>
                    </div>
                </div>
                
                <!-- Informations de Pesée -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-scales-line me-2"></i>
                            Informations de Pesée
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="poids_entree" class="form-label">Poids d'Entrée (kg) *</label>
                        <input type="number" step="0.01" class="form-control @error('poids_entree') is-invalid @enderror" id="poids_entree" name="poids_entree" value="{{ old('poids_entree') }}" required>
                        @error('poids_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="poids_sortie" class="form-label">Poids de Sortie (kg) *</label>
                        <input type="number" step="0.01" class="form-control @error('poids_sortie') is-invalid @enderror" id="poids_sortie" name="poids_sortie" value="{{ old('poids_sortie') }}" required>
                        @error('poids_sortie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nombre_sacs_bidons_cartons" class="form-label">Nombre de Sacs/Bidons/Cartons *</label>
                        <input type="number" class="form-control @error('nombre_sacs_bidons_cartons') is-invalid @enderror" id="nombre_sacs_bidons_cartons" name="nombre_sacs_bidons_cartons" value="{{ old('nombre_sacs_bidons_cartons') }}" required>
                        @error('nombre_sacs_bidons_cartons')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom_peseur" class="form-label">Nom du Peseur *</label>
                        <input type="text" class="form-control @error('nom_peseur') is-invalid @enderror" id="nom_peseur" name="nom_peseur" value="{{ old('nom_peseur') }}" required>
                        @error('nom_peseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Analyse Qualité (Optionnel) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-test-tube-line me-2"></i>
                            Analyse Qualité (Optionnel)
                        </h5>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="poids_100_graines" class="form-label">Poids 100 graines (g)</label>
                        <input type="number" name="poids_100_graines" id="poids_100_graines" step="0.01" min="0" class="form-control" value="100" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="gp" class="form-label">GP (%)</label>
                        <input type="number" name="gp" id="gp" step="0.001" min="0" max="100" class="form-control @error('gp') is-invalid @enderror" value="{{ old('gp') }}">
                        @error('gp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="ga" class="form-label">GA (%)</label>
                        <input type="number" name="ga" id="ga" step="0.001" min="0" max="100" class="form-control @error('ga') is-invalid @enderror" value="{{ old('ga') }}">
                        @error('ga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="me" class="form-label">ME (%)</label>
                        <input type="number" name="me" id="me" step="0.001" min="0" max="100" class="form-control @error('me') is-invalid @enderror" value="{{ old('me') }}">
                        @error('me')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="taux_humidite" class="form-label">Taux Humidité (%)</label>
                        <input type="number" name="taux_humidite" id="taux_humidite" step="0.01" min="0" max="100" class="form-control @error('taux_humidite') is-invalid @enderror" value="{{ old('taux_humidite') }}">
                        @error('taux_humidite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="taux_impuretes" class="form-label">Taux Impuretés (%)</label>
                        <input type="number" name="taux_impuretes" id="taux_impuretes" step="0.01" min="0" max="100" class="form-control @error('taux_impuretes') is-invalid @enderror" value="{{ old('taux_impuretes') }}" readonly>
                        @error('taux_impuretes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Calculé automatiquement (GP + GA + ME)</small>
                    </div>
                </div>
                
                <!-- Dates et Heures -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-calendar-line me-2"></i>
                            Dates et Heures
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_entree" class="form-label">Date d'Entrée *</label>
                        <input type="date" class="form-control @error('date_entree') is-invalid @enderror" id="date_entree" name="date_entree" value="{{ old('date_entree') }}" required>
                        @error('date_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="heure_entree" class="form-label">Heure d'Entrée *</label>
                        <input type="time" class="form-control @error('heure_entree') is-invalid @enderror" id="heure_entree" name="heure_entree" value="{{ old('heure_entree') }}" required>
                        @error('heure_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_sortie" class="form-label">Date de Sortie *</label>
                        <input type="date" class="form-control @error('date_sortie') is-invalid @enderror" id="date_sortie" name="date_sortie" value="{{ old('date_sortie') }}" required>
                        @error('date_sortie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="heure_sortie" class="form-label">Heure de Sortie *</label>
                        <input type="time" class="form-control @error('heure_sortie') is-invalid @enderror" id="heure_sortie" name="heure_sortie" value="{{ old('heure_sortie') }}" required>
                        @error('heure_sortie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.tickets-pesee.index') }}" class="btn btn-secondary">
                        <i class="ri-close-line"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line"></i>
                        Créer le Ticket de Pesée
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const connaissementSelect = document.getElementById('connaissement_id');
    
    connaissementSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Remplir les champs automatiques
            document.getElementById('cooperative_display').value = selectedOption.dataset.cooperative;
            document.getElementById('numero_livraison').value = selectedOption.dataset.numeroLivraison;
            document.getElementById('secteur').value = selectedOption.dataset.secteur;
            document.getElementById('centre_collecte').value = selectedOption.dataset.centre;
            document.getElementById('lieu_depart').value = selectedOption.dataset.lieuDepart;
            document.getElementById('sous_prefecture').value = selectedOption.dataset.sousPrefecture;
            document.getElementById('transporteur').value = selectedOption.dataset.transporteur;
            document.getElementById('transporteur_immatriculation').value = selectedOption.dataset.transporteurImmatriculation;
            document.getElementById('chauffeur').value = selectedOption.dataset.chauffeur;
            document.getElementById('nombre_sacs').value = selectedOption.dataset.nombreSacs;
            document.getElementById('poids_brut').value = selectedOption.dataset.poidsBrut;
            
            // Remplir les champs cachés
            document.getElementById('transporteur_hidden').value = selectedOption.dataset.transporteur;
            document.getElementById('chauffeur_hidden').value = selectedOption.dataset.chauffeur;

            // Remplir les champs du ticket de pesée
            document.getElementById('origine').value = selectedOption.dataset.lieuDepart || '';
            document.getElementById('destination').value = selectedOption.dataset.centre || '';
            document.getElementById('numero_camion').value = selectedOption.dataset.transporteurImmatriculation || '';
        } else {
            // Vider les champs
            document.getElementById('cooperative_display').value = '';
            document.getElementById('numero_livraison').value = '';
            document.getElementById('secteur').value = '';
            document.getElementById('centre_collecte').value = '';
            document.getElementById('lieu_depart').value = '';
            document.getElementById('sous_prefecture').value = '';
            document.getElementById('transporteur').value = '';
            document.getElementById('transporteur_immatriculation').value = '';
            document.getElementById('chauffeur').value = '';
            document.getElementById('nombre_sacs').value = '';
            document.getElementById('poids_brut').value = '';
            
            // Vider les champs cachés
            document.getElementById('transporteur_hidden').value = '';
            document.getElementById('chauffeur_hidden').value = '';
        }
    });
});

// Calcul automatique du taux d'impuretés
document.addEventListener('DOMContentLoaded', function() {
    function calculateTauxImpuretes() {
        const gp = parseFloat(document.getElementById('gp').value) || 0;
        const ga = parseFloat(document.getElementById('ga').value) || 0;
        const me = parseFloat(document.getElementById('me').value) || 0;
        
        const tauxImpuretes = gp + ga + me;
        document.getElementById('taux_impuretes').value = tauxImpuretes.toFixed(3);
    }
    
    // Écouter les changements sur GP, GA, ME
    document.getElementById('gp').addEventListener('input', calculateTauxImpuretes);
    document.getElementById('ga').addEventListener('input', calculateTauxImpuretes);
    document.getElementById('me').addEventListener('input', calculateTauxImpuretes);
});
</script>
</body>
</html>