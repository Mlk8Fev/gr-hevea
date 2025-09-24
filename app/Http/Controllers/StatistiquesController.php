<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketPesee;
use App\Models\Cooperative;
use App\Models\Connaissement;
use App\Models\Facture;
use App\Models\Producteur;
use App\Models\CentreCollecte;
use App\Models\User;
use App\Models\Secteur;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatistiquesController extends Controller
{
    public function index()
    {
        // S'assurer que les dates sont des objets Carbon
        $dateDebut = request('date_debut') 
            ? Carbon::parse(request('date_debut'))
            : Carbon::now()->startOfMonth();
            
        $dateFin = request('date_fin')
            ? Carbon::parse(request('date_fin'))
            : Carbon::now()->endOfMonth();
        
        $stats = $this->getStatsGenerales($dateDebut, $dateFin);
        
        return view('admin.statistiques.index', compact('stats', 'dateDebut', 'dateFin'));
    }

    public function avancees()
    {
        // S'assurer que les dates sont des objets Carbon
        $dateDebut = request('date_debut') 
            ? Carbon::parse(request('date_debut'))
            : Carbon::now()->startOfMonth();
            
        $dateFin = request('date_fin')
            ? Carbon::parse(request('date_fin'))
            : Carbon::now()->endOfMonth();
            
        $type = request('type', 'generales');
        
        $stats = $this->getStatsByType($type, $dateDebut, $dateFin);
        
        return view('admin.statistiques.avancees', compact('stats', 'dateDebut', 'dateFin', 'type'));
    }

    public function getStatsByType($type, $dateDebut, $dateFin)
    {
        switch ($type) {
            case 'generales':
                return $this->getStatsGenerales($dateDebut, $dateFin);
            case 'cooperatives':
                return $this->getStatsCooperatives($dateDebut, $dateFin);
            case 'logistiques':
                return $this->getStatsLogistiques($dateDebut, $dateFin);
            case 'financieres':
                return $this->getStatsFinancieres($dateDebut, $dateFin);
            case 'qualite':
                return $this->getStatsQualite($dateDebut, $dateFin);
            default:
                return $this->getStatsGenerales($dateDebut, $dateFin);
        }
    }

    private function getStatsGenerales($dateDebut, $dateFin)
    {
        $tickets = TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin]);
        $ticketsValides = $tickets->where('statut', 'valide');
        
        // Statistiques de base
        $totalGraines = $ticketsValides->sum('poids_net');
        $nombreTickets = $tickets->count();
        $ticketsValidesCount = $ticketsValides->count();
        $ticketsEnAttente = $tickets->where('statut', 'en_attente')->count();
        $moyennePoidsNet = $ticketsValides->avg('poids_net') ?? 0;
        $totalSacs = $ticketsValides->sum('nombre_sacs_bidons_cartons');
        
        // Statistiques avancées
        $nombreCooperatives = Cooperative::count();
        $nombreCentres = CentreCollecte::count();
        $nombreConnaissements = Connaissement::whereBetween('created_at', [$dateDebut, $dateFin])->count();
        $nombreProducteurs = Producteur::count();
        $nombreSecteurs = Secteur::count();
        
        // Calculs de performance
        $tauxValidation = $nombreTickets > 0 ? ($ticketsValidesCount / $nombreTickets) * 100 : 0;
        $poidsMoyenParTicket = $ticketsValidesCount > 0 ? $totalGraines / $ticketsValidesCount : 0;
        
        // Évolution par rapport au mois précédent
        $moisPrecedent = $dateDebut->copy()->subMonth();
        $ticketsPrecedent = TicketPesee::whereBetween('date_entree', [
            $moisPrecedent->startOfMonth(), 
            $moisPrecedent->endOfMonth()
        ])->where('statut', 'valide');
        
        $totalGrainesPrecedent = $ticketsPrecedent->sum('poids_net');
        $evolutionProduction = $totalGrainesPrecedent > 0 
            ? (($totalGraines - $totalGrainesPrecedent) / $totalGrainesPrecedent) * 100 
            : 0;
        
        return [
            // KPIs principaux
            'total_graines' => $totalGraines,
            'nombre_tickets' => $nombreTickets,
            'tickets_valides' => $ticketsValidesCount,
            'tickets_en_attente' => $ticketsEnAttente,
            'moyenne_poids_net' => $moyennePoidsNet,
            'total_sacs' => $totalSacs,
            'taux_validation' => $tauxValidation,
            'poids_moyen_par_ticket' => $poidsMoyenParTicket,
            'evolution_production' => $evolutionProduction,
            
            // Entités
            'nombre_cooperatives' => $nombreCooperatives,
            'nombre_centres' => $nombreCentres,
            'nombre_connaissements' => $nombreConnaissements,
            'nombre_producteurs' => $nombreProducteurs,
            'nombre_secteurs' => $nombreSecteurs,
            
            // Données pour graphiques
            'evolution_mensuelle' => $this->getEvolutionMensuelle($dateDebut, $dateFin),
            'top_cooperatives' => $this->getTopCooperatives($dateDebut, $dateFin, 5),
            'repartition_secteurs' => $this->getRepartitionSecteurs($dateDebut, $dateFin),
            'evolution_quotidienne' => $this->getEvolutionQuotidienne($dateDebut, $dateFin),
            'repartition_par_centre' => $this->getRepartitionParCentre($dateDebut, $dateFin),
            'performance_par_secteur' => $this->getPerformanceParSecteur($dateDebut, $dateFin),
            
            // Métriques de qualité
            'tickets_annules' => $tickets->where('statut', 'annule')->count(),
            'poids_max_ticket' => $ticketsValides->max('poids_net') ?? 0,
            'poids_min_ticket' => $ticketsValides->min('poids_net') ?? 0,
        ];
    }

    private function getStatsCooperatives($dateDebut, $dateFin)
    {
        return [
            'total_cooperatives' => Cooperative::count(),
            'cooperatives_actives' => Cooperative::whereHas('connaissements.ticketsPesee', function($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_entree', [$dateDebut, $dateFin]);
            })->count(),
            'top_cooperatives' => $this->getTopCooperatives($dateDebut, $dateFin, 10),
            'repartition_par_secteur' => $this->getPerformanceParSecteur($dateDebut, $dateFin)
        ];
    }

    private function getStatsLogistiques($dateDebut, $dateFin)
    {
        $connaissements = Connaissement::whereBetween('created_at', [$dateDebut, $dateFin]);

        return [
            'total_connaissements' => $connaissements->count(),
            'connaissements_valides' => $connaissements->where('statut', 'valide')->count(),
            'connaissements_en_attente' => $connaissements->where('statut', 'en_attente')->count(),
            'centres_actifs' => $this->getCentresActifs($dateDebut, $dateFin),
            'transporteurs_actifs' => $this->getTransporteursActifs($dateDebut, $dateFin),
            'routes_populaires' => $this->getRoutesPopulaires($dateDebut, $dateFin),
            'temps_moyen_traitement' => $this->getTempsMoyenTraitement($dateDebut, $dateFin)
        ];
    }

    private function getStatsFinancieres($dateDebut, $dateFin)
    {
        $factures = Facture::whereBetween('date_emission', [$dateDebut, $dateFin]);

        return [
            'montant_total_factures' => $factures->sum('montant_ttc'),
            'montant_paye' => $factures->sum('montant_paye'),
            'montant_en_attente' => $factures->sum('montant_ttc') - $factures->sum('montant_paye'),
            'nombre_factures' => $factures->count(),
            'factures_payees' => $factures->where('statut', 'payee')->count(),
            'factures_en_attente' => $factures->where('statut', 'en_attente')->count(),
            'taux_recouvrement' => $this->calculerTauxRecouvrement($factures),
            'evolution_revenus' => $this->getEvolutionRevenus($dateDebut, $dateFin)
        ];
    }

    private function getStatsQualite($dateDebut, $dateFin)
    {
        $tickets = TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin]);

        return [
            'taux_humidite_moyen' => $tickets->avg('taux_humidite'),
            'taux_impuretes_moyen' => $tickets->avg('taux_impuretes'),
            'gp_moyen' => $tickets->avg('gp'),
            'ga_moyen' => $tickets->avg('ga'),
            'me_moyen' => $tickets->avg('me'),
            'poids_100_graines_moyen' => $tickets->avg('poids_100_graines'),
            'distribution_qualite' => $this->getDistributionQualite($dateDebut, $dateFin),
            'qualite_par_cooperative' => $this->getQualiteParCooperative($dateDebut, $dateFin)
        ];
    }

    // Méthodes utilitaires
    private function getEvolutionMensuelle($dateDebut, $dateFin)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->selectRaw('DATE_FORMAT(date_entree, "%Y-%m") as mois, SUM(poids_net) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->map(function($item) {
                return [
                    'mois' => $item->mois,
                    'total' => (float) $item->total
                ];
            });
    }

    private function getTopCooperatives($dateDebut, $dateFin, $limit = 5)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->with(['connaissement.cooperative.secteur'])
            ->get()
            ->groupBy('connaissement.cooperative.nom')
            ->map(function($tickets, $nomCoop) {
                $coop = $tickets->first()->connaissement->cooperative;
                return [
                    'nom' => $nomCoop,
                    'secteur' => $coop->secteur->nom ?? 'Non défini',
                    'total' => $tickets->sum('poids_net'),
                    'tickets' => $tickets->count()
                ];
            })
            ->values()
            ->sortByDesc('total')
            ->take($limit)
            ->toArray();
    }
    
    private function getRepartitionSecteurs($dateDebut, $dateFin)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->with('connaissement.cooperative.secteur')
            ->get()
            ->groupBy('connaissement.cooperative.secteur.nom')
            ->map(function($tickets, $secteur) {
                return [
                    'secteur' => $secteur ?? 'Non défini',
                    'nom' => $secteur ?? 'Non défini', // Ajout de la clé 'nom'
                    'total' => $tickets->sum('poids_net')
                ];
            })
            ->values()
            ->sortByDesc('total')
            ->toArray();
    }

    private function calculerPerformance($tickets)
    {
        if ($tickets->count() == 0) return 0;
        
        $tauxQualite = $tickets->avg('gp') ?? 0;
        $tauxHumidite = $tickets->avg('taux_humidite') ?? 0;
        
        return round(($tauxQualite * 0.7) + ((100 - $tauxHumidite) * 0.3), 2);
    }

    private function getRepartitionGeographique($cooperatives)
    {
        return $cooperatives->groupBy('secteur.nom')->map(function($coops) {
            return $coops->count();
        });
    }

    private function getCentresActifs($dateDebut, $dateFin)
    {
        return CentreCollecte::withCount(['connaissements' => function($query) use ($dateDebut, $dateFin) {
            $query->whereBetween('created_at', [$dateDebut, $dateFin]);
        }])
        ->having('connaissements_count', '>', 0)
        ->orderByDesc('connaissements_count')
        ->get();
    }

    private function getTransporteursActifs($dateDebut, $dateFin)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->select('transporteur', DB::raw('COUNT(*) as nombre_livraisons'))
            ->groupBy('transporteur')
            ->orderByDesc('nombre_livraisons')
            ->get();
    }

    private function getRoutesPopulaires($dateDebut, $dateFin)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->select('origine', 'destination', DB::raw('COUNT(*) as nombre_livraisons'))
            ->groupBy('origine', 'destination')
            ->orderByDesc('nombre_livraisons')
            ->get();
    }

    private function getTempsMoyenTraitement($dateDebut, $dateFin)
    {
        $tickets = TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->whereNotNull('date_validation')
            ->get();

        if ($tickets->count() == 0) return 0;

        $tempsTotal = $tickets->sum(function($ticket) {
            return $ticket->date_validation->diffInHours($ticket->date_entree);
        });

        return round($tempsTotal / $tickets->count(), 2);
    }

    private function calculerTauxRecouvrement($factures)
    {
        $montantTotal = $factures->sum('montant_ttc');
        $montantPaye = $factures->sum('montant_paye');
        
        return $montantTotal > 0 ? round(($montantPaye / $montantTotal) * 100, 2) : 0;
    }

    private function getEvolutionRevenus($dateDebut, $dateFin)
    {
        return Facture::selectRaw('DATE_FORMAT(date_emission, "%Y-%m") as mois, SUM(montant_ttc) as total')
            ->whereBetween('date_emission', [$dateDebut, $dateFin])
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();
    }

    private function getDistributionQualite($dateDebut, $dateFin)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->selectRaw('
                CASE 
                    WHEN gp >= 12 THEN "Excellente"
                    WHEN gp >= 10 THEN "Bonne"
                    WHEN gp >= 8 THEN "Moyenne"
                    ELSE "Faible"
                END as qualite,
                COUNT(*) as nombre
            ')
            ->groupBy('qualite')
            ->get();
    }

    private function getQualiteParCooperative($dateDebut, $dateFin)
    {
        return Cooperative::with(['ticketsPesee' => function($query) use ($dateDebut, $dateFin) {
            $query->whereBetween('date_entree', [$dateDebut, $dateFin]);
        }])
        ->get()
        ->map(function($coop) {
            $tickets = $coop->ticketsPesee;
            return [
                'nom' => $coop->nom,
                'gp_moyen' => $tickets->avg('gp'),
                'taux_humidite_moyen' => $tickets->avg('taux_humidite'),
                'taux_impuretes_moyen' => $tickets->avg('taux_impuretes')
            ];
        })
        ->sortByDesc('gp_moyen')
        ->values();
    }

    private function getEvolutionQuotidienne($dateDebut, $dateFin)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->selectRaw('DATE(date_entree) as date, SUM(poids_net) as total, COUNT(*) as tickets')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'total' => (float) $item->total,
                    'tickets' => (int) $item->tickets
                ];
            });
    }
    
    private function getRepartitionParCentre($dateDebut, $dateFin)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->with('connaissement.centreCollecte')
            ->get()
            ->groupBy('connaissement.centreCollecte.nom')
            ->map(function($tickets, $centre) {
                return [
                    'centre' => $centre,
                    'total' => $tickets->sum('poids_net'),
                    'tickets' => $tickets->count()
                ];
            })
            ->values()
            ->sortByDesc('total')
            ->take(10);
    }
    
    private function getPerformanceParSecteur($dateDebut, $dateFin)
    {
        return TicketPesee::whereBetween('date_entree', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->with('connaissement.cooperative.secteur')
            ->get()
            ->groupBy('connaissement.cooperative.secteur.nom')
            ->map(function($tickets, $secteur) {
                return [
                    'secteur' => $secteur,
                    'total' => $tickets->sum('poids_net'),
                    'tickets' => $tickets->count(),
                    'moyenne' => $tickets->avg('poids_net')
                ];
            })
            ->values()
            ->sortByDesc('total');
    }
}
