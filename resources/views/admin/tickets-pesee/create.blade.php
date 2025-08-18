<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Ticket de Pesée - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Créer un Ticket de Pesée</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('admin.tickets-pesee.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Liste des Tickets
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Nouveau Ticket</li>
            </ul>
        </div>
        
        <!-- Actions -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.tickets-pesee.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
        
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
                                    data-centre="{{ $connaissement->centreCollecte->nom }}"
                                    data-transporteur="{{ $connaissement->transporteur_nom }}"
                                    data-chauffeur="{{ $connaissement->chauffeur_nom }}"
                                    data-numero="{{ $connaissement->numero }}"
                                    data-nombre-sacs="{{ $connaissement->nombre_sacs }}"
                                    data-poids-brut="{{ $connaissement->poids_brut_estime }}"
                                    data-poids-net="{{ $connaissement->poids_net }}"
                                    data-lieu-depart="{{ $connaissement->lieu_depart }}"
                                    data-sous-prefecture="{{ $connaissement->sous_prefecture }}"
                                    data-transporteur-immatriculation="{{ $connaissement->transporteur_immatriculation }}">
                                    {{ $connaissement->numero }} - {{ $connaissement->cooperative->nom }} ({{ $connaissement->centreCollecte->nom }})
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
                
                <!-- Informations Récupérées du Connaissement (Read-only) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-information-line me-2"></i>
                            Informations du Connaissement (Automatiques)
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">N° Connaissement</label>
                        <input type="text" class="form-control" id="numero_connaissement" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Centre de Collecte</label>
                        <input type="text" class="form-control" id="centre_collecte" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Transporteur</label>
                        <input type="text" class="form-control" id="transporteur" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Chauffeur</label>
                        <input type="text" class="form-control" id="chauffeur" readonly>
                    </div>
                </div>
                
                <!-- Informations Générales -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-information-line me-2"></i>
                            Informations Générales
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="campagne" class="form-label">Campagne *</label>
                        <input type="text" name="campagne" id="campagne" class="form-control @error('campagne') is-invalid @enderror" value="{{ old('campagne', '2024-2025') }}" readonly required>
                        @error('campagne')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="client" class="form-label">Client *</label>
                        <input type="text" name="client" id="client" class="form-control @error('client') is-invalid @enderror" value="{{ old('client', 'COTRAF SA') }}" readonly required>
                        @error('client')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fournisseur" class="form-label">Fournisseur *</label>
                        <input type="text" name="fournisseur" id="fournisseur" class="form-control @error('fournisseur') is-invalid @enderror" value="{{ old('fournisseur', 'FPH-CI') }}" readonly required>
                        @error('fournisseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="origine" class="form-label">Origine *</label>
                        <input type="text" name="origine" id="origine" class="form-control @error('origine') is-invalid @enderror" value="{{ old('origine', 'DALOA') }}" readonly required>
                        @error('origine')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination" class="form-label">Destination *</label>
                        <input type="text" name="destination" id="destination" class="form-control @error('destination') is-invalid @enderror" value="{{ old('destination', 'PK 24') }}" readonly required>
                        @error('destination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Informations Transport -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-car-line me-2"></i>
                            Informations Transport
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="numero_camion" class="form-label">N° Camion *</label>
                        <input type="text" name="numero_camion" id="numero_camion" class="form-control @error('numero_camion') is-invalid @enderror" value="{{ old('numero_camion') }}" readonly required>
                        @error('numero_camion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="equipe_chargement" class="form-label">Équipe Chargement</label>
                        <input type="text" name="equipe_chargement" id="equipe_chargement" class="form-control @error('equipe_chargement') is-invalid @enderror" value="{{ old('equipe_chargement') }}">
                        @error('equipe_chargement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="equipe_dechargement" class="form-label">Équipe Déchargement</label>
                        <input type="text" name="equipe_dechargement" id="equipe_dechargement" class="form-control @error('equipe_dechargement') is-invalid @enderror" value="{{ old('equipe_dechargement') }}">
                        @error('equipe_dechargement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Détails de Pesée -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-scales-line me-2"></i>
                            Détails de Pesée
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="poids_entree" class="form-label">Poids Entrée (kg) *</label>
                        <input type="number" name="poids_entree" id="poids_entree" step="0.01" min="0.01" class="form-control @error('poids_entree') is-invalid @enderror" value="{{ old('poids_entree') }}" required>
                        @error('poids_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="poids_sortie" class="form-label">Poids Sortie (kg) *</label>
                        <input type="number" name="poids_sortie" id="poids_sortie" step="0.01" min="0.01" class="form-control @error('poids_sortie') is-invalid @enderror" value="{{ old('poids_sortie') }}" required>
                        @error('poids_sortie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="poids_net" class="form-label">Poids Net (kg)</label>
                        <input type="number" id="poids_net" class="form-control" readonly>
                        <small class="form-text text-muted">Calculé automatiquement</small>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="nombre_sacs_bidons_cartons" class="form-label">Nombre de Sacs/Bidons/Cartons *</label>
                        <input type="number" name="nombre_sacs_bidons_cartons" id="nombre_sacs_bidons_cartons" min="1" class="form-control @error('nombre_sacs_bidons_cartons') is-invalid @enderror" value="{{ old('nombre_sacs_bidons_cartons') }}" required>
                        @error('nombre_sacs_bidons_cartons')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom_peseur" class="form-label">Nom du Peseur *</label>
                        <input type="text" name="nom_peseur" id="nom_peseur" class="form-control @error('nom_peseur') is-invalid @enderror" value="{{ old('nom_peseur') }}" required>
                        @error('nom_peseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Horaires -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-time-line me-2"></i>
                            Horaires
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_entree" class="form-label">Date Entrée *</label>
                        <input type="date" name="date_entree" id="date_entree" class="form-control @error('date_entree') is-invalid @enderror" value="{{ old('date_entree', date('Y-m-d')) }}" required>
                        @error('date_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="heure_entree" class="form-label">Heure Entrée *</label>
                        <input type="time" name="heure_entree" id="heure_entree" class="form-control @error('heure_entree') is-invalid @enderror" value="{{ old('heure_entree') }}" required>
                        @error('heure_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_sortie" class="form-label">Date Sortie *</label>
                        <input type="date" name="date_sortie" id="date_sortie" class="form-control @error('date_sortie') is-invalid @enderror" value="{{ old('date_sortie', date('Y-m-d')) }}" required>
                        @error('date_sortie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="heure_sortie" class="form-label">Heure Sortie *</label>
                        <input type="time" name="heure_sortie" id="heure_sortie" class="form-control @error('heure_sortie') is-invalid @enderror" value="{{ old('heure_sortie') }}" required>
                        @error('heure_sortie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Analyse Qualité -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-test-tube-line me-2"></i>
                            Analyse Qualité
                        </h5>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="poids_100_graines" class="form-label">Poids 100 graines (g)</label>
                        <input type="number" name="poids_100_graines" id="poids_100_graines" step="0.01" min="0" class="form-control @error('poids_100_graines') is-invalid @enderror" value="{{ old('poids_100_graines') }}">
                        @error('poids_100_graines')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="gp" class="form-label">Graine Pourrie (%)</label>
                        <input type="number" name="gp" id="gp" step="0.001" min="0" max="100" class="form-control @error('gp') is-invalid @enderror" value="{{ old('gp') }}">
                        @error('gp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="ga" class="form-label">Graine Avortée (%)</label>
                        <input type="number" name="ga" id="ga" step="0.001" min="0" max="100" class="form-control @error('ga') is-invalid @enderror" value="{{ old('ga') }}">
                        @error('ga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="me" class="form-label">Corps Étranger (%)</label>
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
                        <input type="number" id="taux_impuretes" class="form-control" readonly>
                        <small class="form-text text-muted">GP + GA + ME</small>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-2"></i>
                            Créer le Ticket
                        </button>
                        <a href="{{ route('admin.tickets-pesee.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-2"></i>
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@include('partials.wowdash-scripts')

<script>
$(document).ready(function() {
    // Mettre à jour les informations du connaissement sélectionné
    $('#connaissement_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            // Informations de base
            $('#cooperative_display').val(selectedOption.data('cooperative'));
            $('#centre_collecte').val(selectedOption.data('centre'));
            $('#transporteur').val(selectedOption.data('transporteur'));
            $('#chauffeur').val(selectedOption.data('chauffeur'));
            $('#numero_connaissement').val(selectedOption.data('numero'));
            
            // Informations automatiquement remplies
            $('#nombre_sacs_bidons_cartons').val(selectedOption.data('nombre-sacs'));
            // Ne pas pré-remplir les poids, seulement proposer comme référence
            // $('#poids_entree').val(selectedOption.data('poids-brut'));
            // $('#poids_sortie').val(selectedOption.data('poids-net') || '0.00');
            $('#origine').val(selectedOption.data('lieu-depart'));
            $('#destination').val(selectedOption.data('sous-prefecture'));
            $('#numero_camion').val(selectedOption.data('transporteur-immatriculation'));
            
            // Calculer automatiquement le poids net
            calculateNetWeight();
            
            // Pré-remplir la date d'entrée avec la date de réception du connaissement si disponible
            const connaissement = @json($connaissements);
            const selectedConnaissement = connaissement.find(c => c.id == selectedOption.val());
            if (selectedConnaissement && selectedConnaissement.date_reception) {
                $('#date_entree').val(selectedConnaissement.date_reception);
                $('#date_sortie').val(selectedConnaissement.date_reception);
            }
            
        } else {
            // Vider tous les champs
            $('#cooperative_display').val('');
            $('#centre_collecte').val('');
            $('#transporteur').val('');
            $('#chauffeur').val('');
            $('#numero_connaissement').val('');
            $('#nombre_sacs_bidons_cartons').val('');
            $('#poids_entree').val('');
            $('#poids_sortie').val('');
            $('#origine').val('');
            $('#destination').val('');
            $('#numero_camion').val('');
            $('#poids_net').val('0.00');
        }
    });
    
    // Calcul automatique du poids net
    function calculateNetWeight() {
        const poidsEntree = parseFloat($('#poids_entree').val()) || 0;
        const poidsSortie = parseFloat($('#poids_sortie').val()) || 0;
        const poidsNet = poidsEntree - poidsSortie;
        
        if (poidsNet >= 0) {
            $('#poids_net').val(poidsNet.toFixed(2));
        } else {
            $('#poids_net').val('0.00');
        }
    }
    
    $('#poids_entree, #poids_sortie').on('input', calculateNetWeight);
    
    // Calcul automatique du taux d'impuretés
    function calculateImpurities() {
        const gp = parseFloat($('#gp').val()) || 0;
        const ga = parseFloat($('#ga').val()) || 0;
        const me = parseFloat($('#me').val()) || 0;
        const total = gp + ga + me;
        
        $('#taux_impuretes').val(total.toFixed(2));
    }
    
    $('#gp, #ga, #me').on('input', calculateImpurities);
    
    // Initialiser les calculs
    calculateNetWeight();
    calculateImpurities();
    
    // Pré-remplir les valeurs par défaut intelligentes
    $('#origine').val('DALOA');
    $('#destination').val('PK 24');
    $('#campagne').val('2024-2025');
    $('#client').val('COTRAF SA');
    $('#fournisseur').val('FPH-CI');
    
    // Définir l'heure actuelle par défaut
    const now = new Date();
    const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    $('#heure_entree').val(currentTime);
    $('#heure_sortie').val(currentTime);
});
</script>

</body>
</html> 