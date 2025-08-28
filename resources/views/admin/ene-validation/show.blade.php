<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail du Calcul - Validation ENE CI - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/apexcharts.css') }}">
    <!-- Data Table css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/dataTables.min.css') }}">
    <!-- Text Editor css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor-katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor.atom-one-dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor.quill.snow.css') }}">
    <!-- Date picker css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/flatpickr.min.css') }}">
    <!-- Calendar css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/full-calendar.css') }}">
    <!-- Vector Map css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/jquery-jvectormap-2.0.5.css') }}">
    <!-- Popup css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/magnific-popup.css') }}">
    <!-- Slick Slider css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/slick.css') }}">
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/prism.css') }}">
    <!-- file upload css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/audioplayer.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar')

<main class="dashboard-main">
    @include('partials.navbar-header')

    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Détail du Calcul - Ticket {{ $ticketPesee->numero_ticket }} - Validation ENE CI</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.ene-validation.index') }}" class="hover-text-primary">Validation ENE CI</a>
                </li>
                <li>-</li>
                <li class="fw-medium">Détail du Calcul</li>
            </ul>
        </div>

        <!-- Messages de succès/erreur -->
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

        <!-- Informations du Ticket -->
        <div class="card h-100 p-0 radius-12 mb-24">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h5 class="card-title mb-0">
                    <i class="ri-file-list-line me-2"></i>Informations du Ticket de Pesée
                </h5>
            </div>
            <div class="card-body p-24">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">N° Ticket :</span>
                            <span class="badge bg-primary">{{ $ticketPesee->numero_ticket }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">Coopérative :</span>
                            <span>{{ $ticketPesee->connaissement->cooperative->nom }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">Centre de Collecte :</span>
                            <span>{{ $ticketPesee->connaissement->centreCollecte->nom ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">Poids Net :</span>
                            <span class="fw-bold">{{ number_format($ticketPesee->poids_net, 2) }} kg</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">Campagne :</span>
                            <span>{{ $ticketPesee->campagne }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">Client :</span>
                            <span>{{ $ticketPesee->client }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">Fournisseur :</span>
                            <span>{{ $ticketPesee->fournisseur }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-medium">Date de Pesée :</span>
                            <span>{{ $ticketPesee->date_entree ? $ticketPesee->date_entree->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détail du Calcul -->
        <div class="card h-100 p-0 radius-12 mb-24">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h5 class="card-title mb-0">
                    <i class="ri-calculator-line me-2"></i>Détail du Calcul du Prix
                </h5>
            </div>
            <div class="card-body p-24">
                <div class="row">
                    <!-- Prix de Base -->
                    <div class="col-md-6 mb-24">
                        <div class="card border h-100">
                            <div class="card-header bg-light py-12 px-16">
                                <h6 class="mb-0 fw-semibold">1. Prix de Base</h6>
                            </div>
                            <div class="card-body p-16">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Prix standard :</span>
                                    <span class="fw-bold">93 FCFA/kg</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Séchoir :</span>
                                    <span class="text-muted">Non (pas de bonus)</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Prix de base :</span>
                                    <span class="fw-bold text-primary">{{ number_format($prix['prix_base'], 2) }} FCFA/kg</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bonus Qualité -->
                    <div class="col-md-6 mb-24">
                        <div class="card border h-100">
                            <div class="card-header bg-light py-12 px-16">
                                <h6 class="mb-0 fw-semibold">2. Bonus Qualité</h6>
                            </div>
                            <div class="card-body p-16">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Impuretés ({{ number_format($ticketPesee->taux_impuretes, 2) }}%) :</span>
                                    @if($prix['bonus_qualite'] > 0)
                                        <span class="text-success fw-bold">+{{ number_format($prix['bonus_qualite'], 2) }} FCFA</span>
                                    @elseif($prix['bonus_qualite'] < 0)
                                        <span class="text-danger fw-bold">{{ number_format($prix['bonus_qualite'], 2) }} FCFA</span>
                                    @else
                                        <span class="text-muted fw-bold">0 FCFA</span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Humidité ({{ number_format($ticketPesee->taux_humidite, 2) }}%) :</span>
                                    <span class="text-muted">0 FCFA (neutre)</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Total bonus qualité :</span>
                                    <span class="fw-bold @if($prix['bonus_qualite'] > 0) text-success @elseif($prix['bonus_qualite'] < 0) text-danger @else text-muted @endif">
                                        {{ number_format($prix['bonus_qualite'], 2) }} FCFA
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transport -->
                    <div class="col-md-6 mb-24">
                        <div class="card border h-100">
                            <div class="card-header bg-light py-12 px-16">
                                <h6 class="mb-0 fw-semibold">3. Coût Transport</h6>
                            </div>
                            <div class="card-body p-16">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Kilométrage :</span>
                                    <span class="fw-bold">{{ $prix['details']['distance'] }} km</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Zone tarifaire :</span>
                                    <span class="text-muted">
                                        @if($prix['details']['distance'] <= 100)
                                            0-100 km
                                        @elseif($prix['details']['distance'] <= 200)
                                            100-200 km
                                        @elseif($prix['details']['distance'] <= 300)
                                            200-300 km
                                        @elseif($prix['details']['distance'] <= 400)
                                            300-400 km
                                        @elseif($prix['details']['distance'] <= 500)
                                            400-500 km
                                        @elseif($prix['details']['distance'] <= 600)
                                            500-600 km
                                        @else
                                            600+ km
                                        @endif
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Coût transport :</span>
                                    <span class="fw-bold text-info">{{ number_format($prix['cout_transport'], 2) }} FCFA/kg</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prix Public -->
                    <div class="col-md-6 mb-24">
                        <div class="card border h-100">
                            <div class="card-header bg-light py-12 px-16">
                                <h6 class="mb-0 fw-semibold">4. Prix Public</h6>
                            </div>
                            <div class="card-body p-16">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Prix de base :</span>
                                    <span>{{ number_format($prix['prix_base'], 2) }} FCFA</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Bonus qualité :</span>
                                    <span>{{ number_format($prix['bonus_qualite'], 2) }} FCFA</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Transport :</span>
                                    <span>{{ number_format($prix['cout_transport'], 2) }} FCFA</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Prix public par kg :</span>
                                    <span class="fw-bold text-success">{{ number_format($prix['prix_final_public'], 2) }} FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Résumé Final -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-header bg-primary text-white py-16 px-24">
                                <h5 class="mb-0 text-white">
                                    <i class="ri-calculator-line me-2"></i>Résumé Final du Calcul
                                </h5>
                            </div>
                            <div class="card-body p-24">
                                <div class="row">
                                    <!-- Montant Public -->
                                    <div class="col-md-6">
                                        <div class="text-center p-16 border rounded">
                                            <h6 class="text-success mb-2">🟢 MONTANT PUBLIC</h6>
                                            <h4 class="text-success fw-bold">{{ number_format($prix['prix_final_public'], 2) }} FCFA/kg</h4>
                                            <p class="mb-0 text-muted">Prix affiché au public</p>
                                            <hr>
                                            <h5 class="text-success fw-bold">{{ number_format($prix['prix_final_public'] * $ticketPesee->poids_net, 2) }} FCFA</h5>
                                            <p class="mb-0 text-muted">Total pour {{ number_format($ticketPesee->poids_net, 2) }} kg</p>
                                        </div>
                                    </div>

                                    <!-- Montant Privé -->
                                    <div class="col-md-6">
                                        <div class="text-center p-16 border rounded">
                                            <h6 class="text-danger mb-2">🔴 MONTANT PRIVÉ (FPH-CI)</h6>
                                            <h4 class="text-danger fw-bold">{{ number_format($prix['part_fphci'], 2) }} FCFA/kg</h4>
                                            <p class="mb-0 text-muted">Part cachée FPH-CI</p>
                                            <hr>
                                            <h5 class="text-danger fw-bold">{{ number_format($prix['part_fphci'] * $ticketPesee->poids_net, 2) }} FCFA</h5>
                                            <p class="mb-0 text-muted">Total pour {{ number_format($ticketPesee->poids_net, 2) }} kg</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Réel -->
                                <div class="row mt-24">
                                    <div class="col-12">
                                        <div class="text-center p-16 bg-light rounded">
                                            <h6 class="text-primary mb-2">💰 TOTAL RÉEL</h6>
                                            <h3 class="text-primary fw-bold">{{ number_format($prix['prix_final_public'] + $prix['part_fphci'], 2) }} FCFA/kg</h3>
                                            <p class="mb-0 text-muted">Prix total réel (public + privé)</p>
                                            <hr>
                                            <h4 class="text-primary fw-bold">{{ number_format(($prix['prix_final_public'] + $prix['part_fphci']) * $ticketPesee->poids_net, 2) }} FCFA</h4>
                                            <p class="mb-0 text-muted">Montant total réel pour {{ number_format($ticketPesee->poids_net, 2) }} kg</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions de Validation ENE CI -->
        @if($ticketPesee->statut_ene === 'en_attente')
        <div class="card radius-12 mb-24">
            <div class="card-header border-bottom bg-warning py-16 px-24">
                <h6 class="mb-0 fw-semibold text-dark">
                    <i class="ri-shield-check-line me-2"></i>Validation ENE CI
                </h6>
            </div>
            <div class="card-body p-24">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('admin.ene-validation.validate', $ticketPesee->id) }}" method="POST">
                            @csrf
                            <div class="mb-16">
                                <label for="commentaire_validation" class="form-label">Commentaire de validation (optionnel)</label>
                                <textarea class="form-control" id="commentaire_validation" name="commentaire" rows="3" placeholder="Commentaire optionnel..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="ri-check-line me-2"></i>
                                VALIDER LE PAIEMENT
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.ene-validation.reject', $ticketPesee->id) }}" method="POST">
                            @csrf
                            <div class="mb-16">
                                <label for="commentaire_rejet" class="form-label">Commentaire de rejet *</label>
                                <textarea class="form-control" id="commentaire_rejet" name="commentaire" rows="3" placeholder="Motif du rejet..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="ri-close-line me-2"></i>
                                REJETER LE PAIEMENT
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Statut Actuel -->
        <div class="card radius-12 mb-24">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="mb-0 fw-semibold">Statut ENE CI</h6>
            </div>
            <div class="card-body p-24">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center p-16 bg-light rounded">
                            <span class="fw-medium">Statut</span>
                            <span class="badge {{ $ticketPesee->statut_ene === 'valide_par_ene' ? 'bg-success' : 'bg-danger' }} fs-6">
                                {{ $ticketPesee->statut_ene === 'valide_par_ene' ? 'Validé par ENE CI' : 'Rejeté par ENE CI' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center p-16 bg-light rounded">
                            <span class="fw-medium">Date</span>
                            <span class="fw-bold">{{ $ticketPesee->date_validation_ene ? $ticketPesee->date_validation_ene->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @if($ticketPesee->commentaire_ene)
                <div class="mt-16">
                    <label class="form-label fw-medium">Commentaire ENE CI</label>
                    <div class="p-16 bg-light rounded">
                        <p class="mb-0">{{ $ticketPesee->commentaire_ene }}</p>
                    </div>
                </div>
                @endif
                
                @if($ticketPesee->statut_ene === 'valide_par_ene')
                <div class="mt-16">
                    <div class="alert alert-success mb-0">
                        <i class="ri-check-line me-2"></i>
                        <strong>Ce ticket est maintenant éligible à la facturation !</strong><br>
                        Vous pouvez procéder à la génération de la facture individuelle ou l'inclure dans une facture globale.
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Boutons d'Action -->
        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('admin.ene-validation.index') }}" class="btn btn-outline-secondary px-24 py-12 radius-8">
                <i class="ri-arrow-left-line me-2"></i>Retour à la Liste
            </a>
            
            @if($ticketPesee->statut_ene === 'valide_par_ene')
            <a href="#" class="btn btn-primary px-24 py-12 radius-8">
                <i class="ri-file-text-line me-2"></i>Générer Facture
            </a>
            @endif
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html> 