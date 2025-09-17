<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Ticket de Pesée - WowDash</title>
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
            <h6 class="fw-semibold mb-0">Modifier le Ticket de Pesée</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('admin.tickets-pesee.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Liste des Tickets
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">{{ $ticketPesee->numero_ticket }}</li>
            </ul>
        </div>
        
        <!-- Actions -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.tickets-pesee.show', $ticketPesee) }}" class="btn btn-info">
                    <i class="ri-eye-line me-2"></i>
                    Voir les détails
                </a>
            </div>
            <a href="{{ route('admin.tickets-pesee.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line me-2"></i>
                Retour à la liste
            </a>
        </div>
        
        <div class="card p-24 radius-12">
            <form action="{{ route('admin.tickets-pesee.update', $ticketPesee) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Sélection du Connaissement (Read-only for edit) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="card-title mb-3">
                            <i class="ri-file-list-line me-2"></i>
                            Sélection du Connaissement
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="connaissement_id" class="form-label">Connaissement *</label>
                        <select name="connaissement_id" id="connaissement_id" class="form-select @error('connaissement_id') is-invalid @enderror" required disabled>
                            <option value="{{ $ticketPesee->connaissement->id }}" selected>
                                {{ $ticketPesee->connaissement->numero }} - {{ $ticketPesee->connaissement->cooperative->nom }} ({{ $ticketPesee->connaissement->centreCollecte->nom }})
                            </option>
                        </select>
                        <input type="hidden" name="connaissement_id" value="{{ $ticketPesee->connaissement->id }}">
                        @error('connaissement_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Coopérative</label>
                        <input type="text" class="form-control" id="cooperative_display" value="{{ $ticketPesee->connaissement->cooperative->nom }}" readonly>
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
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="origine" class="form-label">Origine *</label>
                        <input type="text" class="form-control" id="origine" name="origine" value="{{ old('origine', $ticketPesee->origine) }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination" class="form-label">Destination *</label>
                        <input type="text" class="form-control" id="destination" name="destination" value="{{ old('destination', $ticketPesee->destination) }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="numero_camion" class="form-label">N° Camion *</label>
                        <input type="text" class="form-control" id="numero_camion" name="numero_camion" value="{{ old('numero_camion', $ticketPesee->numero_camion) }}" readonly>
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
                        <label for="transporteur" class="form-label">Transporteur *</label>
                        <input type="text" name="transporteur" id="transporteur" class="form-control @error('transporteur') is-invalid @enderror" value="{{ old('transporteur', $ticketPesee->transporteur) }}" required>
                        @error('transporteur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="chauffeur" class="form-label">Chauffeur *</label>
                        <input type="text" name="chauffeur" id="chauffeur" class="form-control @error('chauffeur') is-invalid @enderror" value="{{ old('chauffeur', $ticketPesee->chauffeur) }}" required>
                        @error('chauffeur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="equipe_chargement" class="form-label">Équipe Chargement</label>
                        <input type="text" name="equipe_chargement" id="equipe_chargement" class="form-control @error('equipe_chargement') is-invalid @enderror" value="{{ old('equipe_chargement', $ticketPesee->equipe_chargement) }}">
                        @error('equipe_chargement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="equipe_dechargement" class="form-label">Équipe Déchargement</label>
                        <input type="text" name="equipe_dechargement" id="equipe_dechargement" class="form-control @error('equipe_dechargement') is-invalid @enderror" value="{{ old('equipe_dechargement', $ticketPesee->equipe_dechargement) }}">
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
                        <input type="number" name="poids_entree" id="poids_entree" step="0.01" min="0.01" class="form-control @error('poids_entree') is-invalid @enderror" value="{{ old('poids_entree', $ticketPesee->poids_entree) }}" required>
                        @error('poids_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="poids_sortie" class="form-label">Poids Sortie (kg) *</label>
                        <input type="number" name="poids_sortie" id="poids_sortie" step="0.01" min="0.01" class="form-control @error('poids_sortie') is-invalid @enderror" value="{{ old('poids_sortie', $ticketPesee->poids_sortie) }}" required>
                        @error('poids_sortie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="poids_net" class="form-label">Poids Net (kg)</label>
                        <input type="number" id="poids_net" class="form-control" value="{{ number_format($ticketPesee->poids_net, 2) }}" readonly>
                        <small class="form-text text-muted">Calculé automatiquement</small>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="nombre_sacs_bidons_cartons" class="form-label">Nombre de Sacs/Bidons/Cartons *</label>
                        <input type="number" name="nombre_sacs_bidons_cartons" id="nombre_sacs_bidons_cartons" min="1" class="form-control @error('nombre_sacs_bidons_cartons') is-invalid @enderror" value="{{ old('nombre_sacs_bidons_cartons', $ticketPesee->nombre_sacs_bidons_cartons) }}" required>
                        @error('nombre_sacs_bidons_cartons')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom_peseur" class="form-label">Nom du Peseur *</label>
                        <input type="text" name="nom_peseur" id="nom_peseur" class="form-control @error('nom_peseur') is-invalid @enderror" value="{{ old('nom_peseur', $ticketPesee->nom_peseur) }}" required>
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
                        <input type="date" name="date_entree" id="date_entree" class="form-control @error('date_entree') is-invalid @enderror" value="{{ old('date_entree', $ticketPesee->date_entree->format('Y-m-d')) }}" required>
                        @error('date_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="heure_entree" class="form-label">Heure Entrée *</label>
                        <input type="time" name="heure_entree" id="heure_entree" class="form-control @error('heure_entree') is-invalid @enderror" value="{{ old('heure_entree', $ticketPesee->heure_entree->format('H:i')) }}" required>
                        @error('heure_entree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_sortie" class="form-label">Date Sortie *</label>
                        <input type="date" name="date_sortie" id="date_sortie" class="form-control @error('date_sortie') is-invalid @enderror" value="{{ old('date_sortie', $ticketPesee->date_sortie->format('Y-m-d')) }}" required>
                        @error('date_sortie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="heure_sortie" class="form-label">Heure Sortie *</label>
                        <input type="time" name="heure_sortie" id="heure_sortie" class="form-control @error('heure_sortie') is-invalid @enderror" value="{{ old('heure_sortie', $ticketPesee->heure_sortie->format('H:i')) }}" required>
                        @error('heure_sortie')
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
                        <input type="number" name="gp" id="gp" step="0.001" min="0" max="100" class="form-control @error('gp') is-invalid @enderror" value="{{ old('gp', $ticketPesee->gp) }}">
                        @error('gp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="ga" class="form-label">GA (%)</label>
                        <input type="number" name="ga" id="ga" step="0.001" min="0" max="100" class="form-control @error('ga') is-invalid @enderror" value="{{ old('ga', $ticketPesee->ga) }}">
                        @error('ga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="me" class="form-label">ME (%)</label>
                        <input type="number" name="me" id="me" step="0.001" min="0" max="100" class="form-control @error('me') is-invalid @enderror" value="{{ old('me', $ticketPesee->me) }}">
                        @error('me')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="taux_humidite" class="form-label">Taux Humidité (%)</label>
                        <input type="number" name="taux_humidite" id="taux_humidite" step="0.01" min="0" max="100" class="form-control @error('taux_humidite') is-invalid @enderror" value="{{ old('taux_humidite', $ticketPesee->taux_humidite) }}">
                        @error('taux_humidite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="taux_impuretes" class="form-label">Taux Impuretés (%)</label>
                        <input type="number" name="taux_impuretes" id="taux_impuretes" step="0.01" min="0" max="100" class="form-control @error('taux_impuretes') is-invalid @enderror" value="{{ old('taux_impuretes', $ticketPesee->taux_impuretes) }}">
                        @error('taux_impuretes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-2"></i>
                            Mettre à jour
                        </button>
                        <a href="{{ route('admin.tickets-pesee.show', $ticketPesee) }}" class="btn btn-secondary">
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
</body>
</html> 