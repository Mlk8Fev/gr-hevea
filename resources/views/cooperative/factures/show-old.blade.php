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
                    <a href="{{ route('cooperative.factures.index') }}" class="hover-text-primary">Factures</a>
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
                        @if($facture->canBeValidated())
                            <form action="{{ route('admin.factures.validate', $facture) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Valider cette facture ?')">
                                    <i class="ri-check-line"></i>
                                    Valider
                                </button>
                            </form>
                        @endif
                        
                        @if($facture->canBePaid())
                            <button type="button" class="btn btn-success btn-sm" onclick="markAsPaid({{ $facture->id }}, {{ $facture->montant_ttc }})">
                                <i class="ri-eye-line icon me-1"></i>
                                Marquer comme payée
                            </button>
                        @endif
                        
                        @if($facture->statut === 'validee' || $facture->statut === 'payee')
                            <a href="{{ route('cooperative.factures.pdf', $facture) }}" class="btn btn-warning" target="_blank">
                                <i class="ri-file-pdf-line icon me-1"></i>
                                Télécharger PDF
                            </a>
                        @endif
                        
                        @if($facture->statut === 'brouillon')
                            <form action="{{ route('admin.factures.destroy', $facture) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette facture ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="ri-eye-line icon me-1"></i>
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-24">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Numéro de facture</span>
                            <span class="fw-bold text-primary text-lg">{{ $facture->numero_facture }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Type</span>
                            @if($facture->type === 'individuelle')
                                <span class="badge bg-info fs-6">Individuelle</span>
                            @else
                                <span class="badge bg-success fs-6">Globale</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="row mt-16">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Coopérative</span>
                            <span class="fw-medium">{{ $facture->cooperative->nom }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Statut</span>
                            @if($facture->statut === 'brouillon')
                                <span class="badge bg-warning">Brouillon</span>
                            @elseif($facture->statut === 'validee')
                                <span class="badge bg-info">Validée</span>
                            @elseif($facture->statut === 'payee')
                                <span class="badge bg-success">Payée</span>
                            @elseif($facture->statut === 'annulee')
                                <span class="badge bg-secondary">Annulée</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="row mt-16">
                    <div class="col-md-3">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Date d'émission</span>
                            <span class="fw-medium">{{ $facture->date_emission->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Date d'échéance</span>
                            <span class="fw-medium {{ $facture->isEnRetard() ? 'text-danger' : '' }}">
                                {{ $facture->date_echeance->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Montant TTC</span>
                            <span class="fw-bold text-success text-lg">{{ number_format($facture->montant_ttc, 0) }} FCFA</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Montant payé</span>
                            <span class="fw-bold text-info text-lg">{{ number_format($facture->montant_paye, 0) }} FCFA</span>
                        </div>
                    </div>
                </div>
                
                @if($facture->montant_paye > 0)
                <div class="row mt-16">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Montant restant</span>
                            <span class="fw-bold text-warning text-lg">{{ number_format($facture->montant_restant, 0) }} FCFA</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Date de paiement</span>
                            <span class="fw-medium">{{ $facture->date_paiement ? $facture->date_paiement->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($facture->conditions_paiement || $facture->notes)
                <div class="row mt-16">
                    @if($facture->conditions_paiement)
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Conditions de paiement</span>
                            <span class="fw-medium">{{ $facture->conditions_paiement }}</span>
                        </div>
                    </div>
                    @endif
                    @if($facture->notes)
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Notes</span>
                            <span class="fw-medium">{{ $facture->notes }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                @if($facture->valideePar)
                <div class="row mt-16">
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Validée par</span>
                            <span class="fw-medium">{{ $facture->valideePar->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column">
                            <span class="text-sm text-muted mb-1">Date de validation</span>
                            <span class="fw-medium">{{ $facture->date_validation->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Détails des Tickets -->
        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <h6 class="mb-0">Détails des Tickets Facturés</h6>
            </div>
            <div class="card-body p-24">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Ticket</th>
                                <th>Date</th>
                                <th>Poids Net</th>
                                <th>Prix Final</th>
                                <th>Montant</th>
                                <th>Centre de Collecte</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facture->factureTicketsPesee as $factureTicket)
                                @php
                                    $ticket = $factureTicket->ticketPesee;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="fw-medium">{{ $ticket->numero_ticket }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $ticket->date_entree->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="text-success fw-bold">{{ number_format($ticket->poids_net, 2) }} kg</span>
                                    </td>
                                    <td>
                                        <span class="text-primary fw-bold">{{ number_format($factureTicket->montant_ticket / $ticket->poids_net, 2) }} FCFA/kg</span>
                                    </td>
                                    <td>
                                        <span class="text-success fw-bold">{{ number_format($factureTicket->montant_ticket, 0) }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $ticket->connaissement->centreCollecte->nom }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold">Total :</td>
                                <td class="fw-bold text-success">{{ number_format($facture->montant_ttc, 0) }} FCFA</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Boutons d'action -->
        <div class="row mt-24">
            <div class="col-12">
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('cooperative.factures.index') }}" class="btn btn-outline-secondary px-24 py-12 radius-8">
                        <i class="ri-arrow-left-line me-2"></i>Retour à la Liste
                    </a>
                    <a href="{{ route('cooperative.factures.preview', $facture) }}" class="btn btn-primary px-24 py-12 radius-8" target="_blank">
                        <i class="ri-file-text-line me-2"></i>Preview Facture
                    </a>
                    <a href="{{ route('cooperative.factures.pdf', $facture) }}" class="btn btn-warning px-24 py-12 radius-8" target="_blank">
                        <i class="ri-file-pdf-line me-2"></i>Télécharger PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')

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
                        <label for="montant_paye" class="form-label">Montant payé (FCFA)</label>
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

<script>
function markAsPaid(factureId, montantTotal) {
    document.getElementById('montantTotal').textContent = montantTotal.toLocaleString();
    document.getElementById('montant_paye').value = montantTotal;
    document.getElementById('montant_paye').max = montantTotal;
    document.getElementById('markAsPaidForm').action = `/admin/factures/${factureId}/mark-as-paid`;
    document.getElementById('date_paiement').value = new Date().toISOString().split('T')[0];    
    new bootstrap.Modal(document.getElementById('markAsPaidModal')).show();
}
</script>

</body>
</html> 