<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de la Coopérative - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar')
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Profil de la Coopérative</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('admin.cooperatives.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Liste des Coopératives
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Profil</li>
            </ul>
        </div>
        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="card border radius-16 overflow-hidden bg-base h-100 p-0">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="text-center mb-16">
                            <div class="bg-primary rounded-circle p-3 d-inline-flex mb-3">
                                <i class="ri-building-line text-white"></i>
                            </div>
                            <h3 class="fw-bold text-primary mb-2">{{ $cooperative->nom }}</h3>
                            <span class="badge bg-secondary fs-6">{{ $cooperative->code }}</span>
                        </div>
                        <h6 class="text-lg fw-semibold mb-0">
                            <i class="ri-information-line me-2 text-info"></i>
                            Informations principales
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="row g-3">
                            <!-- Nom -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-primary rounded-circle p-2">
                                                <i class="ri-building-line text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Nom de la coopérative</h6>
                                                <span class="text-muted">{{ $cooperative->nom }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Secteur -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-info rounded-circle p-2">
                                                <i class="ri-map-pin-line text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Secteur</h6>
                                                <span class="text-muted">
                                                    {{ $cooperative->secteur ? $cooperative->secteur->code . ' - ' . $cooperative->secteur->nom : 'Non défini' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Président -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-success rounded-circle p-2">
                                                <i class="ri-user-star-line text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Président</h6>
                                                <span class="text-muted">{{ $cooperative->president }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-warning rounded-circle p-2">
                                                <i class="ri-phone-line text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Contact</h6>
                                                <span class="text-muted">{{ $cooperative->contact }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sigle -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-secondary rounded-circle p-2">
                                                <i class="ri-text text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Sigle</h6>
                                                <span class="text-muted">{{ $cooperative->sigle }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- GPS -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-danger rounded-circle p-2">
                                                <i class="ri-map-2-line text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Coordonnées GPS</h6>
                                                <span class="text-muted">{{ $cooperative->latitude }}, {{ $cooperative->longitude }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Séchoir -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-{{ $cooperative->a_sechoir ? 'success' : 'danger' }} rounded-circle p-2">
                                                <i class="ri-home-4-line text-white"></i>
                                </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Séchoir</h6>
                                                <span class="text-muted">
                                    @if($cooperative->a_sechoir)
                                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                                        <span class="badge bg-danger">Non disponible</span>
                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                @if(auth()->user()->role !== 'agc')
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">
                            <i class="ri-bank-line me-2 text-success"></i>
                            Données bancaires sécurisées
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        <!-- Première ligne : 3 premiers éléments -->
                        <div class="row g-3 mb-3">
                            <!-- Compte bancaire -->
                            <div class="col-md-4">
                                <div class="card border h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                                <div class="bg-success rounded-circle p-2">
                                                    <i class="ri-bank-line text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">Compte bancaire</h6>
                                                    <small class="text-muted">Numéro de compte</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-success" onclick="toggleBankInfo('compte')">
                                                <i class="ri-eye-line me-1"></i>
                                                Voir
                                            </button>
                                        </div>
                                        <div id="compte-display" class="text-muted">
                                            <span class="badge bg-light text-dark">••••••••••••••••</span>
                                        </div>
                                        <div id="compte-value" class="d-none">
                                            <span class="fw-semibold text-dark">{{ $cooperative->compte_bancaire }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Clé RIB -->
                            <div class="col-md-4">
                                <div class="card border h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary rounded-circle p-2">
                                                    <i class="ri-key-line text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">Clé RIB</h6>
                                                    <small class="text-muted">Clé de contrôle</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary" onclick="toggleBankInfo('cle_rib')">
                                                <i class="ri-eye-line me-1"></i>
                                                Voir
                                            </button>
                                        </div>
                                        <div id="cle_rib-display" class="text-muted">
                                            <span class="badge bg-light text-dark">••</span>
                                        </div>
                                        <div id="cle_rib-value" class="d-none">
                                            <span class="fw-semibold text-dark">{{ $cooperative->cle_rib ?? '00' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Code banque -->
                            <div class="col-md-4">
                                <div class="card border h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                                <div class="bg-info rounded-circle p-2">
                                                    <i class="ri-building-line text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">Code banque</h6>
                                                    <small class="text-muted">Identifiant banque</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-info" onclick="toggleBankInfo('banque')">
                                                <i class="ri-eye-line me-1"></i>
                                                Voir
                                            </button>
                                        </div>
                                        <div id="banque-display" class="text-muted">
                                            <span class="badge bg-light text-dark">••••</span>
                                        </div>
                                        <div id="banque-value" class="d-none">
                                            <span class="fw-semibold text-dark">{{ $cooperative->code_banque }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deuxième ligne : 2 derniers éléments -->
                        <div class="row g-3">
                            <!-- Code guichet -->
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                                <div class="bg-warning rounded-circle p-2">
                                                    <i class="ri-store-line text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">Code guichet</h6>
                                                    <small class="text-muted">Identifiant agence</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-warning" onclick="toggleBankInfo('guichet')">
                                                <i class="ri-eye-line me-1"></i>
                                                Voir
                                            </button>
                                        </div>
                                        <div id="guichet-display" class="text-muted">
                                            <span class="badge bg-light text-dark">••••</span>
                                        </div>
                                        <div id="guichet-value" class="d-none">
                                            <span class="fw-semibold text-dark">{{ $cooperative->code_guichet }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nom à la banque -->
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary rounded-circle p-2">
                                                    <i class="ri-user-line text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">Nom à la banque</h6>
                                                    <small class="text-muted">Titulaire du compte</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary" onclick="toggleBankInfo('nom')">
                                                <i class="ri-eye-line me-1"></i>
                                                Voir
                                            </button>
                                        </div>
                                        <div id="nom-display" class="text-muted">
                                            <span class="badge bg-light text-dark">••••••••••••••••</span>
                                        </div>
                                        <div id="nom-value" class="d-none">
                                            <span class="fw-semibold text-dark">{{ $cooperative->nom_cooperative_banque }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="ri-check-line me-2"></i>
                            <strong>Sécurité :</strong> Les informations bancaires sont masquées par défaut. Cliquez sur "Voir" pour les afficher.
                        </div>
                    </div>
                </div>
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="text-lg fw-semibold mb-0">
                                <i class="ri-road-map-line me-2 text-info"></i>
                                Distances vers les centres de collecte
                            </h6>
                            <span class="badge bg-primary">{{ $cooperative->distances->count() }}/7 centres</span>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        @if($cooperative->distances->count() > 0)
                            <!-- Vue en cartes -->
                            <div class="row g-3 mb-4">
                                @foreach($cooperative->distances as $distance)
                                    @php
                                        $distanceKm = $distance->distance_km;
                                        $isCOTRAF = in_array($distance->centreCollecte->code, ['COT1', 'COT2']);
                                        $colorClass = '';
                                        $transportCost = 0;
                                        
                                        if ($isCOTRAF) {
                                            // Prix progressifs pour l'usine COTRAF
                                            if ($distanceKm <= 100) {
                                                $colorClass = 'success';
                                                $transportCost = 14;
                                            } elseif ($distanceKm <= 200) {
                                                $colorClass = 'info';
                                                $transportCost = 15;
                                            } elseif ($distanceKm <= 300) {
                                                $colorClass = 'warning';
                                                $transportCost = 16;
                                            } elseif ($distanceKm <= 400) {
                                                $colorClass = 'danger';
                                                $transportCost = 22;
                                            } elseif ($distanceKm <= 500) {
                                                $colorClass = 'danger';
                                                $transportCost = 22;
                                            } elseif ($distanceKm <= 600) {
                                                $colorClass = 'dark';
                                                $transportCost = 23;
                                            } else {
                                                $colorClass = 'dark';
                                                $transportCost = 25;
                                            }
                                        } else {
                                            // Prix simplifiés pour les centres externes
                                            if ($distanceKm <= 100) {
                                                $colorClass = 'success';
                                                $transportCost = 8;
                                            } else {
                                                $colorClass = 'warning';
                                                $transportCost = 9;
                                            }
                                        }
                                    @endphp
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card border h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-light rounded-circle p-2">
                                                            <i class="ri-map-pin-line text-secondary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold">{{ $distance->centreCollecte->nom }}</h6>
                                                            <small class="text-muted">{{ $distance->centreCollecte->code }}</small>
                                                        </div>
                                                    </div>
                                                    <span class="badge {{ $isCOTRAF ? 'bg-success' : 'bg-warning' }} text-white fs-6">
                                                        {{ number_format($distanceKm, 0) }} km
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">Coût transport:</small>
                                                    <strong class="text-dark">{{ $transportCost }} FCFA/kg</strong>
                                                </div>
                                                <!-- Barre de progression -->
                                                <div class="progress mt-2" style="height: 3px;">
                                                    <div class="progress-bar bg-secondary" 
                                                         style="width: {{ min(($distanceKm / 600) * 100, 100) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="ri-road-map-line text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-3">Aucune distance configurée</h5>
                                <p class="text-muted mb-4">Configurez les distances vers les centres de collecte pour optimiser le calcul des coûts de transport.</p>
                                <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="btn btn-primary">
                                    <i class="ri-check-line me-2"></i>
                                    Configurer les distances
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="text-lg fw-semibold mb-0">
                                <i class="ri-file-text-line me-2 text-warning"></i>
                                Documents de traçabilité
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $providedDocs = $cooperative->documents->count();
                                    $totalDocs = count($documentTypes);
                                @endphp
                                <span class="badge bg-{{ $providedDocs == $totalDocs ? 'success' : ($providedDocs > 0 ? 'warning' : 'danger') }}">
                                    {{ $providedDocs }}/{{ $totalDocs }} documents
                                </span>
                                <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="btn btn-warning">
                                    <i class="ri-edit-line me-1"></i>
                                    Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        <!-- Barre de progression globale -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">Progression des documents</span>
                                <span class="text-muted">{{ number_format(($providedDocs / $totalDocs) * 100, 0) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $providedDocs == $totalDocs ? 'success' : ($providedDocs > 0 ? 'warning' : 'danger') }}" 
                                     style="width: {{ ($providedDocs / $totalDocs) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Grille des documents -->
                        <div class="row g-3">
                            @foreach($documentTypes as $key => $label)
                                @php $doc = $cooperative->documents->where('type', $key)->first(); @endphp
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border h-100 {{ $doc ? 'border-success' : 'border-light' }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                                    <div class="bg-{{ $doc ? 'success' : 'light' }} rounded-circle p-2">
                                                        <i class="ri-file-text-line text-{{ $doc ? 'white' : 'muted' }}"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold text-sm">{{ $label }}</h6>
                                                        <small class="text-muted">{{ $key }}</small>
                                                    </div>
                                                </div>
                                                @if($doc)
                                                    <span class="badge bg-success">
                                                        <i class="ri-check-line me-1"></i>
                                                        Fourni
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted">
                                                        <i class="ri-close-line me-1"></i>
                                                        Manquant
                                                    </span>
                                                @endif
                                            </div>
                                            
                                        @if($doc)
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="ri-calendar-line me-1"></i>
                                                        {{ $doc->created_at->format('d/m/Y') }}
                                                    </small>
                                                    <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-outline-success">
                                                        <i class="ri-eye-line me-1"></i>
                                                        Voir
                                                    </a>
                                                </div>
                                        @else
                                                <div class="text-center py-2">
                                                    <small class="text-muted">
                                                        <i class="ri-file-warning-line me-1"></i>
                                                        Document non fourni
                                                    </small>
                                                </div>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Résumé et actions -->
                        <div class="row mt-4">
                            <div class="col-md-8">
                                <div class="alert alert-{{ $providedDocs == $totalDocs ? 'success' : ($providedDocs > 0 ? 'warning' : 'danger') }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-file-text-line"></i>
                                        <div>
                                            <strong>
                                                @if($providedDocs == $totalDocs)
                                                    Tous les documents sont fournis
                                                @elseif($providedDocs > 0)
                                                    {{ $totalDocs - $providedDocs }} document(s) manquant(s)
                                                @else
                                                    Aucun document fourni
                                                @endif
                                            </strong>
                                            <br>
                                            <small>Cliquez sur "Modifier" pour ajouter ou remplacer des documents.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="btn btn-warning">
                                    <i class="ri-edit-line me-2"></i>
                                    Gérer les documents
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="btn btn-warning px-4">Modifier</a>
                    <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-secondary px-4">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</main>
@include('partials.wowdash-scripts')
<script>
function toggleBankInfo(type) {
    const display = document.getElementById(type + '-display');
    const value = document.getElementById(type + '-value');
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    
    if (display.classList.contains('d-none')) {
        // Masquer
        display.classList.remove('d-none');
        value.classList.add('d-none');
        icon.className = 'ri-eye-line me-1';
        button.innerHTML = '<i class="ri-eye-line me-1"></i>Voir';
    } else {
        // Afficher
        display.classList.add('d-none');
        value.classList.remove('d-none');
        icon.className = 'ri-eye-off-line me-1';
        button.innerHTML = '<i class="ri-eye-off-line me-1"></i>Masquer';
    }
}
</script>
</body>
</html> 