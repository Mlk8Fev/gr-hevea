/**
 * Statistiques Interactives - Gestion des Graines d'Hévéa
 * Fichier JavaScript pour les graphiques, filtres et interactions
 */

// Configuration globale des graphiques
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.responsive = true;
Chart.defaults.maintainAspectRatio = false;

// Variables globales
let currentCharts = {};
let statisticsData = {};
let currentFilters = {
    dateDebut: null,
    dateFin: null,
    type: 'generales'
};

/**
 * Initialisation principale
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeStatistics();
    setupFilters();
    setupInteractions();
    loadInitialData();
});

/**
 * Initialisation des statistiques
 */
function initializeStatistics() {
    console.log('🚀 Initialisation des statistiques...');
    
    // Récupérer les données initiales depuis les vues
    if (typeof window.statsData !== 'undefined') {
        statisticsData = window.statsData;
        currentFilters.type = window.statsType || 'generales';
    }
    
    // Initialiser selon le type de page
    const currentPage = getCurrentPage();
    switch(currentPage) {
        case 'basic':
            initBasicStatistics();
            break;
        case 'advanced':
            initAdvancedStatistics();
            break;
        default:
            console.log('Page de statistiques non reconnue');
    }
}

/**
 * Détecter la page actuelle
 */
function getCurrentPage() {
    const path = window.location.pathname;
    if (path.includes('statistiques/avancees')) {
        return 'advanced';
    } else if (path.includes('statistiques')) {
        return 'basic';
    }
    return 'unknown';
}

/**
 * Initialiser les statistiques basiques
 */
function initBasicStatistics() {
    console.log('📊 Initialisation des statistiques basiques');
    
    // Graphique d'évolution
    initEvolutionChart();
    
    // Animations des cartes
    animateStatsCards();
    
    // Barres de progression
    animateProgressBars();
}

/**
 * Initialiser les statistiques avancées
 */
function initAdvancedStatistics() {
    console.log('📈 Initialisation des statistiques avancées');
    
    const statsType = currentFilters.type;
    
    switch(statsType) {
        case 'generales':
            initGeneralCharts();
            break;
        case 'cooperatives':
            initCooperativesCharts();
            break;
        case 'logistiques':
            initLogisticsCharts();
            break;
        case 'financieres':
            initFinancialCharts();
            break;
        case 'qualite':
            initQualityCharts();
            break;
    }
    
    // Initialiser les tooltips
    initTooltips();
}

/**
 * Configuration des filtres
 */
function setupFilters() {
    console.log('🔍 Configuration des filtres...');
    
    // Filtres de date
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', handleDateChange);
    });
    
    // Filtre de type (statistiques avancées)
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        typeSelect.addEventListener('change', handleTypeChange);
    }
    
    // Boutons de période rapide
    setupQuickPeriods();
}

/**
 * Boutons de période rapide
 */
function setupQuickPeriods() {
    // Créer des boutons de période si ils n'existent pas
    const filterCard = document.querySelector('.card-body form');
    if (filterCard && !document.querySelector('.quick-periods')) {
        const quickPeriodsHtml = `
            <div class="quick-periods mt-3">
                <label class="form-label">Périodes rapides :</label>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickPeriod('today')">
                        <i class="ri-calendar-line"></i> Aujourd'hui
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickPeriod('week')">
                        <i class="ri-calendar-week-line"></i> Cette semaine
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickPeriod('month')">
                        <i class="ri-calendar-month-line"></i> Ce mois
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickPeriod('quarter')">
                        <i class="ri-calendar-2-line"></i> Ce trimestre
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickPeriod('year')">
                        <i class="ri-calendar-year-line"></i> Cette année
                    </button>
                </div>
            </div>
        `;
        filterCard.insertAdjacentHTML('beforeend', quickPeriodsHtml);
    }
}

/**
 * Définir une période rapide
 */
