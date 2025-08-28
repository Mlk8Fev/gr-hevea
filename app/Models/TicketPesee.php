<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TicketPesee extends Model
{
    use HasFactory;

    protected $table = 'tickets_pesee';

    protected $fillable = [
        'connaissement_id',
        'numero_ticket',
        'campagne',
        'client',
        'fournisseur',
        'numero_bl',
        'origine',
        'destination',
        'produit',
        'numero_camion',
        'transporteur',
        'chauffeur',
        'equipe_chargement',
        'equipe_dechargement',
        'poids_entree',
        'poids_sortie',
        'poids_net',
        'nombre_sacs_bidons_cartons',
        'poids_100_graines',
        'gp',
        'ga',
        'me',
        'taux_humidite',
        'taux_impuretes',
        'date_entree',
        'heure_entree',
        'date_sortie',
        'heure_sortie',
        'nom_peseur',
        'signature',
        'statut',
        'statut_ene',
        'created_by',
        'validated_by',
        'valide_par_ene',
        'date_validation',
        'date_validation_ene',
        'commentaire_ene'
    ];

    protected $casts = [
        'poids_entree' => 'decimal:2',
        'poids_sortie' => 'decimal:2',
        'poids_net' => 'decimal:2',
        'poids_100_graines' => 'decimal:2',
        'gp' => 'decimal:3',
        'ga' => 'decimal:3',
        'me' => 'decimal:3',
        'taux_humidite' => 'decimal:2',
        'taux_impuretes' => 'decimal:2',
        'date_entree' => 'date',
        'heure_entree' => 'datetime',
        'date_sortie' => 'date',
        'heure_sortie' => 'datetime',
        'date_validation' => 'datetime',
        'date_validation_ene' => 'datetime'
    ];

    // Relations
    public function connaissement()
    {
        return $this->belongsTo(Connaissement::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec l'utilisateur qui a validé le ticket
     */
    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Relation avec l'utilisateur qui a validé par ENE CI
     */
    public function valideParEne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par_ene');
    }

    /**
     * Relation many-to-many avec les factures
     */
    public function factures(): BelongsToMany
    {
        return $this->belongsToMany(Facture::class, 'facture_ticket_pesee')
                    ->withPivot('montant_ticket')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeArchive($query)
    {
        return $query->where('statut', 'archive');
    }

    // Méthodes
    public function isEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function isValide()
    {
        return $this->statut === 'valide';
    }

    public function isArchive()
    {
        return $this->statut === 'archive';
    }

    // Calcul automatique du poids net
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($ticket) {
            if ($ticket->poids_entree && $ticket->poids_sortie) {
                $ticket->poids_net = $ticket->poids_entree - $ticket->poids_sortie;
            }
        });
    }
}
