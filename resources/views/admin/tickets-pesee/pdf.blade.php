<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Pesée - {{ $ticketPesee->numero_ticket }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            line-height: 1.1;
            margin: 0;
            padding: 0;
        }
        .shadow-4 {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .border {
            border: 1px solid #dee2e6;
        }
        .radius-8 {
            border-radius: 6px;
        }
        .p-20 {
            padding: 12px;
        }
        .py-40 {
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .py-28 {
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .px-20 {
            padding-left: 12px;
            padding-right: 12px;
        }
        .mt-24 {
            margin-top: 12px;
        }
        .mb-16 {
            margin-bottom: 8px;
        }
        .mb-8 {
            margin-bottom: 4px;
        }
        .mb-1 {
            margin-bottom: 1px;
        }
        .mb-0 {
            margin-bottom: 0;
        }
        .text-xl {
            font-size: 16px;
        }
        .text-md {
            font-size: 12px;
        }
        .text-base {
            font-size: 10px;
        }
        .text-sm {
            font-size: 9px;
        }
        .text-primary-light {
            color: #6c757d;
        }
        .text-dark {
            color: #000;
        }
        .text-secondary-light {
            color: #6c757d;
        }
        .fw-semibold {
            font-weight: 600;
        }
        .fw-bold {
            font-weight: bold;
        }
        .d-flex {
            display: flex;
        }
        .flex-wrap {
            flex-wrap: wrap;
        }
        .justify-content-between {
            justify-content: space-between;
        }
        .justify-content-start {
            justify-content: flex-start;
        }
        .align-items-end {
            align-items: flex-end;
        }
        .gap-3 {
            gap: 8px;
        }
        .gap-4 {
            gap: 12px;
        }
        .border-bottom {
            border-bottom: 1px solid #dee2e6;
        }
        .text-center {
            text-align: center;
        }
        .ps-8 {
            padding-left: 20px;
        }
        .img-fluid {
            max-width: 100%;
            height: auto;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        .col-lg-10 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .justify-content-center {
            justify-content: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        strong {
            font-weight: bold;
        }
        .logo {
            max-height: 50px !important;
        }
    </style>
</head>
<body>
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
                            <img src="{{ public_path('wowdash/images/fph-ci.png') }}" alt="FPH-CI" class="img-fluid logo">
                        </div>
                    </div>
                </div>
                
                <!-- Informations principales -->
                <div class="py-28 px-20">
                    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                        <div>
                            <h6 class="text-md mb-16 text-center">Informations Coopérative:</h6>
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
                                        <td>N° Connaissement</td>
                                        <td class="ps-8">: {{ $ticketPesee->connaissement->numero }}</td>
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
                            <div style="padding-left: 60px;">
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Poids Entrée:</span> <span class="text-dark fw-bold">{{ number_format($ticketPesee->poids_entree, 2) }} kg</span></p>
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Poids Sortie:</span> <span class="text-dark fw-bold">{{ number_format($ticketPesee->poids_sortie, 2) }} kg</span></p>
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Poids Net:</span> <span class="text-dark fw-bold">{{ number_format($ticketPesee->poids_net, 2) }} kg</span></p>
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Nombre de Sacs:</span> <span class="text-dark fw-bold">{{ $ticketPesee->nombre_sacs_bidons_cartons }} unités</span></p>
                            </div>
                            <div style="padding-left: 200px;">
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">N° Camion:</span> <span class="text-dark fw-bold">{{ $ticketPesee->numero_camion }}</span></p>
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Transporteur:</span> <span class="text-dark fw-bold">{{ $ticketPesee->transporteur }}</span></p>
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Chauffeur:</span> <span class="text-dark fw-bold">{{ $ticketPesee->chauffeur }}</span></p>
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Nom du Peseur:</span> <span class="text-dark fw-bold">{{ $ticketPesee->nom_peseur }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Analyse Qualité -->
                    <div class="mt-24">
                        <h6 class="text-md mb-16 text-center">Analyse Qualité:</h6>
                        <div class="d-flex flex-wrap justify-content-start gap-4">
                            <div style="padding-left: 60px;">
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Poids 100 graines:</span> <span class="text-dark fw-bold">{{ $ticketPesee->poids_100_graines }} g</span></p>
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Graine Pourrie (GP):</span> <span class="text-dark fw-bold">{{ $ticketPesee->gp }}%</span></p>
                                <p class="text-base mb-0"><span class="text-primary-light fw-semibold">Graine Avortée (GA):</span> <span class="text-dark fw-bold">{{ $ticketPesee->ga }}%</span></p>
                            </div>
                            <div style="padding-left: 200px;">
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
</body>
</html> 