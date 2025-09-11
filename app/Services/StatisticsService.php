<?php

namespace App\Services;

use App\Models\TicketPesee;
use App\Models\Cooperative;
use App\Models\Connaissement;
use App\Models\Facture;
use App\Models\Producteur;
use App\Models\CentreCollecte;
use App\Models\Secteur;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    /**
     * Statistiques basiques pour la page d'accueil des statistiques
     */
    public function getBasicStatistics($dateFrom, $dateTo)
    {
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        return [
            'production' => [
                'total_kg' => TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
                    ->where('statut', 'valide')
                    ->sum('poids_net'),
                'total_tickets' => TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])->count(),
                'tickets_valides' => TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
                    ->where('statut', 'valide')->count(),
                'tickets_en_attente' => TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
                    ->where('statut', 'brouillon')->count(),
            ],
            'cooperatives' => [
                'total' => Cooperative::count(),
                'actives' => Cooperative::whereHas('ticketsPesee', function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('date_entree', [$dateFrom, $dateTo]);
                })->count(),
            ],
            'factures' => [
                'total_montant' => Facture::whereBetween('date_emission', [$dateFrom, $dateTo])
                    ->sum('montant_ttc'),
                'total_factures' => Facture::whereBetween('date_emission', [$dateFrom, $dateTo])->count(),
                'factures_payees' => Facture::whereBetween('date_emission', [$dateFrom, $dateTo])
                    ->where('statut', 'payee')->count(),
            ],
            'qualite' => [
                'taux_humidite_moyen' => TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
                    ->where('statut', 'valide')
                    ->avg('taux_humidite'),
                'taux_impuretes_moyen' => TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
                    ->where('statut', 'valide')
                    ->avg('taux_impuretes'),
            ]
        ];
    }

    /**
     * Statistiques générales (onglet Général)
     */
    public function getGeneralStatistics($dateFrom, $dateTo)
    {
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        return [
            'evolution_production' => $this->getProductionEvolution($dateFrom, $dateTo),
            'repartition_secteurs' => $this->getSecteursRepartition($dateFrom, $dateTo),
            'top_cooperatives' => $this->getTopCooperatives($dateFrom, $dateTo, 10),
            'statistiques_globales' => $this->getBasicStatistics($dateFrom, $dateTo),
        ];
    }

    /**
     * Statistiques des coopératives (onglet Coopératives)
     */
    public function getCooperativeStatistics($dateFrom, $dateTo)
    {
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        return [
            'performance_cooperatives' => $this->getCooperativePerformance($dateFrom, $dateTo),
            'repartition_geographique' => $this->getGeographicDistribution($dateFrom, $dateTo),
            'cooperatives_sechoir' => $this->getCooperativesWithSechoir($dateFrom, $dateTo),
            'evolution_cooperatives' => $this->getCooperativeEvolution($dateFrom, $dateTo),
        ];
    }

    /**
     * Statistiques logistiques (onglet Logistique)
     */
    public function getLogisticsStatistics($dateFrom, $dateTo)
    {
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        return [
            'centres_collecte' => $this->getCentreCollecteStats($dateFrom, $dateTo),
            'transporteurs' => $this->getTransporteurStats($dateFrom, $dateTo),
            'routes_utilisees' => $this->getRoutesStats($dateFrom, $dateTo),
            'efficacite_logistique' => $this->getLogisticsEfficiency($dateFrom, $dateTo),
        ];
    }

    /**
     * Statistiques financières (onglet Financier)
     */
    public function getFinancialStatistics($dateFrom, $dateTo)
    {
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        return [
            'revenus' => $this->getRevenueStats($dateFrom, $dateTo),
            'factures_statut' => $this->getFactureStatusStats($dateFrom, $dateTo),
            'evolution_prix' => $this->getPriceEvolution($dateFrom, $dateTo),
            'taux_recouvrement' => $this->getRecoveryRate($dateFrom, $dateTo),
        ];
    }

    /**
     * Statistiques de qualité (onglet Qualité)
     */
    public function getQualityStatistics($dateFrom, $dateTo)
    {
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        return [
            'analyse_humidite' => $this->getHumidityAnalysis($dateFrom, $dateTo),
            'analyse_impuretes' => $this->getImpuritiesAnalysis($dateFrom, $dateTo),
            'distribution_gp_ga_me' => $this->getGPGAMEDistribution($dateFrom, $dateTo),
            'qualite_par_cooperative' => $this->getQualityByCooperative($dateFrom, $dateTo),
        ];
    }

    /**
     * Données pour les graphiques
     */
    public function getChartData($type, $dateFrom, $dateTo)
    {
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        switch ($type) {
            case 'production':
                return $this->getProductionEvolution($dateFrom, $dateTo);
            case 'cooperatives':
                return $this->getTopCooperatives($dateFrom, $dateTo, 10);
            case 'secteurs':
                return $this->getSecteursRepartition($dateFrom, $dateTo);
            case 'qualite':
                return $this->getQualityTrends($dateFrom, $dateTo);
            default:
                return [];
        }
    }

    /**
     * Données pour l'export
     */
    public function getStatisticsForExport($tab, $dateFrom, $dateTo)
    {
        switch ($tab) {
            case 'general':
                return $this->getGeneralStatistics($dateFrom, $dateTo);
            case 'cooperatives':
                return $this->getCooperativeStatistics($dateFrom, $dateTo);
            case 'logistics':
                return $this->getLogisticsStatistics($dateFrom, $dateTo);
            case 'financial':
                return $this->getFinancialStatistics($dateFrom, $dateTo);
            case 'quality':
                return $this->getQualityStatistics($dateFrom, $dateTo);
            default:
                return $this->getBasicStatistics($dateFrom, $dateTo);
        }
    }

    // Méthodes privées pour les calculs spécifiques

    private function getProductionEvolution($dateFrom, $dateTo)
    {
        return TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
            ->where('statut', 'valide')
            ->selectRaw('DATE(date_entree) as date, SUM(poids_net) as total_kg, COUNT(*) as total_tickets')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getSecteursRepartition($dateFrom, $dateTo)
    {
        return TicketPesee::join('connaissements', 'tickets_pesee.connaissement_id', '=', 'connaissements.id')
            ->join('secteurs', 'connaissements.secteur_id', '=', 'secteurs.id')
            ->whereBetween('tickets_pesee.date_entree', [$dateFrom, $dateTo])
            ->where('tickets_pesee.statut', 'valide')
            ->selectRaw('secteurs.nom as secteur, SUM(tickets_pesee.poids_net) as total_kg, COUNT(*) as total_tickets')
            ->groupBy('secteurs.id', 'secteurs.nom')
            ->orderBy('total_kg', 'desc')
            ->get();
    }

    private function getTopCooperatives($dateFrom, $dateTo, $limit = 10)
    {
        return TicketPesee::join('connaissements', 'tickets_pesee.connaissement_id', '=', 'connaissements.id')
            ->join('cooperatives', 'connaissements.cooperative_id', '=', 'cooperatives.id')
            ->whereBetween('tickets_pesee.date_entree', [$dateFrom, $dateTo])
            ->where('tickets_pesee.statut', 'valide')
            ->selectRaw('cooperatives.nom as cooperative, SUM(tickets_pesee.poids_net) as total_kg, COUNT(*) as total_tickets')
            ->groupBy('cooperatives.id', 'cooperatives.nom')
            ->orderBy('total_kg', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getCooperativePerformance($dateFrom, $dateTo)
    {
        return Cooperative::withCount(['ticketsPesee' => function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('date_entree', [$dateFrom, $dateTo])->where('statut', 'valide');
        }])
        ->with(['ticketsPesee' => function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('date_entree', [$dateFrom, $dateTo])->where('statut', 'valide');
        }])
        ->get()
        ->map(function($coop) {
            $coop->total_kg = $coop->ticketsPesee->sum('poids_net');
            return $coop;
        })
        ->sortByDesc('total_kg')
        ->take(15);
    }

    private function getGeographicDistribution($dateFrom, $dateTo)
    {
        return Secteur::withCount(['cooperatives' => function($q) use ($dateFrom, $dateTo) {
            $q->whereHas('ticketsPesee', function($q2) use ($dateFrom, $dateTo) {
                $q2->whereBetween('date_entree', [$dateFrom, $dateTo])->where('statut', 'valide');
            });
        }])
        ->get();
    }

    private function getCooperativesWithSechoir($dateFrom, $dateTo)
    {
        $withSechoir = Cooperative::where('a_sechoir', true)
            ->whereHas('ticketsPesee', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date_entree', [$dateFrom, $dateTo])->where('statut', 'valide');
            })
            ->count();

        $withoutSechoir = Cooperative::where('a_sechoir', false)
            ->whereHas('ticketsPesee', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date_entree', [$dateFrom, $dateTo])->where('statut', 'valide');
            })
            ->count();

        return [
            'avec_sechoir' => $withSechoir,
            'sans_sechoir' => $withoutSechoir,
            'total' => $withSechoir + $withoutSechoir
        ];
    }

    private function getCooperativeEvolution($dateFrom, $dateTo)
    {
        return TicketPesee::join('connaissements', 'tickets_pesee.connaissement_id', '=', 'connaissements.id')
            ->join('cooperatives', 'connaissements.cooperative_id', '=', 'cooperatives.id')
            ->whereBetween('tickets_pesee.date_entree', [$dateFrom, $dateTo])
            ->where('tickets_pesee.statut', 'valide')
            ->selectRaw('DATE(tickets_pesee.date_entree) as date, cooperatives.nom as cooperative, SUM(tickets_pesee.poids_net) as total_kg')
            ->groupBy('date', 'cooperatives.id', 'cooperatives.nom')
            ->orderBy('date')
            ->get()
            ->groupBy('cooperative');
    }

    private function getCentreCollecteStats($dateFrom, $dateTo)
    {
        return CentreCollecte::withCount(['connaissements' => function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('date_validation', [$dateFrom, $dateTo]);
        }])
        ->with(['connaissements' => function($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('date_validation', [$dateFrom, $dateTo]);
        }])
        ->get()
        ->map(function($centre) {
            $centre->total_kg = $centre->connaissements->sum('poids_net');
            return $centre;
        })
        ->sortByDesc('total_kg');
    }

    private function getTransporteurStats($dateFrom, $dateTo)
    {
        return TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
            ->where('statut', 'valide')
            ->selectRaw('transporteur, COUNT(*) as total_livraisons, SUM(poids_net) as total_kg')
            ->groupBy('transporteur')
            ->orderBy('total_livraisons', 'desc')
            ->get();
    }

    private function getRoutesStats($dateFrom, $dateTo)
    {
        return TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
            ->where('statut', 'valide')
            ->selectRaw('CONCAT(origine, " → ", destination) as route, COUNT(*) as total_livraisons, SUM(poids_net) as total_kg')
            ->groupBy('origine', 'destination')
            ->orderBy('total_livraisons', 'desc')
            ->get();
    }

    private function getLogisticsEfficiency($dateFrom, $dateTo)
    {
        // Calcul de l'efficacité basé sur le temps de traitement moyen
        return TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
            ->where('statut', 'valide')
            ->selectRaw('
                AVG(TIMESTAMPDIFF(HOUR, CONCAT(date_entree, " ", heure_entree), CONCAT(date_sortie, " ", heure_sortie))) as temps_moyen_heures,
                COUNT(*) as total_tickets
            ')
            ->first();
    }

    private function getRevenueStats($dateFrom, $dateTo)
    {
        return [
            'total_revenus' => Facture::whereBetween('date_emission', [$dateFrom, $dateTo])->sum('montant_ttc'),
            'revenus_par_mois' => Facture::whereBetween('date_emission', [$dateFrom, $dateTo])
                ->selectRaw('MONTH(date_emission) as mois, SUM(montant_ttc) as total')
                ->groupBy('mois')
                ->orderBy('mois')
                ->get(),
            'moyenne_facture' => Facture::whereBetween('date_emission', [$dateFrom, $dateTo])->avg('montant_ttc'),
        ];
    }

    private function getFactureStatusStats($dateFrom, $dateTo)
    {
        return Facture::whereBetween('date_emission', [$dateFrom, $dateTo])
            ->selectRaw('statut, COUNT(*) as count, SUM(montant_ttc) as total_montant')
            ->groupBy('statut')
            ->get();
    }

    private function getPriceEvolution($dateFrom, $dateTo)
    {
        // Cette méthode nécessiterait une table de prix ou des calculs basés sur les factures
        return [];
    }

    private function getRecoveryRate($dateFrom, $dateTo)
    {
        $totalFactures = Facture::whereBetween('date_emission', [$dateFrom, $dateTo])->count();
        $facturesPayees = Facture::whereBetween('date_emission', [$dateFrom, $dateTo])
            ->where('statut', 'payee')->count();
        
        return $totalFactures > 0 ? round(($facturesPayees / $totalFactures) * 100, 2) : 0;
    }

    private function getHumidityAnalysis($dateFrom, $dateTo)
    {
        return TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
            ->where('statut', 'valide')
            ->whereNotNull('taux_humidite')
            ->selectRaw('
                AVG(taux_humidite) as moyenne,
                MIN(taux_humidite) as minimum,
                MAX(taux_humidite) as maximum,
                COUNT(*) as total_echantillons
            ')
            ->first();
    }

    private function getImpuritiesAnalysis($dateFrom, $dateTo)
    {
        return TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
            ->where('statut', 'valide')
            ->whereNotNull('taux_impuretes')
            ->selectRaw('
                AVG(taux_impuretes) as moyenne,
                MIN(taux_impuretes) as minimum,
                MAX(taux_impuretes) as maximum,
                COUNT(*) as total_echantillons
            ')
            ->first();
    }

    private function getGPGAMEDistribution($dateFrom, $dateTo)
    {
        return TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
            ->where('statut', 'valide')
            ->whereNotNull('gp')
            ->whereNotNull('ga')
            ->whereNotNull('me')
            ->selectRaw('
                AVG(gp) as gp_moyen,
                AVG(ga) as ga_moyen,
                AVG(me) as me_moyen,
                COUNT(*) as total_echantillons
            ')
            ->first();
    }

    private function getQualityByCooperative($dateFrom, $dateTo)
    {
        return TicketPesee::join('connaissements', 'tickets_pesee.connaissement_id', '=', 'connaissements.id')
            ->join('cooperatives', 'connaissements.cooperative_id', '=', 'cooperatives.id')
            ->whereBetween('tickets_pesee.date_entree', [$dateFrom, $dateTo])
            ->where('tickets_pesee.statut', 'valide')
            ->whereNotNull('tickets_pesee.taux_humidite')
            ->selectRaw('
                cooperatives.nom as cooperative,
                AVG(tickets_pesee.taux_humidite) as humidite_moyenne,
                AVG(tickets_pesee.taux_impuretes) as impuretes_moyennes,
                COUNT(*) as total_echantillons
            ')
            ->groupBy('cooperatives.id', 'cooperatives.nom')
            ->orderBy('humidite_moyenne')
            ->get();
    }

    private function getQualityTrends($dateFrom, $dateTo)
    {
        return TicketPesee::whereBetween('date_entree', [$dateFrom, $dateTo])
            ->where('statut', 'valide')
            ->whereNotNull('taux_humidite')
            ->selectRaw('
                DATE(date_entree) as date,
                AVG(taux_humidite) as humidite_moyenne,
                AVG(taux_impuretes) as impuretes_moyennes,
                COUNT(*) as total_echantillons
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
