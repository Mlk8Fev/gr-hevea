<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Connaissement - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
    <style>
        @media print {
            /* Masquer tous les éléments avec la classe no-print */
            .no-print {
                display: none !important;
            }
            
            /* Masquer tous les éléments sauf la card du connaissement */
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
            #connaissement {
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
            
            /* Garder seulement la card interne sans bordure - STYLE IMPRESSION COMPACT */
            .shadow-4.border.radius-8 {
                border: none !important;
                padding: 10px !important;
                margin: 0 !important;
            }
            
            /* Réduire les tailles de police pour l'impression - STYLE IMPRESSION COMPACT */
            .text-xl {
                font-size: 14px !important;
            }
            
            .text-md {
                font-size: 10px !important;
            }
            
            .text-base {
                font-size: 8px !important;
            }
            
            .text-sm {
                font-size: 7px !important;
            }
            
            .text-xs {
                font-size: 6px !important;
            }
            
            /* Réduire les marges et espacements - STYLE IMPRESSION COMPACT */
            .mt-24 {
                margin-top: 6px !important;
            }
            
            .mb-16 {
                margin-bottom: 5px !important;
            }
            
            .mb-8 {
                margin-bottom: 3px !important;
            }
            
            .py-28 {
                padding-top: 10px !important;
                padding-bottom: 10px !important;
            }
            
            .px-20 {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            
            .py-16 {
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }
            
            .px-16 {
                padding-left: 5px !important;
                padding-right: 5px !important;
            }
            
            table td {
                padding: 1px 0 !important;
            }
            
            .ps-8 {
                padding-left: 15px !important;
            }
            
            .ps-4 {
                padding-left: 8px !important;
            }
            
            .gap-3 {
                gap: 5px !important;
            }
            
            .gap-4 {
                gap: 8px !important;
            }
            
            .logo-section img {
                max-height: 40px !important;
            }
            
            [style*="padding-left: 100px"] {
                padding-left: 30px !important;
            }
            
            [style*="padding-left: 350px"] {
                padding-left: 150px !important;
            }
            
            [style*="padding-left: 400px"] {
                padding-left: 150px !important;
            }
            
            /* Optimisations spécifiques pour l'impression */
            .table {
                font-size: 6px !important;
            }
            
            .table th {
                padding: 2px !important;
                font-size: 7px !important;
            }
            
            .table td {
                padding: 1px 2px !important;
                font-size: 6px !important;
            }
            
            .mt-16 {
                margin-top: 5px !important;
            }
            
            .mb-8 {
                margin-bottom: 3px !important;
            }
            
            .gap-3 {
                gap: 5px !important;
            }
            
            /* Réduire la taille des cases de signature */
            [style*="min-height: 40px"] {
                min-height: 25px !important;
            }
            
            [style*="min-width: 80px"] {
                min-width: 50px !important;
            }
            
            .p-2 {
                padding: 3px !important;
            }
        }
    </style>
</head>
<body>
@include('partials.sidebar', ['navigation' => $navigation])
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Détails du Connaissement</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.connaissements.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Liste des Connaissements
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">{{ $connaissement->numero }}</li>
            </ul>
        </div>
        
        <!-- Status Badge -->
        <div class="row mb-4 no-print">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="ri-information-line me-2"></i>
                    <strong>Statut:</strong>
                    @if($connaissement->statut === 'programme')
                        <span class="badge bg-warning ms-2">Programmé</span>
                    @elseif($connaissement->statut === 'valide')
                        <span class="badge bg-success ms-2">Validé pour ticket de pesée</span>
                    @else
                        <span class="badge bg-secondary ms-2">Archivé</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header no-print">
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-warning radius-8 d-inline-flex align-items-center gap-1" onclick="printConnaissement()">
                        <iconify-icon icon="basil:printer-outline" class="text-xl"></iconify-icon>
                        Imprimer
                    </button>
                </div>
            </div>
            <div class="card-body py-40">
                <div class="row justify-content-center" id="connaissement">
                    <div class="col-lg-10">
                        <div class="shadow-4 border radius-8">
                            <!-- En-tête du connaissement -->
                            <div class="p-20 d-flex flex-wrap justify-content-between gap-3 border-bottom">
                                <div>
                                    <h3 class="text-xl">{{ $connaissement->numero }}</h3>
                                    <p class="mb-1 text-sm">Date de Création: {{ $connaissement->created_at->format('d/m/Y') }}</p>
                                    <p class="mb-0 text-sm">Heure: {{ $connaissement->created_at->format('H:i') }}</p>
                                </div>
                                <div>
                                    <div class="mb-8">
                                        <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="FPH-CI" class="img-fluid" style="max-height: 80px;">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations principales -->
                            <div class="py-16 px-16">
                                <table class="table bordered-table sm-table mb-0 text-xs">
                                    <tbody>
                                        <tr>
                                            <th colspan="4" class="bg-base text-dark fw-semibold text-center py-2">Informations Coopérative</th>
                                        </tr>
                                        <tr>
                                            <td class="py-1">Coopérative</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->cooperative->nom }}</span></td>
                                            <td class="py-1">Centre de Collecte</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->centreCollecte->nom }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">Lieu de Départ</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->lieu_depart }}</span></td>
                                            <td class="py-1">Sous-Préfecture</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->sous_prefecture }}</span></td>
                                        </tr>

                                        <tr>
                                            <th colspan="4" class="bg-base text-dark fw-semibold text-center py-2">Informations Connaissement</th>
                                        </tr>
                                        <tr>
                                            <td class="py-1">N° Connaissement</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->numero }}</span></td>
                                            <td class="py-1">Destinataire Type</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ ucfirst($connaissement->destinataire_type) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">Nombre de Sacs</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->nombre_sacs }}</span></td>
                                            <td class="py-1"></td>
                                            <td class="py-1"></td>
                                        </tr>

                                        <tr>
                                            <th colspan="4" class="bg-base text-dark fw-semibold text-center py-2">Détails de Transport</th>
                                        </tr>
                                        <tr>
                                            <td class="py-1">Transporteur</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->transporteur_nom }}</span></td>
                                            <td class="py-1">Immatriculation</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->transporteur_immatriculation }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">Chauffeur</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->chauffeur_nom }}</span></td>
                                            <td class="py-1">Poids Brut Estimé</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ number_format($connaissement->poids_brut_estime, 2) }} kg</span></td>
                                        </tr>
                                        @if($connaissement->poids_net)
                                        <tr>
                                            <td class="py-1">Poids Net Réel</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ number_format($connaissement->poids_net, 2) }} kg</span></td>
                                            <td class="py-1"></td>
                                            <td class="py-1"></td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th colspan="4" class="bg-base text-dark fw-semibold text-center py-2">Programmation et Validation</th>
                                        </tr>
                                        <tr>
                                            <td class="py-1">Date Réception</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->date_reception ? $connaissement->date_reception->format('d/m/Y') : '-' }}</span></td>
                                            <td class="py-1">Heure Arrivée</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->heure_arrivee ? $connaissement->heure_arrivee : '-' }}</span></td>
                                        </tr>
                                        @if($connaissement->date_validation)
                                        <tr>
                                            <td class="py-1">Date Validation</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->date_validation->format('d/m/Y') }}</span></td>
                                            <td class="py-1"></td>
                                            <td class="py-1"></td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th colspan="4" class="bg-base text-dark fw-semibold text-center py-2">Informations de Création</th>
                                        </tr>
                                        <tr>
                                            <td class="py-1">Crée par</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->createdBy->name ?? 'N/A' }}</span></td>
                                            <td class="py-1">Date Création</td>
                                            <td class="ps-4 py-1"><span class="text-dark fw-bold">{{ $connaissement->created_at->format('d/m/Y H:i') }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Signatures en bas -->
                                <div class="mt-16 text-center">
                                    <h6 class="text-sm mb-8">Signatures</h6>
                                    <div class="d-flex justify-content-center gap-3">
                                        @if($connaissement->signature_cooperative)
                                        <div class="text-center">
                                            <p class="text-xs mb-1">Signature Coopérative</p>
                                            <div class="border rounded p-2" style="min-height: 40px; min-width: 80px;">
                                                <span class="text-success">✓</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if($connaissement->signature_fphci)
                                        <div class="text-center">
                                            <p class="text-xs mb-1">Signature FPH-CI</p>
                                            <div class="border rounded p-2" style="min-height: 40px; min-width: 80px;">
                                                <span class="text-success">✓</span>
                                            </div>
                                        </div>
                                        @endif
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
function printConnaissement() {
    window.print();
}
</script>
</body>
</html> 