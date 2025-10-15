<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Ticket de Pesée - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
    <style>
        @media print {
            /* Masquer tous les éléments avec la classe no-print */
            .no-print {
                display: none !important;
            }
            
            /* Masquer tous les éléments sauf la card du ticket */
            .sidebar,
            .navbar-header,
            .breadcrumb,
            .card-header,
            .btn,
            .d-flex.flex-wrap.align-items-center.justify-content-between.gap-3.mb-24,
            .row.mb-4,
            .alert {
                display: none !important;
            }
            
            /* Masquer le conteneur principal et afficher seulement la card */
            .dashboard-main-body {
                margin: 0 !important;
                padding: 0 !important;
            }
            
            /* Afficher seulement la card */
            #ticket {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: none !important;
            }
            
            /* Supprimer les bordures et ombres de la card externe */
            .card {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .card-body {
                padding: 0 !important;
            }
            
            /* Garder seulement la card interne sans bordure - STYLE IMPRESSION (PLUS GRAND) */
            .shadow-4.border.radius-8 {
                border: none !important;
                padding: 20px !important;
                margin: 0 !important;
            }
            
            /* Réduire les tailles de police pour l'impression - STYLE IMPRESSION (PLUS GRAND) */
            .text-xl {
                font-size: 18px !important;
            }
            
            .text-md {
                font-size: 14px !important;
            }
            
            .text-base {
                font-size: 12px !important;
            }
            
            .text-sm {
                font-size: 10px !important;
            }
            
            /* Réduire les marges et espacements - STYLE IMPRESSION (PLUS GRAND) */
            .mt-24 {
                margin-top: 15px !important;
            }
            
            .mb-16 {
                margin-bottom: 10px !important;
            }
            
            .mb-8 {
                margin-bottom: 6px !important;
            }
            
            .py-28 {
                padding-top: 20px !important;
                padding-bottom: 20px !important;
            }
            
            .px-20 {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
            
            /* Optimiser les tableaux - STYLE IMPRESSION (PLUS GRAND) */
            table td {
                padding: 4px 0 !important;
            }
            
            .ps-8 {
                padding-left: 25px !important;
            }
            
            /* Réduire les gaps - STYLE IMPRESSION (PLUS GRAND) */
            .gap-3 {
                gap: 12px !important;
            }
            
            .gap-4 {
                gap: 16px !important;
            }
            
            /* Optimiser le logo - STYLE IMPRESSION (PLUS GRAND) */
            .logo-section img {
                max-height: 65px !important;
                width: auto !important;
            }
            
            /* Réduire les paddings-left - STYLE IMPRESSION (PLUS GRAND) */
            [style*="padding-left: 100px"] {
                padding-left: 70px !important;
            }
            
            [style*="padding-left: 350px"] {
                padding-left: 220px !important;
            }
            
            [style*="padding-left: 400px"] {
                padding-left: 220px !important;
            }
        }
    </style>
</head>
<body>
@include('partials.sidebar', ['navigation' => $navigation])
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24 no-print">
            <h6 class="fw-semibold mb-0">Ticket de Pesée</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('admin.tickets-pesee.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Liste des Tickets
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">{{ $ticketPesee->numero_ticket }}</li>
            </ul>
        </div>
        
        <!-- Status Badge -->
        <div class="row mb-4 no-print">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="ri-information-line me-2"></i>
                    <strong>Statut:</strong>
                    @if($ticketPesee->statut === 'en_attente')
                        <span class="badge bg-warning ms-2">En attente de validation</span>
                    @elseif($ticketPesee->statut === 'valide')
                        <span class="badge bg-success ms-2">Validé pour paiement</span>
                    @else
                        <span class="badge bg-secondary ms-2">Archivé</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header no-print">
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
                    @if(auth()->check() && auth()->user()->role !== 'agc')
                    <button type="button" class="btn btn-sm btn-warning radius-8 d-inline-flex align-items-center gap-1" onclick="printTicket()">
                        <i class="ri-printer-line text-xl"></i>
                        Imprimer
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body py-40">
                <div class="row justify-content-center" id="ticket">
                    <div class="col-lg-10">
                        <div class="shadow-4 border radius-8">
                            <!-- En-tête du ticket -->
                            <div class="p-20 d-flex flex-wrap justify-content-between gap-3 border-bottom">
                                <div>
                                    <h3 class="text-xl">{{ $ticketPesee->numero_ticket }}</h3>
                                    <p class="mb-1 text-sm">Date de Pesée: {{ $ticketPesee->date_entree->format('d/m/Y') }}</p>
                                    <p class="mb-0 text-sm">Heure: {{ $ticketPesee->heure_entree }}</p>
                                </div>
                                <div>
                                    <div class="mb-8">
                                        <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="FPH-CI" class="img-fluid" style="max-height: 80px;">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations principales -->
                            <div class="py-28 px-20">
                                <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                                    <div>
                                        <h6 class="text-md text-center">Informations Coopérative:</h6>
                                        <table class="text-sm text-secondary-light">
                                            <tbody>
                                                <tr>
                                                    <td>Coopérative</td>
                                                    <td class="ps-8">: {{ $ticketPesee->connaissement->cooperative->nom }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Centre de Collecte</td>
                                                    <td class="ps-8">: {{ $ticketPesee->connaissement->centreCollecte->nom }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Campagne</td>
                                                    <td class="ps-8">: {{ $ticketPesee->campagne }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Produit</td>
                                                    <td class="ps-8">: {{ $ticketPesee->produit }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        <table class="text-sm text-secondary-light">
                                            <tbody>
                                                <tr>
                                                    <td>N° Livraison</td>
                                                    <td class="ps-8">: {{ $ticketPesee->connaissement->numero_livraison }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Client</td>
                                                    <td class="ps-8">: {{ $ticketPesee->client }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Fournisseur</td>
                                                    <td class="ps-8">: {{ $ticketPesee->fournisseur }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Détails de pesée -->
                                <div class="mt-24">
                                    <h6 class="text-md mb-16 text-center">Détails de Pesée:</h6>
                                    <div class="d-flex flex-wrap justify-content-start gap-4">
                                        <div style="padding-left: 100px;">
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Poids Entrée:</span> <span class="text-dark fw-bold">{{ number_format($ticketPesee->poids_entree, 2) }} kg</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Poids Sortie:</span> <span class="text-dark fw-bold">{{ number_format($ticketPesee->poids_sortie, 2) }} kg</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Poids Net:</span> <span class="text-dark fw-bold">{{ number_format($ticketPesee->poids_net, 2) }} kg</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Nombre de Sacs:</span> <span class="text-dark fw-bold">{{ $ticketPesee->nombre_sacs_bidons_cartons }} unités</span></p>
                                        </div>
                                        <div style="padding-left: 350px;">
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">N° Camion:</span> <span class="text-dark fw-bold">{{ $ticketPesee->numero_camion }}</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Transporteur:</span> <span class="text-dark fw-bold">{{ $ticketPesee->numero_camion }}</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Chauffeur:</span> <span class="text-dark fw-bold">{{ $ticketPesee->chauffeur }}</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Nom du Peseur:</span> <span class="text-dark fw-bold">{{ $ticketPesee->nom_peseur }}</span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Analyse Qualité -->
                                <div class="mt-24">
                                    <h6 class="text-md mb-16 text-center">Analyse Qualité:</h6>
                                    <div class="d-flex flex-wrap justify-content-start gap-4">
                                        <div style="padding-left: 100px;">
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Poids 100 graines:</span> <span class="text-dark fw-bold">{{ $ticketPesee->poids_100_graines }} g</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Graine Pourrie (GP):</span> <span class="text-dark fw-bold">{{ $ticketPesee->gp }}%</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Graine Avortée (GA):</span> <span class="text-dark fw-bold">{{ $ticketPesee->ga }}%</span></p>
                                        </div>
                                        <div style="padding-left: 400px;">
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Corps Étranger (ME):</span> <span class="text-dark fw-bold">{{ $ticketPesee->me }}%</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Taux Humidité:</span> <span class="text-dark fw-bold">{{ $ticketPesee->taux_humidite }}%</span></p>
                                            <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Taux Impuretés Total:</span> <span class="text-dark fw-bold">{{ $ticketPesee->taux_impuretes }}%</span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Horaires -->
                                <div class="mt-24">
                                    <h6 class="text-md mb-16 text-center">Horaires:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-base mb-1"><strong>Entrée:</strong> <span class="text-dark fw-bold">{{ $ticketPesee->date_entree->format('d/m/Y') }} à {{ $ticketPesee->heure_entree }}</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-base mb-1"><strong>Sortie:</strong> <span class="text-dark fw-bold">{{ $ticketPesee->date_sortie->format('d/m/Y') }} à {{ $ticketPesee->heure_sortie }}</span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations supplémentaires -->
                                <div class="mt-24">
                                    <div class="d-flex flex-wrap justify-content-between gap-2">
                                        <div>
                                            <p class="text-sm mb-0"><span class="text-primary-light fw-semibold">Créé par:</span> {{ $ticketPesee->createdBy->name }}</p>
                                        </div>
                                    </div>
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

<script>
function printTicket() {
    // Imprimer directement la page avec le CSS d'impression optimisé
    window.print();
}
</script>

</body>
</html> 