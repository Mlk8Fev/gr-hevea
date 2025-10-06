<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditService;

class AuditMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $executionTime = round(($endTime - $startTime) * 1000);
        
        // Logger la requête
        $this->logRequest($request, $response, $executionTime);
        
        return $response;
    }

    /**
     * Logger une requête
     */
    protected function logRequest(Request $request, $response, $executionTime)
    {
        if (!$this->shouldLog($request)) {
            return;
        }

        $user = Auth::user();
        $action = $this->getActionFromRequest($request);
        $module = $this->getModuleFromPath($request->path());
        $objectType = $this->getObjectTypeFromPath($request->path());
        $objectId = $this->getObjectIdFromRequest($request);
        $description = $this->getDescriptionFromRequest($request);

        AuditService::log(
            $action,
            $module,
            $objectType,
            $objectId,
            $description,
            $user,
            $request,
            null,
            null,
            true,
            $executionTime
        );
    }

    /**
     * Déterminer si la requête doit être loggée
     */
    protected function shouldLog(Request $request): bool
    {
        $path = $request->path();
        
        // Exclure les assets et les requêtes AJAX
        if (str_starts_with($path, 'css/') || 
            str_starts_with($path, 'js/') || 
            str_starts_with($path, 'images/') ||
            str_starts_with($path, 'fonts/') ||
            str_starts_with($path, 'wowdash/') ||
            $request->ajax()) {
            return false;
        }
        
        // Logger les pages importantes
        $importantPages = [
            'admin/producteurs',
            'admin/cooperatives', 
            'admin/connaissements',
            'admin/farmer-lists',
            'admin/tickets-pesee',
            'admin/factures',
            'admin/recus-achat',
            'admin/centres-collecte',
            'admin/users',
            'admin/audit-logs',
            'admin/secteurs',
            'admin/ene-validation',
            'admin/finance',
            'admin/statistiques',
            'dashboard'
        ];
        
        foreach ($importantPages as $page) {
            if (str_starts_with($path, $page)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Obtenir l'action à partir de la requête
     */
    protected function getActionFromRequest(Request $request): string
    {
        $path = $request->path();
        
        if (str_contains($path, '/create')) {
            return 'AFFICHAGE_FORMULAIRE_CREATION';
        } elseif (str_contains($path, '/edit')) {
            return 'AFFICHAGE_FORMULAIRE_MODIFICATION';
        } elseif (str_contains($path, '/show') || preg_match('/\/\d+$/', $path)) {
            return 'CONSULTATION_DETAILS';
        } elseif (str_contains($path, '/index') || !str_contains($path, '/')) {
            return 'CONSULTATION_LISTE';
        }
        
        return 'CONSULTATION_PAGE';
    }

    /**
     * Obtenir le module à partir du chemin
     */
    protected function getModuleFromPath(string $path): string
    {
        $pathParts = explode('/', $path);
        
        if (count($pathParts) >= 2 && $pathParts[0] === 'admin') {
            $module = $pathParts[1];
            
            $moduleMap = [
                'producteurs' => 'Gestion des Producteurs',
                'cooperatives' => 'Gestion des Coopératives',
                'connaissements' => 'Gestion des Connaissements',
                'farmer-lists' => 'Farmer Lists - Gestion des Livraisons',
                'tickets-pesee' => 'Tickets de Pesée',
                'factures' => 'Gestion des Factures',
                'recus-achat' => 'Reçus d\'Achat',
                'centres-collecte' => 'Centres de Collecte',
                'users' => 'Gestion des Utilisateurs',
                'secteurs' => 'Gestion des Secteurs',
                'ene-validation' => 'Validation ENE CI',
                'finance' => 'Finance',
                'statistiques' => 'Statistiques',
                'audit-logs' => 'Logs Système',
            ];
            
            return $moduleMap[$module] ?? $module;
        }
        
        return 'Tableau de Bord';
    }

    /**
     * Obtenir le type d'objet à partir du chemin
     */
    protected function getObjectTypeFromPath(string $path): ?string
    {
        $pathParts = explode('/', $path);
        
        if (count($pathParts) >= 2 && $pathParts[0] === 'admin') {
            $module = $pathParts[1];
            
            $moduleMap = [
                'producteurs' => 'Producteur',
                'cooperatives' => 'Cooperative',
                'connaissements' => 'Connaissement',
                'farmer-lists' => 'FarmerList',
                'tickets-pesee' => 'TicketPesee',
                'factures' => 'Facture',
                'recus-achat' => 'RecuAchat',
                'centres-collecte' => 'CentreCollecte',
                'users' => 'User',
                'secteurs' => 'Secteur',
                'audit-logs' => 'AuditLog',
            ];
            
            return $moduleMap[$module] ?? null;
        }
        
        return null;
    }

    /**
     * Obtenir l'ID de l'objet à partir de la requête
     */
    protected function getObjectIdFromRequest(Request $request): ?int
    {
        $path = $request->path();
        
        // Extraire l'ID depuis l'URL (ex: /admin/producteurs/123)
        if (preg_match('/\/(\d+)(?:\/|$)/', $path, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }

    /**
     * Obtenir la description à partir de la requête
     */
    protected function getDescriptionFromRequest(Request $request): string
    {
        $path = $request->path();
        $action = $this->getActionFromRequest($request);
        $module = $this->getModuleFromPath($path);
        $objectId = $this->getObjectIdFromRequest($request);
        
        // Descriptions personnalisées selon le module et l'action
        if ($action === 'CONSULTATION_DETAILS' && $objectId) {
            $objectType = $this->getObjectTypeFromPath($path);
            
            switch ($objectType) {
                case 'Producteur':
                    $producteur = \App\Models\Producteur::find($objectId);
                    return $producteur ? "Consultation des détails du producteur : {$producteur->nom} (ID: {$objectId})" : "Consultation des détails d'un producteur (ID: {$objectId})";
                    
                case 'Cooperative':
                    $cooperative = \App\Models\Cooperative::find($objectId);
                    return $cooperative ? "Consultation des détails de la coopérative : {$cooperative->nom} (ID: {$objectId})" : "Consultation des détails d'une coopérative (ID: {$objectId})";
                    
                case 'Connaissement':
                    $connaissement = \App\Models\Connaissement::find($objectId);
                    return $connaissement ? "Consultation des détails du connaissement : {$connaissement->numero_livraison} (ID: {$objectId})" : "Consultation des détails d'un connaissement (ID: {$objectId})";
                    
                case 'TicketPesee':
                    $ticket = \App\Models\TicketPesee::find($objectId);
                    return $ticket ? "Consultation des détails du ticket de pesée : {$ticket->numero_ticket} (ID: {$objectId})" : "Consultation des détails d'un ticket de pesée (ID: {$objectId})";
                    
                case 'Facture':
                    $facture = \App\Models\Facture::find($objectId);
                    return $facture ? "Consultation des détails de la facture : {$facture->numero_facture} (ID: {$objectId})" : "Consultation des détails d'une facture (ID: {$objectId})";
                    
                case 'FarmerList':
                    $farmerList = \App\Models\FarmerList::find($objectId);
                    return $farmerList ? "Consultation des détails de la farmer list : {$farmerList->numero_farmer_list} (ID: {$objectId})" : "Consultation des détails d'une farmer list (ID: {$objectId})";
                    
                default:
                    return "Consultation des détails d'un {$objectType} (ID: {$objectId})";
            }
        }
        
        // Descriptions pour les listes
        if ($action === 'CONSULTATION_LISTE') {
            return "Consultation de la liste des {$module}";
        }
        
        // Descriptions pour les formulaires
        if ($action === 'AFFICHAGE_FORMULAIRE_CREATION') {
            return "Affichage du formulaire de création d'un nouvel élément dans {$module}";
        }
        
        if ($action === 'AFFICHAGE_FORMULAIRE_MODIFICATION') {
            return "Affichage du formulaire de modification d'un élément dans {$module}";
        }
        
        return "Consultation de la page {$module}";
    }
}
