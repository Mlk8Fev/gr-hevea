<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditService;
use App\Services\NavigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditLogController extends Controller
{
    protected $navigationService;

    public function __construct(NavigationService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    /**
     * Afficher la liste des logs d'audit
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('object_type')) {
            $query->where('object_type', $request->object_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('is_successful')) {
            $query->where('is_successful', $request->is_successful);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('object_type', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 25);
        $auditLogs = $query->paginate($perPage);

        // Données pour les filtres
        $actions = AuditLog::distinct()->pluck('action')->sort();
        $modules = AuditLog::distinct()->pluck('module')->sort();
        $objectTypes = AuditLog::distinct()->pluck('object_type')->sort();
        $users = User::select('id', 'name')->orderBy('name')->get();

        // Statistiques
        $stats = $this->getStats($request);

        $navigation = $this->navigationService->getNavigation();

        return view('admin.audit-logs.index', compact(
            'auditLogs',
            'actions',
            'modules',
            'objectTypes',
            'users',
            'stats',
            'navigation'
        ));
    }

    /**
     * Afficher les détails d'un log
     */
    public function show($id)
    {
        $auditLog = AuditLog::with('user')->findOrFail($id);
        $navigation = $this->navigationService->getNavigation();

        return view('admin.audit-logs.show', compact('auditLog', 'navigation'));
    }

    /**
     * Exporter les logs en PDF
     */
    public function exportPdf(Request $request)
    {
        $query = $this->buildQuery($request);
        $auditLogs = $query->limit(1000)->get(); // Limiter pour éviter les timeouts

        $pdf = Pdf::loadView('admin.audit-logs.export-pdf', [
            'auditLogs' => $auditLogs,
            'filters' => $request->all(),
            'generated_at' => now()
        ]);

        return $pdf->download('audit-logs-' . now()->format('Y-m-d-H-i-s') . '.pdf');
    }

    /**
     * Exporter les logs en Excel
     */
    public function exportExcel(Request $request)
    {
        $query = $this->buildQuery($request);
        $auditLogs = $query->limit(10000)->get();

        // Ici vous pouvez utiliser Laravel Excel ou créer un CSV simple
        $filename = 'audit-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID', 'Date', 'Action', 'Module', 'Utilisateur', 'Objet', 
                'Description', 'IP', 'Navigateur', 'OS', 'Appareil', 'Statut'
            ]);

            // Données
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->action_name,
                    $log->module_name,
                    $log->user_name,
                    $log->object_type . ($log->object_id ? " #{$log->object_id}" : ''),
                    $log->description,
                    $log->ip_address,
                    $log->browser,
                    $log->os,
                    $log->device,
                    $log->is_successful ? 'Succès' : 'Échec'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Obtenir les statistiques
     */
    public function stats(Request $request)
    {
        $stats = $this->getStats($request);
        
        return response()->json($stats);
    }

    /**
     * Construire la requête avec les filtres
     */
    protected function buildQuery(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('object_type')) {
            $query->where('object_type', $request->object_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('is_successful')) {
            $query->where('is_successful', $request->is_successful);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('object_type', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Obtenir les statistiques
     */
    protected function getStats(Request $request)
    {
        $query = AuditLog::query();

        // Appliquer les mêmes filtres
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $total = $query->count();
        $successful = $query->clone()->where('is_successful', true)->count();
        $failed = $query->clone()->where('is_successful', false)->count();

        $actionsByType = $query->clone()
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->pluck('count', 'action');

        $actionsByModule = $query->clone()
            ->selectRaw('module, COUNT(*) as count')
            ->groupBy('module')
            ->orderBy('count', 'desc')
            ->pluck('count', 'module');

        $topUsers = $query->clone()
            ->selectRaw('user_name, COUNT(*) as count')
            ->whereNotNull('user_name')
            ->groupBy('user_name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->pluck('count', 'user_name');

        $recentActivity = $query->clone()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'total' => $total,
            'successful' => $successful,
            'failed' => $failed,
            'success_rate' => $total > 0 ? round(($successful / $total) * 100, 2) : 0,
            'actions_by_type' => $actionsByType,
            'actions_by_module' => $actionsByModule,
            'top_users' => $topUsers,
            'recent_activity' => $recentActivity,
        ];
    }
}
