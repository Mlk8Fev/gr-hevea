<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu d'Achat - {{ $recuAchat->numero_recu }}</title>
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
                <h6 class="fw-semibold mb-0">Reçu d'Achat #{{ $recuAchat->numero_recu }}</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="{{ route('admin.recus-achat.pdf', $recuAchat) }}" class="btn btn-primary">
                            <i class="ri-file-pdf-line"></i> Télécharger PDF
                        </a>
                    </li>
                    <li class="fw-medium">
                        <a href="{{ route('admin.recus-achat.edit', $recuAchat) }}" class="btn btn-warning">
                            <i class="ri-edit-line"></i> Modifier Signatures
                        </a>
                    </li>
                    <li class="fw-medium">
                        <a href="{{ route('admin.farmer-lists.show', $recuAchat->connaissement) }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line"></i> Retour
                        </a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Détails du Reçu d'Achat</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Informations du Producteur</h6>
                                    <p><strong>Nom :</strong> {{ $recuAchat->nom_producteur }}</p>
                                    <p><strong>Prénom :</strong> {{ $recuAchat->prenom_producteur }}</p>
                                    <p><strong>Téléphone :</strong> {{ $recuAchat->telephone_producteur }}</p>
                                    <p><strong>Code FPH-CI :</strong> {{ $recuAchat->code_fphci }}</p>
                                    <p><strong>Secteur :</strong> {{ $recuAchat->secteur_fphci }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Informations de la Livraison</h6>
                                    <p><strong>Centre de Collecte :</strong> {{ $recuAchat->centre_collecte }}</p>
                                    <p><strong>Date de Création :</strong> {{ $recuAchat->date_creation ? $recuAchat->date_creation->format('d/m/Y H:i') : 'N/A' }}</p>
                                    <p><strong>Quantité :</strong> {{ number_format($recuAchat->quantite_livree, 2) }} kg</p>
                                    <p><strong>Prix Unitaire :</strong> {{ number_format($recuAchat->prix_unitaire, 0) }} FCFA/kg</p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Calcul du Prix</h6>
                                    <p><strong>Prix Unitaire :</strong> {{ number_format($recuAchat->prix_unitaire, 2) }} FCFA/kg</p>
                                    <p><strong>Montant Total :</strong> {{ number_format($recuAchat->montant_total, 2) }} FCFA</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Informations Supplémentaires</h6>
                                    <p><strong>Numéro de Reçu :</strong> {{ $recuAchat->numero_recu }}</p>
                                    <p><strong>Créé par :</strong> {{ $recuAchat->createdBy ? $recuAchat->createdBy->name : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section des Signatures -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Signatures</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Signature Acheteur -->
                                <div class="col-md-6">
                                    <h6>Signature Acheteur</h6>
                                    <div class="signature-display">
                                        @if($recuAchat->signature_acheteur)
                                            <div class="signature-box border rounded p-3 mb-2" style="min-height: 120px; background-color: #f8f9fa;">
                                                <img src="{{ $recuAchat->signature_acheteur }}" 
                                                     alt="Signature Acheteur" 
                                                     class="img-fluid" 
                                                     style="max-height: 100px;">
                                            </div>
                                            <small class="text-success">
                                                <iconify-icon icon="ri-check-circle-fill"></iconify-icon>
                                                Signature présente
                                            </small>
                                        @else
                                            <div class="signature-box border rounded p-3 mb-2" style="min-height: 120px; background-color: #f8f9fa;">
                                                <div class="text-center text-muted">
                                                    <iconify-icon icon="ri-file-text-line" style="font-size: 2rem;"></iconify-icon>
                                                    <p class="mb-0">Aucune signature</p>
                                                </div>
                                            </div>
                                            <small class="text-warning">
                                                <iconify-icon icon="ri-alert-line"></iconify-icon>
                                                Signature manquante
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Signature Producteur -->
                                <div class="col-md-6">
                                    <h6>Signature Producteur</h6>
                                    <div class="signature-display">
                                        @if($recuAchat->signature_producteur)
                                            <div class="signature-box border rounded p-3 mb-2" style="min-height: 120px; background-color: #f8f9fa;">
                                                <img src="{{ $recuAchat->signature_producteur }}" 
                                                     alt="Signature Producteur" 
                                                     class="img-fluid" 
                                                     style="max-height: 100px;">
                                            </div>
                                            <small class="text-success">
                                                <iconify-icon icon="ri-check-circle-fill"></iconify-icon>
                                                Signature présente
                                            </small>
                                        @else
                                            <div class="signature-box border rounded p-3 mb-2" style="min-height: 120px; background-color: #f8f9fa;">
                                                <div class="text-center text-muted">
                                                    <iconify-icon icon="ri-file-text-line" style="font-size: 2rem;"></iconify-icon>
                                                    <p class="mb-0">Aucune signature</p>
                                                </div>
                                            </div>
                                            <small class="text-warning">
                                                <iconify-icon icon="ri-alert-line"></iconify-icon>
                                                Signature manquante
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.wowdash-scripts')
</body>
</html> 