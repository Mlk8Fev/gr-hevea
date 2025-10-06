<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditService
{
    /**
     * Créer un log d'audit
     */
    public static function log($action, $module, $objectType, $objectId = null, $description = null, $oldValues = null, $newValues = null, $additionalData = [])
    {
        try {
            $user = Auth::user();
            $request = request();

            $data = [
                'action' => $action,
                'module' => $module,
                'object_type' => $objectType,
                'object_id' => $objectId,
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'Système',
                'user_role' => $user?->role ?? null,
                'old_values' => $oldValues ? self::sanitizeData($oldValues) : null,
                'new_values' => $newValues ? self::sanitizeData($newValues) : null,
                'description' => $description ?? self::getDefaultDescription($action, $module),
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'session_id' => session()->getId(),
                'request_method' => $request?->method(),
                'request_url' => $request?->fullUrl(),
                'request_data' => $request ? self::sanitizeRequestData($request->all()) : null,
                'browser' => self::getBrowserInfo($request?->userAgent()),
                'os' => self::getOSInfo($request?->userAgent()),
                'device' => self::getDeviceInfo($request?->userAgent()),
                'is_successful' => true,
                'execution_time' => null,
            ];

            // Ajouter les données supplémentaires
            $data = array_merge($data, $additionalData ?? []);

            return AuditLog::create($data);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du log d\'audit: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Logger une connexion utilisateur
     */
    public static function logLogin(User $user, $request = null)
    {
        if (!$request) {
            $request = request();
        }
        
        return self::log(
            'LOGIN',
            'Gestion des Utilisateurs',
            'User',
            $user->id,
            "Connexion de l'utilisateur {$user->name}",
            $user,
            $request
        );
    }

    /**
     * Logger une déconnexion utilisateur
     */
    public static function logLogout(User $user, $request = null)
    {
        if (!$request) {
            $request = request();
        }
        
        return self::log(
            'LOGOUT',
            'Gestion des Utilisateurs',
            'User',
            $user->id,
            "Déconnexion de l'utilisateur {$user->name}",
            $user,
            $request
        );
    }

    /**
     * Logger une validation
     */
    public static function logValidation($module, $objectType, $objectId, $validatedBy = null)
    {
        return self::log(
            'VALIDATE',
            $module,
            $objectType,
            $objectId,
            "Validation de l'enregistrement",
            null,
            ['validated_by' => $validatedBy ?? Auth::user()?->name]
        );
    }

    /**
     * Logger une annulation
     */
    public static function logCancellation($module, $objectType, $objectId, $reason = null, $cancelledBy = null)
    {
        return self::log(
            'CANCEL',
            $module,
            $objectType,
            $objectId,
            "Annulation de l'enregistrement",
            null,
            [
                'cancelled_by' => $cancelledBy ?? Auth::user()?->name,
                'reason' => $reason
            ]
        );
    }

    /**
     * Logger un export
     */
    public static function logExport($module, $exportType = null, $recordsCount = null)
    {
        return self::log(
            'EXPORT',
            $module,
            'Export',
            null,
            "Export des données du module {$module}",
            null,
            [
                'export_type' => $exportType,
                'records_count' => $recordsCount
            ]
        );
    }

    /**
     * Logger un import
     */
    public static function logImport($module, $importType = null, $recordsCount = null)
    {
        return self::log(
            'IMPORT',
            $module,
            'Import',
            null,
            "Import des données dans le module {$module}",
            null,
            [
                'import_type' => $importType,
                'records_count' => $recordsCount
            ]
        );
    }

    /**
     * Logger une erreur
     */
    public static function logError($action, $module, $objectType, $objectId, $errorMessage, $additionalData = [])
    {
        return self::log(
            $action,
            $module,
            $objectType,
            $objectId,
            "Erreur lors de l'action {$action}",
            null,
            null,
            array_merge([
                'is_successful' => false,
                'error_message' => $errorMessage
            ], $additionalData)
        );
    }

    /**
     * Obtenir la description par défaut
     */
    protected static function getDefaultDescription($action, $module)
    {
        $descriptions = [
            'CREATE' => "Création d'un nouvel enregistrement dans le module {$module}",
            'UPDATE' => "Modification d'un enregistrement dans le module {$module}",
            'DELETE' => "Suppression d'un enregistrement dans le module {$module}",
            'LOGIN' => "Connexion utilisateur",
            'LOGOUT' => "Déconnexion utilisateur",
            'VALIDATE' => "Validation d'un enregistrement dans le module {$module}",
            'CANCEL' => "Annulation d'un enregistrement dans le module {$module}",
            'EXPORT' => "Export des données du module {$module}",
            'IMPORT' => "Import des données dans le module {$module}",
        ];

        return $descriptions[$action] ?? "Action {$action} dans le module {$module}";
    }

    /**
     * Nettoyer les données sensibles
     */
    protected static function sanitizeData($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $sensitiveFields = ['password', 'password_confirmation', 'token', 'secret', 'key', 'api_key'];
        
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
        return self::sanitizeData($data);
    }

    /**
     * Obtenir les informations du navigateur
     */
    protected static function getBrowserInfo($userAgent)
    {
        if (!$userAgent) return 'Unknown';

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
        if (!$userAgent) return 'Unknown';

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
        if (!$userAgent) return 'Unknown';

        if (strpos($userAgent, 'Mobile') !== false) {
            return 'Mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    /**
     * Obtenir les statistiques d'audit
     */
    public static function getStats($startDate = null, $endDate = null)
    {
        $query = AuditLog::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return [
            'total_actions' => $query->count(),
            'successful_actions' => $query->clone()->where('is_successful', true)->count(),
            'failed_actions' => $query->clone()->where('is_successful', false)->count(),
            'actions_by_type' => $query->clone()->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action'),
            'actions_by_module' => $query->clone()->selectRaw('module, COUNT(*) as count')
                ->groupBy('module')
                ->pluck('count', 'module'),
            'unique_users' => $query->clone()->distinct('user_id')->count('user_id'),
        ];
    }
}
