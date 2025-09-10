<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Facture - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
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
                Créer une Facture {{ ucfirst($type) }}
            </h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.factures.index') }}" class="hover-text-primary">Factures</a>
                </li>
                <li>-</li>
                <li class="fw-medium">Créer Facture {{ ucfirst($type) }}</li>
            </ul>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.factures.store') }}" method="POST" id="createFactureForm">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            
            <!-- Sélection des Tickets -->
            <div class="col-12">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Sélection des Tickets à Facturer</h6>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAllTickets()">
                                <iconify-icon icon="lucide:check-square" class="icon me-1"></iconify-icon>
                                Tout sélectionner
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAllTickets()">
                                <iconify-icon icon="lucide:square" class="icon me-1"></iconify-icon>
                                Tout désélectionner
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-24">
                        @if(count($ticketsAvecPrix) === 0)
                            <div class="text-center py-24">
                                <iconify-icon icon="majesticons:inbox-line" class="text-6xl text-muted"></iconify-icon>
                                <h6 class="mt-3 text-muted">Aucun ticket éligible</h6>
                                <p class="text-muted mb-0">Tous les tickets validés par ENE CI ont déjà été facturés.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                            <th>N° Ticket</th>
                                            <th>Coopérative</th>
                                            <th>Poids Net</th>
                                            <th>Prix Final</th>
                                            <th>Montant</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ticketsAvecPrix as $item)
                                            @php
                                                $ticket = $item['ticket'];
                                                $prix = $item['prix'];
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="tickets_ids[]" value="{{ $ticket->id }}" 
                                                           class="form-check-input ticket-checkbox" 
                                                           data-montant="{{ $prix ? $prix['details']['montant_public'] : 0 }}">
                                                </td>
                                                <td>
                                                    <span class="fw-medium">{{ $ticket->numero_livraison }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $ticket->connaissement->cooperative->nom }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-success fw-bold">{{ number_format($ticket->poids_net, 2) }} kg</span>
                                                </td>
                                                <td>
                                                    @if($prix && !isset($prix['erreur']))
                                                        <span class="text-primary fw-bold">{{ number_format($prix['details']['prix_final_public'], 2) }} FCFA</span>
                                                    @else
                                                        <span class="text-danger">Erreur calcul</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($prix && !isset($prix['erreur']))
                                                        <span class="text-success fw-bold">{{ number_format($prix['details']['montant_public'], 0) }} FCFA</span>
                                                    @else
                                                        <span class="text-danger">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $ticket->date_entree->format('d/m/Y') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Résumé de la Sélection -->
                            <div class="mt-24 p-16 bg-light rounded">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="text-muted small">Tickets Sélectionnés</span>
                                            <span class="fw-bold text-primary" id="ticketsCount">0</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="text-muted small">Poids Total</span>
                                            <span class="fw-bold text-success" id="poidsTotal">0 kg</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="text-muted small">Montant Total</span>
                                            <span class="fw-bold text-success" id="montantTotal">0 FCFA</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="text-muted small">Coopérative</span>
                                            <span class="fw-bold text-dark" id="cooperativeSelected">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Boutons d'Action -->
            <div class="row mt-24">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.factures.index') }}" class="btn btn-outline-secondary">
                            <iconify-icon icon="lucide:x" class="icon me-1"></iconify-icon>
                            Annuler
                        </a>
                        @if(count($ticketsAvecPrix) > 0)
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <iconify-icon icon="lucide:save" class="icon me-1"></iconify-icon>
                                Créer la Facture
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

@include('partials.wowdash-scripts')

<script>
let selectedTickets = new Set();
let ticketsData = {};

// Initialiser les données des tickets
@foreach($ticketsAvecPrix as $item)
    @php
        $ticket = $item['ticket'];
        $prix = $item['prix'];
    @endphp
    ticketsData[{{ $ticket->id }}] = {
        poids: {{ $ticket->poids_net }},
        montant: {{ $prix ? $prix['details']['montant_public'] : 0 }},
        cooperative: '{{ $ticket->connaissement->cooperative->nom }}'
    };
@endforeach

// Gérer la sélection des tickets
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.ticket-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    const submitBtn = document.getElementById('submitBtn');
    
    checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const ticketId = parseInt(this.value);
        
        if (this.checked) {
            selectedTickets.add(ticketId);
        } else {
            selectedTickets.delete(ticketId);
        }
        
        updateSummary();
        updateSubmitButton();
    });
});

    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            const ticketId = parseInt(checkbox.value);
            
            if (this.checked) {
                selectedTickets.add(ticketId);
            } else {
                selectedTickets.delete(ticketId);
            }
    });
    
    updateSummary();
    updateSubmitButton();
    });
    
    function updateSummary() {
        let poidsTotal = 0;
        let montantTotal = 0;
        let cooperatives = new Set();
        
        selectedTickets.forEach(ticketId => {
            const data = ticketsData[ticketId];
            if (data) {
                poidsTotal += data.poids;
                montantTotal += data.montant;
                cooperatives.add(data.cooperative);
            }
        });
        
        document.getElementById('ticketsCount').textContent = selectedTickets.size;
        document.getElementById('poidsTotal').textContent = poidsTotal.toFixed(2) + ' kg';
        document.getElementById('montantTotal').textContent = montantTotal.toLocaleString() + ' FCFA';
        document.getElementById('cooperativeSelected').textContent = cooperatives.size === 1 ? Array.from(cooperatives)[0] : 'Multiple';
    }
    
    function updateSubmitButton() {
        submitBtn.disabled = selectedTickets.size === 0;
    }
});

function selectAllTickets() {
    document.getElementById('selectAll').checked = true;
    document.getElementById('selectAll').dispatchEvent(new Event('change'));
        }

function deselectAllTickets() {
    document.getElementById('selectAll').checked = false;
    document.getElementById('selectAll').dispatchEvent(new Event('change'));
    }
</script>

</body>
</html> 