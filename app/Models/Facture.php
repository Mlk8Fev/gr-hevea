<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Auditable;

class Facture extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'numero_facture',
        'numero_livraison',
        'type',
        'statut',
        'cooperative_id',
        'montant_total',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'montant_paye',
        'date_emission',
        'date_echeance',
        'date_paiement',
        'date_validation',
        'date_annulation',
        'conditions_paiement',
        'notes',
        'devise',
        'created_by',
        'validee_par'
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_echeance' => 'date',
        'date_paiement' => 'date',
        'date_validation' => 'datetime',
        'date_annulation' => 'datetime',
        'montant_total' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'montant_paye' => 'decimal:2'
    ];

    /**
     * Relation avec la coopérative
     */
    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    /**
     * Relation avec l'utilisateur qui a créé la facture
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec l'utilisateur qui a validé la facture
     */
    public function valideePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    /**
     * Relation avec les tickets de pesée
     */
    public function ticketsPesee(): BelongsToMany
    {
        return $this->belongsToMany(TicketPesee::class, 'facture_ticket_pesee');
    }

    /**
     * Relation avec les tickets de pesée via la table pivot (avec données supplémentaires)
     */
    public function factureTicketsPesee()
    {
        return $this->hasMany(FactureTicketPesee::class);
    }

    /**
     * Scopes
     */
    public function scopeValidee($query)
    {
        return $query->where('statut', 'validee');
    }

    public function scopeBrouillon($query)
    {
        return $query->where('statut', 'brouillon');
    }

    public function scopeAnnulee($query)
    {
        return $query->where('statut', 'annulee');
    }

    public function scopeIndividuelle($query)
    {
        return $query->where('type', 'individuelle');
    }

    public function scopeGlobale($query)
    {
        return $query->where('type', 'globale');
    }

    /**
     * Méthodes
     */
    /**
     * Vérifie si la facture est en brouillon
     */
    public function isBrouillon()
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Vérifie si la facture est validée
     */
    public function isValidee()
    {
        return $this->statut === 'validee';
    }

    /**
     * Vérifie si la facture est payée
     */
    public function isPayee()
    {
        return $this->statut === 'payee';
    }

    /**
     * Vérifie si la facture est annulée
     */
    public function isAnnulee()
    {
        return $this->statut === 'annulee';
    }

    /**
     * Vérifie si la facture est individuelle
     */
    public function isIndividuelle()
    {
        return $this->type === 'individuelle';
    }

    /**
     * Vérifie si la facture est globale
     */
    public function isGlobale()
    {
        return $this->type === 'globale';
    }

    /**
     * Calcule le montant restant à payer
     */
    public function getMontantRestantAttribute()
    {
        return $this->montant_ttc - $this->montant_paye;
    }

    /**
     * Vérifie si la facture est complètement payée
     */
    public function isCompletementPayee()
    {
        return $this->montant_paye >= $this->montant_ttc;
    }

    /**
     * Vérifie si la facture est partiellement payée
     */
    public function isPartiellementPayee()
    {
        return $this->montant_paye > 0 && $this->montant_paye < $this->montant_ttc;
    }

    /**
     * Vérifie si la facture est en retard
     */
    public function isEnRetard()
    {
        return $this->date_echeance && $this->date_echeance < now() && !$this->isCompletementPayee();
    }

    /**
     * Vérifie si la facture peut être validée
     */
    public function canBeValidated()
    {
        // Une facture peut être validée si :
        // 1. Elle est en statut brouillon
        // 2. Elle a au moins un ticket de pesée associé
        // 3. Elle n'est pas déjà validée
        return $this->isBrouillon() && 
               $this->ticketsPesee()->count() > 0 && 
               !$this->isValidee();
    }

    /**
     * Vérifie si la facture peut être payée
     */
    public function canBePaid()
    {
        // Une facture peut être payée si :
        // 1. Elle est validée
        // 2. Elle n'est pas déjà payée
        // 3. Elle n'est pas annulée
        return $this->isValidee() && 
               !$this->isCompletementPayee() && 
               !$this->isAnnulee();
    }
}
