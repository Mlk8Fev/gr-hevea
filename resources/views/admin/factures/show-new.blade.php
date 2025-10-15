@extends('layouts.app')

@section('content')
<main class="main-content">
    <div class="container-fluid">
        <!-- En-tête de la Facture -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-file-list-3-line me-2"></i>
                            FACTURE n° {{ $facture->numero_facture }}
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            @if($facture->canBeValidated())
                                <form action="{{ route('admin.factures.validate', $facture) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm" onclick="return confirm('Valider cette facture ?')">
                                        <i class="ri-check-line"></i>
                                        Valider
                                    </button>
                                </form>
                            @endif
                            
                            @if($facture->canBePaid())
                                <button type="button" class="btn btn-success btn-sm" onclick="markAsPaid({{ $facture->id }}, {{ $facture->montant_ttc }})">
                                    <i class="ri-money-dollar-circle-line me-1"></i>
                                    Marquer comme payée
                                </button>
                            @endif
                            
                            @if($facture->statut === 'validee' || $facture->statut === 'payee')
                                <a href="{{ route('admin.factures.pdf', $facture) }}" class="btn btn-warning" target="_blank">
                                    <i class="ri-file-pdf-line me-1"></i>
                                    Télécharger PDF
                                </a>
                            @endif
                            
                            @if($facture->statut === 'brouillon')
                                <form action="{{ route('admin.factures.destroy', $facture) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette facture ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="ri-delete-bin-line me-1"></i>
                                        Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de la Facture -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="ri-building-line me-2"></i>FACTURÉ À</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="fw-bold">Coopérative :</td>
                                <td>{{ $facture->cooperative->nom }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Secteur :</td>
                                <td>{{ $facture->cooperative->secteur->code }} - {{ $facture->cooperative->secteur->nom }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Président :</td>
                                <td>{{ $facture->cooperative->president }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Contact :</td>
                                <td>{{ $facture->cooperative->contact ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="ri-file-list-line me-2"></i>INFORMATIONS FACTURE</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="fw-bold">Type :</td>
                                <td>{{ ucfirst($facture->type) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Statut :</td>
                                <td>
                                    @if($facture->statut === 'brouillon')
                                        <span class="badge bg-warning">Brouillon</span>
                                    @elseif($facture->statut === 'validee')
                                        <span class="badge bg-info">Validée</span>
                                    @elseif($facture->statut === 'payee')
                                        <span class="badge bg-success">Payée</span>
                                    @else
                                        <span class="badge bg-danger">Annulée</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Date d'émission :</td>
                                <td>{{ $facture->date_emission ? $facture->date_emission->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Date d'échéance :</td>
                                <td>{{ $facture->date_echeance ? $facture->date_echeance->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nombre de tickets :</td>
                                <td>{{ $facture->ticketsPesee->count() }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Poids total :</td>
                                <td>{{ number_format($facture->poids_total, 2) }} kg</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($facture->ticketsPesee->count() > 0)
        <!-- Détail des Tickets de Pesée -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="ri-scales-line me-2"></i>DÉTAIL DES TICKETS DE PESÉE</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Ticket</th>
                                        <th>Date</th>
                                        <th>Poids Net (kg)</th>
                                        <th>Prix/Kg</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ticketsAvecPrix as $item)
                                    @php
                                        $ticket = $item['ticket'];
                                        $prix = $item['prix'];
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">{{ $ticket->numero_ticket }}</td>
                                        <td>{{ $ticket->date_entree->format('d/m/Y') }}</td>
                                        <td>{{ number_format($ticket->poids_net, 2) }}</td>
                                        <td>
                                            @if(isset($prix['details']['prix_final_public']))
                                                {{ number_format($prix['details']['prix_final_public'], 0) }} FCFA
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="fw-bold">
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
            </div>
        </div>

        <!-- Détails de Calcul -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="ri-calculator-line me-2"></i>DÉTAILS DE CALCUL</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Élément de Calcul</th>
                                        <th>Valeur</th>
                                        <th>Unité</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $firstTicket = $ticketsAvecPrix[0]['prix'] ?? null;
                                    @endphp
                                    @if($firstTicket && isset($firstTicket['details']))
                                        <tr>
                                            <td class="fw-bold">Prix de base</td>
                                            <td>{{ number_format($firstTicket['details']['prix_base'] ?? 0, 2) }}</td>
                                            <td>FCFA/kg</td>
                                            <td>Prix de référence</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Bonus qualité</td>
                                            <td>{{ number_format($firstTicket['details']['bonus_qualite'] ?? 0, 2) }}</td>
                                            <td>FCFA/kg</td>
                                            <td>Prime qualité</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Coût transport</td>
                                            <td>{{ number_format($firstTicket['details']['cout_transport'] ?? 0, 2) }}</td>
                                            <td>FCFA/kg</td>
                                            <td>Frais de transport</td>
                                        </tr>
                                        <tr class="table-success fw-bold">
                                            <td>Prix final</td>
                                            <td>{{ number_format($firstTicket['details']['prix_final_public'] ?? 0, 2) }}</td>
                                            <td>FCFA/kg</td>
                                            <td>Prix total par kg</td>
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
            </div>
        </div>
        @endif

        <!-- Totaux -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="ri-money-dollar-circle-line me-2"></i>TOTAUX</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td class="fw-bold">Poids Total :</td>
                                        <td class="text-end">{{ number_format($facture->poids_total, 2) }} kg</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Montant HT :</td>
                                        <td class="text-end">{{ number_format($facture->montant_ht, 0) }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">TVA (18%) :</td>
                                        <td class="text-end">{{ number_format($facture->montant_tva, 0) }} FCFA</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm mb-0">
                                    <tr class="table-primary fw-bold">
                                        <td>MONTANT TTC :</td>
                                        <td class="text-end">{{ number_format($facture->montant_ttc, 0) }} FCFA</td>
                                    </tr>
                                    @if($facture->montant_paye > 0)
                                    <tr>
                                        <td class="fw-bold">Montant Payé :</td>
                                        <td class="text-end">{{ number_format($facture->montant_paye, 0) }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Reste à Payer :</td>
                                        <td class="text-end">{{ number_format($facture->montant_restant, 0) }} FCFA</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('admin.factures.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Retour à la Liste
                    </a>
                    @if($facture->statut === 'validee' || $facture->statut === 'payee')
                        <a href="{{ route('admin.factures.pdf', $facture) }}" class="btn btn-warning" target="_blank">
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
@endsection
