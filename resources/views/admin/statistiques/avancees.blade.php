@extends('layouts.app')

@section('title', 'Statistiques Avancées')

@section('content')
<div class="dashboard-main-body">
    <!-- Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Statistiques Avancées</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="ri-home-line icon text-lg"></i>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.statistiques.index') }}" class="hover-text-primary">
                    Statistiques
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Avancées</li>
        </ul>
    </div>

            <!-- Filtres et Navigation -->
    <!-- Filtres et Navigation Moderne FPH-CI -->
    <div class="card radius-8 border-0 mb-32">
        <div class="card-body p-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
                <div>
                    <h6 class="fw-bold text-lg mb-0">Analyse Détaillée</h6>
                    <span class="text-sm fw-medium text-secondary-light">Filtrez et explorez vos données en profondeur</span>
                </div>
                <a href="{{ route('admin.statistiques.index') }}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                    <i class="ri-arrow-left-line icon"></i>
                    Retour aux Statistiques Basiques
                </a>
            </div>
            
            <form method="GET" action="{{ route('admin.statistiques.avancees') }}" class="row gy-3">
                <div class="col-xxl-3 col-md-6">
                    <label for="date_debut" class="form-label fw-semibold text-primary-light text-sm mb-8">Date de Début</label>
                    <input type="date" class="form-control radius-8 h-48-px" id="date_debut" name="date_debut" 
                           value="{{ $dateDebut->format('Y-m-d') }}">
                </div>
                <div class="col-xxl-3 col-md-6">
                    <label for="date_fin" class="form-label fw-semibold text-primary-light text-sm mb-8">Date de Fin</label>
                    <input type="date" class="form-control radius-8 h-48-px" id="date_fin" name="date_fin" 
                           value="{{ $dateFin->format('Y-m-d') }}">
                </div>
                <div class="col-xxl-4 col-md-8">
                    <label for="type" class="form-label fw-semibold text-primary-light text-sm mb-8">Type de Statistiques</label>
                    <select class="form-select radius-8 h-48-px" id="type" name="type">
                        <option value="generales" {{ $type == 'generales' ? 'selected' : '' }}>📊 Générales</option>
                        <option value="cooperatives" {{ $type == 'cooperatives' ? 'selected' : '' }}>🤝 Coopératives</option>
                        <option value="logistiques" {{ $type == 'logistiques' ? 'selected' : '' }}>🚚 Logistiques</option>
                        <option value="financieres" {{ $type == 'financieres' ? 'selected' : '' }}>💰 Financières</option>
                        <option value="qualite" {{ $type == 'qualite' ? 'selected' : '' }}>⭐ Qualité</option>
                    </select>
                </div>
                <div class="col-xxl-2 col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary h-48-px px-24 w-100 radius-8 d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-search-line icon"></i>
                        Analyser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Navigation par Catégories -->
    <div class="d-flex flex-wrap align-items-center justify-content-center gap-1 mb-32">
        <ul class="nav bordered-tab nav-pills mb-0" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link d-flex align-items-center {{ $type == 'generales' ? 'active' : '' }}" 
                   href="{{ route('admin.statistiques.avancees', ['type' => 'generales', 'date_debut' => $dateDebut->format('Y-m-d'), 'date_fin' => $dateFin->format('Y-m-d')]) }}">
                    <i class="ri-bar-chart-line me-6"></i>
                    Générales
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link d-flex align-items-center {{ $type == 'cooperatives' ? 'active' : '' }}" 
                   href="{{ route('admin.statistiques.avancees', ['type' => 'cooperatives', 'date_debut' => $dateDebut->format('Y-m-d'), 'date_fin' => $dateFin->format('Y-m-d')]) }}">
                    <i class="ri-building-line me-6"></i>
                    Coopératives
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link d-flex align-items-center {{ $type == 'logistiques' ? 'active' : '' }}" 
                   href="{{ route('admin.statistiques.avancees', ['type' => 'logistiques', 'date_debut' => $dateDebut->format('Y-m-d'), 'date_fin' => $dateFin->format('Y-m-d')]) }}">
                    <i class="ri-truck-line me-6"></i>
                    Logistiques
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link d-flex align-items-center {{ $type == 'financieres' ? 'active' : '' }}" 
                   href="{{ route('admin.statistiques.avancees', ['type' => 'financieres', 'date_debut' => $dateDebut->format('Y-m-d'), 'date_fin' => $dateFin->format('Y-m-d')]) }}">
                    <i class="ri-money-dollar-circle-line me-6"></i>
                    Financières
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link d-flex align-items-center {{ $type == 'qualite' ? 'active' : '' }}" 
                   href="{{ route('admin.statistiques.avancees', ['type' => 'qualite', 'date_debut' => $dateDebut->format('Y-m-d'), 'date_fin' => $dateFin->format('Y-m-d')]) }}">
                    <i class="ri-star-line me-6"></i>
                    Qualité
                </a>
            </li>
        </ul>
    </div>

            <!-- Contenu Dynamique selon le Type -->
            @if($type == 'generales')
                @include('admin.statistiques.partials.generales', ['stats' => $stats])
            @elseif($type == 'cooperatives')
                @include('admin.statistiques.partials.cooperatives', ['stats' => $stats])
            @elseif($type == 'logistiques')
                @include('admin.statistiques.partials.logistiques', ['stats' => $stats])
            @elseif($type == 'financieres')
                @include('admin.statistiques.partials.financieres', ['stats' => $stats])
            @elseif($type == 'qualite')
                @include('admin.statistiques.partials.qualite', ['stats' => $stats])
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Apex Chart js -->
<script src="{{ asset('wowdash/js/lib/apexcharts.min.js') }}"></script>
<script>
// Configuration FPH-CI pour les statistiques avancées
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation des statistiques avancées FPH-CI');
    
    // Données pour les graphiques
    const statsData = @json($stats ?? []);
    const statsType = '{{ $type }}';
    
    console.log('Type de statistiques:', statsType);
    console.log('Données:', statsData);

