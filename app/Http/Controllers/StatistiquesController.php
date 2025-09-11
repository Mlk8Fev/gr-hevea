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
        
        return [
            'total_graines' => $tickets->sum('poids_net'),
            'nombre_tickets' => $tickets->count(),
            'tickets_valides' => $tickets->where('statut', 'valide')->count(),
            'tickets_en_attente' => $tickets->where('statut', 'en_attente')->count(),
            'moyenne_poids_net' => $tickets->avg('poids_net'),
            'total_sacs' => $tickets->sum('nombre_sacs_bidons_cartons'),
            'nombre_cooperatives' => Cooperative::count(),
            'nombre_centres' => CentreCollecte::count(),
            'nombre_connaissements' => Connaissement::whereBetween('created_at', [$dateDebut, $dateFin])->count(),
            'evolution_mensuelle' => $this->getEvolutionMensuelle($dateDebut, $dateFin),
            'top_cooperatives' => $this->getTopCooperatives($dateDebut, $dateFin, 5),
            'repartition_secteurs' => $this->getRepartitionSecteurs($dateDebut, $dateFin)
        ];
    }

    private function getStatsCooperatives($dateDebut, $dateFin)
    {
        $cooperatives = Cooperative::with(['ticketsPesee' => function($query) use ($dateDebut, $dateFin) {
            $query->whereBetween('date_entree', [$dateDebut, $dateFin]);
        }])->get();

        $stats = [];
        foreach ($cooperatives as $coop) {
            $tickets = $coop->ticketsPesee;
            $stats[] = [
                'nom' => $coop->nom,
                'secteur' => $coop->secteur->nom ?? 'N/A',
                'total_graines' => $tickets->sum('poids_net'),
                'nombre_tickets' => $tickets->count(),
                'moyenne_poids' => $tickets->avg('poids_net'),
                'a_sechoir' => $coop->a_sechoir ? 'Oui' : 'Non',
                'performance' => $this->calculerPerformance($tickets)
            ];
        }

        return [
            'cooperatives' => collect($stats)->sortByDesc('total_graines')->values(),
            'total_cooperatives' => $cooperatives->count(),
            'cooperatives_avec_sechoir' => $cooperatives->where('a_sechoir', true)->count(),
            'performance_moyenne' => collect($stats)->avg('performance'),
            'repartition_geographique' => $this->getRepartitionGeographique($cooperatives)
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
        return TicketPesee::selectRaw('DATE_FORMAT(date_entree, "%Y-%m") as mois, SUM(poids_net) as total')
            ->whereBetween('date_entree', [$dateDebut, $dateFin])
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();
    }

    private function getTopCooperatives($dateDebut, $dateFin, $limit = 5)
    {
        return Cooperative::with(['ticketsPesee' => function($query) use ($dateDebut, $dateFin) {
            $query->whereBetween('date_entree', [$dateDebut, $dateFin]);
        }])
        ->get()
        ->map(function($coop) {
            return [
                'nom' => $coop->nom,
                'total_graines' => $coop->ticketsPesee->sum('poids_net')
            ];
        })
        ->sortByDesc('total_graines')
        ->take($limit)
        ->values();
    }

    private function getRepartitionSecteurs($dateDebut, $dateFin)
    {
        return DB::table('tickets_pesee')
            ->join('connaissements', 'tickets_pesee.connaissement_id', '=', 'connaissements.id')
            ->join('secteurs', 'connaissements.secteur_id', '=', 'secteurs.id')
            ->whereBetween('tickets_pesee.date_entree', [$dateDebut, $dateFin])
            ->select('secteurs.nom', DB::raw('SUM(tickets_pesee.poids_net) as total'))
            ->groupBy('secteurs.nom')
            ->get();
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
}
