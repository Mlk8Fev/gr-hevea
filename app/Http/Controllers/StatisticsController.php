<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketPesee;
use App\Models\Cooperative;
use App\Models\Connaissement;
use App\Models\Facture;
use App\Models\Producteur;
use App\Models\CentreCollecte;
use App\Models\Secteur;
use App\Services\StatisticsService;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Page des statistiques basiques
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());
        
        $stats = $this->statisticsService->getBasicStatistics($dateFrom, $dateTo);
        
        return view('admin.statistics.index', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * Page des statistiques avancées avec onglets
     */
    public function advanced(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());
        $tab = $request->get('tab', 'general');
        
        $stats = [];
        
        switch ($tab) {
            case 'general':
                $stats = $this->statisticsService->getGeneralStatistics($dateFrom, $dateTo);
                break;
            case 'cooperatives':
                $stats = $this->statisticsService->getCooperativeStatistics($dateFrom, $dateTo);
                break;
            case 'logistics':
                $stats = $this->statisticsService->getLogisticsStatistics($dateFrom, $dateTo);
                break;
            case 'financial':
                $stats = $this->statisticsService->getFinancialStatistics($dateFrom, $dateTo);
                break;
            case 'quality':
                $stats = $this->statisticsService->getQualityStatistics($dateFrom, $dateTo);
                break;
        }
        
        return view('admin.statistics.advanced', compact('stats', 'dateFrom', 'dateTo', 'tab'));
    }

    /**
     * API pour les graphiques (AJAX)
     */
    public function getChartData(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());
        $type = $request->get('type', 'production');
        
        $data = $this->statisticsService->getChartData($type, $dateFrom, $dateTo);
        
        return response()->json($data);
    }

    /**
     * Export des statistiques en PDF
     */
    public function exportPdf(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());
        $tab = $request->get('tab', 'general');
        
        $stats = $this->statisticsService->getStatisticsForExport($tab, $dateFrom, $dateTo);
        
        return response()->json(['message' => 'Export PDF en cours de développement']);
    }

    /**
     * Export des statistiques en Excel
     */
    public function exportExcel(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());
        $tab = $request->get('tab', 'general');
        
        $stats = $this->statisticsService->getStatisticsForExport($tab, $dateFrom, $dateTo);
        
        return response()->json(['message' => 'Export Excel en cours de développement']);
    }
}