// Fonctions spécifiques aux statistiques avancées
function initGeneralCharts() {
    // Graphiques pour les statistiques générales
    const evolutionData = @json($stats['evolution_mensuelle'] ?? []);
    
    if (document.getElementById('generalEvolutionChart')) {
        const ctx = document.getElementById('generalEvolutionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: evolutionData.map(item => {
                    const [year, month] = item.mois.split('-');
                    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 
                                      'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                    return monthNames[parseInt(month) - 1] + ' ' + year;
                }),
                datasets: [{
                    label: 'Production (Kg)',
                    data: evolutionData.map(item => item.total),
                    borderColor: '#20c997',
                    backgroundColor: 'rgba(32, 201, 151, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' Kg';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Production: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' Kg';
                            }
                        }
                    }
                }
            }
        });
    }
}

function initCooperativesCharts() {
    // Graphiques pour les coopératives (déjà défini dans le partial)
    console.log('📊 Graphiques coopératives initialisés via partial');
}

function initLogisticsCharts() {
    // Graphiques pour la logistique (déjà défini dans le partial)
    console.log('🚚 Graphiques logistiques initialisés via partial');
}

function initFinancialCharts() {
    // Graphiques financiers (déjà défini dans le partial)
    console.log('💰 Graphiques financiers initialisés via partial');
}

function initQualityCharts() {
    // Graphiques de qualité (déjà défini dans le partial)
    console.log('⭐ Graphiques qualité initialisés via partial');
}

// Fonctions de tri pour les tableaux
function sortTable(criteria) {
    console.log('🔄 Tri du tableau par:', criteria);
    
    // Mise à jour de l'apparence des boutons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    if (event && event.target) {
        event.target.closest('.btn').classList.add('active');
    }
}

function sortQualityTable(criteria) {
    console.log('🔄 Tri du tableau qualité par:', criteria);
    
    // Mise à jour de l'apparence des boutons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    if (event && event.target) {
        event.target.closest('.btn').classList.add('active');
    }
}

// Exposer les fonctions globalement pour les vues partielles
window.sortTable = sortTable;
window.sortQualityTable = sortQualityTable;
window.initGeneralCharts = initGeneralCharts;
window.initCooperativesCharts = initCooperativesCharts;
window.initLogisticsCharts = initLogisticsCharts;
window.initFinancialCharts = initFinancialCharts;
window.initQualityCharts = initQualityCharts;

// Exposer les fonctions d'export
window.setQuickPeriod = function(period) {
    if (window.statistiques && window.statistiques.setQuickPeriod) {
        window.statistiques.setQuickPeriod(period);
    }
};

window.exportToExcel = function(button) {
    if (window.statistiques && window.statistiques.exportToExcel) {
        window.statistiques.exportToExcel(button);
    }
};

window.exportToPDF = function(button) {
    if (window.statistiques && window.statistiques.exportToPDF) {
        window.statistiques.exportToPDF(button);
    }
};

window.printChart = function(button) {
    if (window.statistiques && window.statistiques.printChart) {
        window.statistiques.printChart(button);
    }
};
</script>
@endpush

@push('styles')
<style>
.nav-pills .nav-link {
    border-radius: 0.5rem;
    margin: 0 2px;
    transition: all 0.2s ease-in-out;
}

.nav-pills .nav-link:hover {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.nav-pills .nav-link.active {
    background-color: #0d6efd;
    color: white;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
</style>
@endpush
