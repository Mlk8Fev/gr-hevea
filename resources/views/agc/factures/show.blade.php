<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $facture->numero_facture }} - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar', ['navigation' => $navigation])

<main class="dashboard-main">
    @include('partials.navbar-header')
    
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">
                Facture {{ $facture->numero_facture }}
            </h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('cs.factures.index') }}" class="hover-text-primary">Factures</a>
                </li>
                <li>-</li>
                <li class="fw-medium">{{ $facture->numero_facture }}</li>
            </ul>
        </div>

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

        <!-- En-tête de la Facture -->
        <div class="card h-100 p-0 radius-12 mb-24">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Informations de la Facture</h6>
                    <div class="d-flex align-items-center gap-2">
                        @if($facture->canBePaid())
                            <button type="button" class="btn btn-success btn-sm" onclick="markAsPaid({{ $facture->id }}, {{ $facture->montant_ttc }})">
                                <i class="ri-money-dollar-circle-line me-1"></i>
                                Marquer comme payée
                            </button>
                        @endif
                        
                        @if($facture->statut === 'validee' || $facture->statut === 'payee')
                            <a href="{{ route('cs.factures.pdf', $facture) }}" class="btn btn-warning" target="_blank">
                                <i class="ri-file-pdf-line me-1"></i>
                                Télécharger PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-24">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="ri-building-line me-2"></i>FACTURÉ À
                            </h6>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Coopérative :</span>
                                    <span class="fw-semibold">{{ $facture->cooperative->nom }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Secteur :</span>
                                    <span class="fw-semibold">{{ $facture->cooperative->secteur->code }} - {{ $facture->cooperative->secteur->nom }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Président :</span>
                                    <span class="fw-semibold">{{ $facture->cooperative->president }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Contact :</span>
                                    <span class="fw-semibold">{{ $facture->cooperative->contact ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <h6 class="fw-semibold mb-3 text-info">
                                <i class="ri-file-list-line me-2"></i>INFORMATIONS FACTURE
                            </h6>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Type :</span>
                                    <span class="fw-semibold">{{ ucfirst($facture->type) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Statut :</span>
                                    <span>
                                        @if($facture->statut === 'brouillon')
                                            <span class="badge bg-warning">Brouillon</span>
                                        @elseif($facture->statut === 'validee')
                                            <span class="badge bg-info">Validée</span>
                                        @elseif($facture->statut === 'payee')
                                            <span class="badge bg-success">Payée</span>
                                        @else
                                            <span class="badge bg-danger">Annulée</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Date d'émission :</span>
                                    <span class="fw-semibold">{{ $facture->date_emission ? $facture->date_emission->format('d/m/Y') : '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Date d'échéance :</span>
                                    <span class="fw-semibold">{{ $facture->date_echeance ? $facture->date_echeance->format('d/m/Y') : '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Nombre de tickets :</span>
                                    <span class="fw-semibold">{{ $facture->ticketsPesee->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Poids total :</span>
                                    <span class="fw-semibold">{{ number_format($facture->poids_total, 2) }} kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($facture->ticketsPesee->count() > 0)
        <!-- Détail des Tickets de Pesée -->
        <div class="card h-100 p-0 radius-12 mb-24">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="mb-0 text-success">
                    <i class="ri-scales-line me-2"></i>DÉTAIL DES TICKETS DE PESÉE
                </h6>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">N° Ticket</th>
                                <th class="border-0">Date</th>
                                <th class="border-0">Poids Net (kg)</th>
                                <th class="border-0">Prix/Kg</th>
                                <th class="border-0">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ticketsAvecPrix as $item)
                            @php
                                $ticket = $item['ticket'];
                                $prix = $item['prix'];
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $ticket->numero_ticket }}</td>
                                <td>{{ $ticket->date_entree->format('d/m/Y') }}</td>
                                <td>{{ number_format($ticket->poids_net, 2) }}</td>
                                <td>
                                    @if(isset($prix['details']['prix_final_public']))
                                        {{ number_format($prix['details']['prix_final_public'], 0) }} FCFA
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="fw-bold text-success">
                                    @if(isset($prix['details']['prix_final_public']))
                                        {{ number_format($ticket->poids_net * $prix['details']['prix_final_public'], 0) }} FCFA
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Détails de Calcul -->
        <div class="card h-100 p-0 radius-12 mb-24">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="mb-0 text-warning">
                    <i class="ri-calculator-line me-2"></i>DÉTAILS DE CALCUL
                </h6>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Élément de Calcul</th>
                                <th class="border-0">Valeur</th>
                                <th class="border-0">Unité</th>
                                <th class="border-0">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $firstTicket = $ticketsAvecPrix[0]['prix'] ?? null;
                            @endphp
                            @if($firstTicket && isset($firstTicket['details']))
                                <tr>
                                    <td class="fw-semibold">Prix de base</td>
                                    <td>{{ number_format($firstTicket['details']['prix_base'] ?? 0, 2) }}</td>
                                    <td>FCFA/kg</td>
                                    <td class="text-muted">Prix de référence</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Bonus qualité</td>
                                    <td>{{ number_format($firstTicket['details']['bonus_qualite'] ?? 0, 2) }}</td>
                                    <td>FCFA/kg</td>
                                    <td class="text-muted">Prime qualité</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Coût transport</td>
                                    <td>{{ number_format($firstTicket['details']['cout_transport'] ?? 0, 2) }}</td>
                                    <td>FCFA/kg</td>
                                    <td class="text-muted">Frais de transport</td>
                                </tr>
                                <tr class="table-success">
                                    <td class="fw-bold">Prix final</td>
                                    <td class="fw-bold">{{ number_format($firstTicket['details']['prix_final_public'] ?? 0, 2) }}</td>
                                    <td class="fw-bold">FCFA/kg</td>
                                    <td class="fw-bold">Prix total par kg</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Calcul non disponible</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Totaux -->
        <div class="card h-100 p-0 radius-12 mb-24">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="mb-0 text-dark">
                    <i class="ri-money-dollar-circle-line me-2"></i>TOTAUX
                </h6>
            </div>
            <div class="card-body p-24">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Poids Total :</span>
                                <span class="fw-semibold">{{ number_format($facture->poids_total, 2) }} kg</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Montant HT :</span>
                                <span class="fw-semibold">{{ number_format($facture->montant_ht, 0) }} FCFA</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">TVA (18%) :</span>
                                <span class="fw-semibold">{{ number_format($facture->montant_tva, 0) }} FCFA</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between border-top pt-2">
                                <span class="fw-bold text-primary">MONTANT TTC :</span>
                                <span class="fw-bold text-primary">{{ number_format($facture->montant_ttc, 0) }} FCFA</span>
                            </div>
                            @if($facture->montant_paye > 0)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Montant Payé :</span>
                                <span class="fw-semibold text-success">{{ number_format($facture->montant_paye, 0) }} FCFA</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Reste à Payer :</span>
                                <span class="fw-semibold text-warning">{{ number_format($facture->montant_restant, 0) }} FCFA</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('cs.factures.index') }}" class="btn btn-outline-secondary px-24 py-12 radius-8">
                        <i class="ri-arrow-left-line me-2"></i>Retour à la Liste
                    </a>
                    @if($facture->statut === 'validee' || $facture->statut === 'payee')
                        <a href="{{ route('cs.factures.pdf', $facture) }}" class="btn btn-warning px-24 py-12 radius-8" target="_blank">
                            <i class="ri-file-pdf-line me-2"></i>Télécharger PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal pour marquer comme payée -->
<div class="modal fade" id="markAsPaidModal" tabindex="-1" aria-labelledby="markAsPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAsPaidModalLabel">Marquer comme payée</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="markAsPaidForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="montant_paye" class="form-label">Montant payé</label>
                        <input type="number" class="form-control" id="montant_paye" name="montant_paye" step="0.01" required>
                        <div class="form-text">Montant total de la facture : <span id="montantTotal"></span> FCFA</div>
                    </div>
                    <div class="mb-3">
                        <label for="date_paiement" class="form-label">Date de paiement</label>
                        <input type="date" class="form-control" id="date_paiement" name="date_paiement" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer le paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('partials.wowdash-scripts')

<script>
function markAsPaid(factureId, montantTotal) {
    document.getElementById('montantTotal').textContent = montantTotal.toLocaleString();
    document.getElementById('montant_paye').value = montantTotal;
    document.getElementById('montant_paye').max = montantTotal;
    document.getElementById('markAsPaidForm').action = `/cooperative/factures/${factureId}/mark-as-paid`;
    document.getElementById('date_paiement').value = new Date().toISOString().split('T')[0];    
    new bootstrap.Modal(document.getElementById('markAsPaidModal')).show();
}
</script>

</body>
</html>