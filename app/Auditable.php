<?php

namespace App;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot the trait
     */
    protected static function bootAuditable()
    {
        // Log des créations
        static::created(function ($model) {
            static::logAction($model, 'CREATE');
        });

        // Log des modifications
        static::updated(function ($model) {
            static::logAction($model, 'UPDATE', $model->getOriginal());
        });

        // Log des suppressions
        static::deleted(function ($model) {
            static::logAction($model, 'DELETE');
        });
    }

    /**
     * Logger une action
     */
    protected static function logAction($model, $action, $oldValues = null)
    {
        try {
            $user = Auth::user();
            $request = Request::instance();

            // Préparer les données
            $data = [
                'action' => $action,
                'module' => static::getModuleName(),
                'object_type' => class_basename($model),
                'object_id' => $model->id ?? null,
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'Système',
                'user_role' => $user?->role ?? null,
                'old_values' => $oldValues ? static::sanitizeData($oldValues) : null,
                'new_values' => $action !== 'DELETE' ? static::sanitizeData($model->toArray()) : null,
                'description' => static::getActionDescription($model, $action),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => session()->getId(),
                'request_method' => $request->method(),
                'request_url' => $request->fullUrl(),
                'request_data' => static::sanitizeRequestData($request->all()),
                'browser' => static::getBrowserInfo($request->userAgent()),
                'os' => static::getOSInfo($request->userAgent()),
                'device' => static::getDeviceInfo($request->userAgent()),
                'is_successful' => true,
                'execution_time' => null, // À calculer si nécessaire
            ];

            // Créer le log
            AuditLog::create($data);

        } catch (\Exception $e) {
            // En cas d'erreur, logger silencieusement pour éviter les boucles
            \Log::error('Erreur lors de la création du log d\'audit: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir le nom du module
     */
    protected static function getModuleName()
    {
        $class = get_called_class();
        $basename = class_basename($class);
        
        $moduleMap = [
            'Connaissement' => 'connaissements',
            'FarmerList' => 'farmer-lists',
            'TicketPesee' => 'tickets-pesee',
            'Facture' => 'factures',
            'RecuAchat' => 'recus-achat',
            'Producteur' => 'producteurs',
            'Cooperative' => 'cooperatives',
            'CentreCollecte' => 'centres-collecte',
            'User' => 'users',
            'Secteur' => 'secteurs',
        ];

        return $moduleMap[$basename] ?? strtolower($basename);
    }

    /**
     * Obtenir la description de l'action
     */
    protected static function getActionDescription($model, $action)
    {
        $descriptions = [
            'CREATE' => "Création d'un nouvel enregistrement",
            'UPDATE' => "Modification d'un enregistrement existant",
            'DELETE' => "Suppression d'un enregistrement",
        ];

        $baseDescription = $descriptions[$action] ?? "Action: {$action}";
        
        // Personnaliser selon le modèle
        if (method_exists($model, 'getAuditDescription')) {
            return $model->getAuditDescription($action);
        }

        // Descriptions personnalisées selon le type de modèle
        $modelName = class_basename($model);
        
        switch ($modelName) {
            case 'Producteur':
                return $action === 'CREATE' ? "Création d'un nouveau producteur : {$model->nom}" : 
                       ($action === 'UPDATE' ? "Modification du producteur : {$model->nom}" : 
                       "Suppression du producteur : {$model->nom}");
                       
            case 'Cooperative':
                return $action === 'CREATE' ? "Création d'une nouvelle coopérative : {$model->nom}" : 
                       ($action === 'UPDATE' ? "Modification de la coopérative : {$model->nom}" : 
                       "Suppression de la coopérative : {$model->nom}");
                       
            case 'Connaissement':
                return $action === 'CREATE' ? "Création d'un nouveau connaissement : {$model->numero_livraison}" : 
                       ($action === 'UPDATE' ? "Modification du connaissement : {$model->numero_livraison}" : 
                       "Suppression du connaissement : {$model->numero_livraison}");
                       
            case 'TicketPesee':
                return $action === 'CREATE' ? "Création d'un nouveau ticket de pesée : {$model->numero_ticket}" : 
                       ($action === 'UPDATE' ? "Modification du ticket de pesée : {$model->numero_ticket}" : 
                       "Suppression du ticket de pesée : {$model->numero_ticket}");
                       
            case 'Facture':
                return $action === 'CREATE' ? "Création d'une nouvelle facture : {$model->numero_facture}" : 
                       ($action === 'UPDATE' ? "Modification de la facture : {$model->numero_facture}" : 
                       "Suppression de la facture : {$model->numero_facture}");
                       
            default:
                return $baseDescription;
        }
    }

    /**
     * Nettoyer les données sensibles
     */
    protected static function sanitizeData($data)
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'secret', 'key'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[HIDDEN]';
            }
        }

        return $data;
    }

    /**
     * Nettoyer les données de requête
     */
    protected static function sanitizeRequestData($data)
    {
        return static::sanitizeData($data);
    }

    /**
     * Obtenir les informations du navigateur
     */
    protected static function getBrowserInfo($userAgent)
    {
        $browsers = [
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'Edge' => 'Edge',
            'Opera' => 'Opera',
        ];

        foreach ($browsers as $name => $browser) {
            if (strpos($userAgent, $name) !== false) {
                return $browser;
            }
        }

        return 'Unknown';
    }

    /**
     * Obtenir les informations du système d'exploitation
     */
    protected static function getOSInfo($userAgent)
    {
        $os = [
            'Windows' => 'Windows',
            'Mac' => 'macOS',
            'Linux' => 'Linux',
            'Android' => 'Android',
            'iOS' => 'iOS',
        ];

        foreach ($os as $name => $system) {
            if (strpos($userAgent, $name) !== false) {
                return $system;
            }
        }

        return 'Unknown';
    }

    /**
     * Obtenir les informations de l'appareil
     */
    protected static function getDeviceInfo($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) {
            return 'Mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    /**
     * Logger une action personnalisée
     */
    public function logCustomAction($action, $description = null, $data = [])
    {
        static::logAction($this, $action, null, $description, $data);
    }

    /**
     * Logger une validation
     */
    public function logValidation($validatedBy = null)
    {
        $this->logCustomAction('VALIDATE', 'Validation de l\'enregistrement', [
            'validated_by' => $validatedBy ?? Auth::user()?->name
        ]);
    }

    /**
     * Logger une annulation
     */
    public function logCancellation($cancelledBy = null, $reason = null)
    {
        $this->logCustomAction('CANCEL', 'Annulation de l\'enregistrement', [
            'cancelled_by' => $cancelledBy ?? Auth::user()?->name,
            'reason' => $reason
        ]);
    }

    /**
     * Logger un export
     */
    public function logExport($exportType = null)
    {
        $this->logCustomAction('EXPORT', 'Export des données', [
            'export_type' => $exportType
        ]);
    }

    /**
     * Logger un import
     */
    public function logImport($importType = null, $recordsCount = null)
    {
        $this->logCustomAction('IMPORT', 'Import des données', [
            'import_type' => $importType,
            'records_count' => $recordsCount
        ]);
    }
}