function setQuickPeriod(period) {
    const today = new Date();
    let startDate, endDate;
    
    switch(period) {
        case 'today':
            startDate = endDate = today;
            break;
        case 'week':
            startDate = new Date(today.setDate(today.getDate() - today.getDay()));
            endDate = new Date(today.setDate(today.getDate() - today.getDay() + 6));
            break;
        case 'month':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case 'quarter':
            const quarter = Math.floor(today.getMonth() / 3);
            startDate = new Date(today.getFullYear(), quarter * 3, 1);
            endDate = new Date(today.getFullYear(), quarter * 3 + 3, 0);
            break;
        case 'year':
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = new Date(today.getFullYear(), 11, 31);
            break;
    }
    
    // Mettre à jour les champs de date
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    
    if (dateDebutInput) dateDebutInput.value = formatDate(startDate);
    if (dateFinInput) dateFinInput.value = formatDate(endDate);
    
    // Mettre en surbrillance le bouton sélectionné
    document.querySelectorAll('.quick-periods .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Déclencher la mise à jour des données
    handleDateChange();
}

/**
 * Formater une date en YYYY-MM-DD
 */
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

/**
 * Gestionnaire de changement de date
 */
function handleDateChange() {
    const dateDebut = document.getElementById('date_debut')?.value;
    const dateFin = document.getElementById('date_fin')?.value;
    
    if (dateDebut && dateFin) {
        currentFilters.dateDebut = dateDebut;
        currentFilters.dateFin = dateFin;
        
        // Afficher un loader
        showLoader();
        
        // Simuler le rechargement des données
        setTimeout(() => {
            updateStatistics();
            hideLoader();
            showSuccessMessage('Données mises à jour avec succès !');
        }, 1000);
    }
}

/**
 * Gestionnaire de changement de type
 */
function handleTypeChange() {
    const type = document.getElementById('type')?.value;
    if (type) {
        currentFilters.type = type;
        
        // Redirection vers le bon onglet
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('type', type);
        window.location.href = currentUrl.toString();
    }
}

/**
 * Interactions utilisateur
 */
function setupInteractions() {
    // Tri des tableaux
    setupTableSorting();
    
    // Zoom sur les graphiques
    setupChartZoom();
    
    // Export des données
    setupExportButtons();
    
    // Recherche dans les tableaux
    setupTableSearch();
}

/**
 * Tri des tableaux
 */
function setupTableSorting() {
    const tables = document.querySelectorAll('table[data-sortable="true"], .table-responsive table');
    
    tables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                sortTable(table, this.dataset.sort);
            });
        });
    });
}

/**
 * Trier un tableau
 */
function sortTable(table, column) {
    console.log(`🔄 Tri du tableau par: ${column}`);
    
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Déterminer le type de tri
    const isNumeric = rows.some(row => {
        const cell = row.querySelector(`td[data-${column}]`);
        return cell && !isNaN(parseFloat(cell.textContent));
    });
    
    // Trier les lignes
    rows.sort((a, b) => {
        const aVal = a.querySelector(`td[data-${column}]`)?.textContent || '';
        const bVal = b.querySelector(`td[data-${column}]`)?.textContent || '';
        
        if (isNumeric) {
            return parseFloat(bVal) - parseFloat(aVal);
        } else {
            return aVal.localeCompare(bVal);
        }
    });
    
    // Réinsérer les lignes triées
    rows.forEach(row => tbody.appendChild(row));
    
    // Animation
    tbody.style.opacity = '0.5';
    setTimeout(() => {
        tbody.style.opacity = '1';
    }, 200);
}

/**
 * Recherche dans les tableaux
 */
function setupTableSearch() {
    const searchInputs = document.querySelectorAll('input[data-table-search]');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const tableId = this.dataset.tableSearch;
            const table = document.getElementById(tableId);
            const filter = this.value.toLowerCase();
            
            if (table) {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            }
        });
    });
}

/**
 * Export des données
 */
function setupExportButtons() {
    // Ajouter des boutons d'export si ils n'existent pas
    const cardHeaders = document.querySelectorAll('.card-header');
    
    cardHeaders.forEach(header => {
        if (!header.querySelector('.export-buttons')) {
            const exportHtml = `
                <div class="export-buttons ms-auto">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-success" onclick="exportToExcel(this)" data-bs-toggle="tooltip" title="Exporter en Excel">
                            <i class="ri-file-excel-line"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="exportToPDF(this)" data-bs-toggle="tooltip" title="Exporter en PDF">
                            <i class="ri-file-pdf-line"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="printChart(this)" data-bs-toggle="tooltip" title="Imprimer">
                            <i class="ri-printer-line"></i>
                        </button>
                    </div>
                </div>
            `;
            
            const titleElement = header.querySelector('.card-title');
            if (titleElement && !header.querySelector('.export-buttons')) {
                header.style.display = 'flex';
                header.style.alignItems = 'center';
                header.insertAdjacentHTML('beforeend', exportHtml);
            }
        }
    });
}

