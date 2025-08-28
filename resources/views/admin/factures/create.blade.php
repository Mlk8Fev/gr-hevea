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
                                                    <span class="fw-medium">{{ $ticket->numero_ticket }}</span>
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
        cooperativeName: "{{ $ticket->connaissement->cooperative->nom }}"
    };
@endforeach

// Gérer la sélection des tickets
document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
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

// Mettre à jour le résumé
function updateSummary() {
    let count = 0;
    let poidsTotal = 0;
    let montantTotal = 0;
    let cooperativeName = '-';
    
    if (selectedTickets.size > 0) {
        const firstTicketId = Array.from(selectedTickets)[0];
        cooperativeName = ticketsData[firstTicketId].cooperativeName;
    }
    
    selectedTickets.forEach(ticketId => {
        const ticketData = ticketsData[ticketId];
        count++;
        poidsTotal += ticketData.poids;
        montantTotal += ticketData.montant;
    });
    
    document.getElementById('ticketsCount').textContent = count;
    document.getElementById('poidsTotal').textContent = poidsTotal.toLocaleString() + ' kg';
    document.getElementById('montantTotal').textContent = montantTotal.toLocaleString() + ' FCFA';
    document.getElementById('cooperativeSelected').textContent = cooperativeName;
}

// Mettre à jour le bouton de soumission
function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = selectedTickets.size === 0;
    }
}

// Sélectionner tous les tickets
function selectAllTickets() {
    document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
        checkbox.checked = true;
        selectedTickets.add(parseInt(checkbox.value));
    });
    
    updateSummary();
    updateSubmitButton();
}

// Désélectionner tous les tickets
function deselectAllTickets() {
    document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    selectedTickets.clear();
    updateSummary();
    updateSubmitButton();
}

// Gérer la case "Tout sélectionner"
document.getElementById('selectAll').addEventListener('change', function() {
    if (this.checked) {
        selectAllTickets();
    } else {
        deselectAllTickets();
    }
});

// Validation du formulaire
document.getElementById('createFactureForm').addEventListener('submit', function(e) {
    if (selectedTickets.size === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un ticket.');
        return false;
    }
    
    // Vérifier que tous les tickets sélectionnés appartiennent à la même coopérative
    let sameCooperative = true;
    let firstCooperative = null;
    
    selectedTickets.forEach(ticketId => {
        const ticketData = ticketsData[ticketId];
        if (firstCooperative === null) {
            firstCooperative = ticketData.cooperativeName;
        } else if (ticketData.cooperativeName !== firstCooperative) {
            sameCooperative = false;
        }
    });
    
    if (!sameCooperative) {
        e.preventDefault();
        alert('Tous les tickets sélectionnés doivent appartenir à la même coopérative.');
        return false;
    }
});
</script>

</body>
</html> 