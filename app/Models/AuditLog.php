<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'action',
        'module',
        'object_type',
        'object_id',
        'user_id',
        'user_name',
        'user_role',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'session_id',
        'request_method',
        'request_url',
        'request_data',
        'browser',
        'os',
        'device',
        'latitude',
        'longitude',
        'country',
        'city',
        'is_successful',
        'error_message',
        'execution_time',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'request_data' => 'array',
        'is_successful' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'execution_time' => 'integer',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir l'objet modifié
     */
    public function getObjectAttribute()
    {
        if ($this->object_type && $this->object_id) {
            $modelClass = "App\\Models\\{$this->object_type}";
            if (class_exists($modelClass)) {
                return $modelClass::find($this->object_id);
            }
        }
        return null;
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par module
     */
    public function scopeModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope pour filtrer par utilisateur
     */
    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope pour les actions réussies
     */
    public function scopeSuccessful($query)
    {
        return $query->where('is_successful', true);
    }

    /**
     * Scope pour les actions échouées
     */
    public function scopeFailed($query)
    {
        return $query->where('is_successful', false);
    }

    /**
     * Obtenir la différence entre les valeurs
     */
    public function getDiffAttribute()
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $diff = [];
        $oldValues = $this->old_values;
        $newValues = $this->new_values;

        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? null;
            if ($oldValue !== $newValue) {
                $diff[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }

        return $diff;
    }

    /**
     * Obtenir le nom d'affichage de l'action
     */
    public function getActionNameAttribute()
    {
        $actions = [
            'CREATE' => 'Création',
            'UPDATE' => 'Modification',
            'DELETE' => 'Suppression',
            'LOGIN' => 'Connexion',
            'LOGOUT' => 'Déconnexion',
            'VALIDATE' => 'Validation',
            'CANCEL' => 'Annulation',
            'EXPORT' => 'Export',
            'IMPORT' => 'Import',
            'CONSULTATION_LISTE' => 'Consultation Liste',
            'CONSULTATION_DETAILS' => 'Consultation Détails',
            'CONSULTATION_PAGE' => 'Consultation Page',
            'AFFICHAGE_FORMULAIRE_CREATION' => 'Formulaire Création',
            'AFFICHAGE_FORMULAIRE_MODIFICATION' => 'Formulaire Modification',
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Obtenir le nom d'affichage du module
     */
    public function getModuleNameAttribute()
    {
        $modules = [
            'connaissements' => 'Connaissements',
            'farmer-lists' => 'Farmer Lists',
            'tickets-pesee' => 'Tickets de Pesée',
            'factures' => 'Factures',
            'recus-achat' => 'Reçus d\'Achat',
            'producteurs' => 'Producteurs',
            'cooperatives' => 'Coopératives',
            'centres-collecte' => 'Centres de Collecte',
            'users' => 'Utilisateurs',
            'secteurs' => 'Secteurs',
        ];

        return $modules[$this->module] ?? $this->module;
    }
}