/**
 * Graphiques interactifs
 */

/**
 * Graphique d'évolution (page basique)
 */
function initEvolutionChart() {
    const canvas = document.getElementById('evolutionChart');
    if (!canvas) return;
    
    // Données d'exemple si pas de données réelles
    const defaultData = [
        { mois: '2024-01', total: 15000 },
        { mois: '2024-02', total: 18000 },
        { mois: '2024-03', total: 22000 },
        { mois: '2024-04', total: 19000 },
        { mois: '2024-05', total: 25000 }
    ];
    
    const data = window.evolutionData || defaultData;
    
    const ctx = canvas.getContext('2d');
    currentCharts.evolution = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => formatMonth(item.mois)),
            datasets: [{
                label: 'Production (Kg)',
                data: data.map(item => item.total),
                borderColor: '#20c997',
                backgroundColor: 'rgba(32, 201, 151, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#20c997',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    callbacks: {
                        label: function(context) {
                            return 'Production: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' Kg';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR').format(value) + ' Kg';
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
}

/**
 * Animations
 */

/**
 * Animer les cartes de statistiques
 */
function animateStatsCards() {
    const cards = document.querySelectorAll('.card-stats, .card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

/**
 * Animer les barres de progression
 */
function animateProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach((bar, index) => {
        const width = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.transition = 'width 1.5s ease-out';
            bar.style.width = width;
        }, 500 + index * 100);
    });
}

/**
 * Utilitaires
 */

/**
 * Formater un mois
 */
function formatMonth(monthString) {
    const [year, month] = monthString.split('-');
    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 
                       'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    return monthNames[parseInt(month) - 1] + ' ' + year;
}

/**
 * Initialiser les tooltips
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Afficher un loader
 */
function showLoader() {
    if (!document.getElementById('statisticsLoader')) {
        const loader = `
            <div id="statisticsLoader" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(255,255,255,0.8); z-index: 9999;">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <div class="mt-2">Mise à jour des statistiques...</div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', loader);
    }
}

/**
 * Masquer le loader
 */
function hideLoader() {
    const loader = document.getElementById('statisticsLoader');
    if (loader) {
        loader.remove();
    }
}

/**
 * Afficher un message de succès
 */
function showSuccessMessage(message) {
    const alert = `
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 10000;" role="alert">
            <i class="ri-check-circle-line me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', alert);
    
    // Auto-remove après 3 secondes
    setTimeout(() => {
        const alertElement = document.querySelector('.alert');
        if (alertElement) {
            alertElement.remove();
        }
    }, 3000);
}

/**
 * Mettre à jour les statistiques
 */
function updateStatistics() {
    console.log('🔄 Mise à jour des statistiques...');
    
    // Ici vous pouvez faire un appel AJAX pour récupérer de nouvelles données
    // et mettre à jour les graphiques
    
    // Exemple de mise à jour des graphiques existants
    Object.keys(currentCharts).forEach(key => {
        if (currentCharts[key]) {
            // Simuler de nouvelles données
            updateChartData(currentCharts[key]);
        }
    });
}

/**
 * Mettre à jour les données d'un graphique
 */
function updateChartData(chart) {
    // Simuler de nouvelles données
    chart.data.datasets[0].data = chart.data.datasets[0].data.map(value => 
        Math.floor(value * (0.8 + Math.random() * 0.4))
    );
    chart.update();
}

/**
 * Charger les données initiales
 */
function loadInitialData() {
    console.log('📥 Chargement des données initiales...');
    // Ici vous pouvez charger des données additionnelles si nécessaire
}

/**
 * Fonctions d'export
 */
function exportToExcel(button) {
    console.log('📊 Export Excel...');
    showSuccessMessage('Export Excel en cours...');
}

function exportToPDF(button) {
    console.log('📄 Export PDF...');
    showSuccessMessage('Export PDF en cours...');
}

function printChart(button) {
    console.log('🖨️ Impression...');
    window.print();
}

/**
 * Fonctions globales exposées
 */
window.statistiques = {
    setQuickPeriod,
    exportToExcel,
    exportToPDF,
    printChart,
    updateStatistics,
    showLoader,
    hideLoader
};
