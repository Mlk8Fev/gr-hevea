<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecuAchat extends Model
{
    use HasFactory;

    // Spécifier le nom de la table (au singulier)
    protected $table = 'recus_achat';

    protected $fillable = [
        'numero_recu',
        'connaissement_id',
        'producteur_id',
        'farmer_list_id',
        'nom_producteur',
        'prenom_producteur',
        'telephone_producteur',
        'code_fphci',
        'secteur_fphci',
        'centre_collecte',
        'quantite_livree',
        'prix_unitaire',
        'montant_total',
        'signature_acheteur',
        'signature_producteur',
        'date_creation',
        'created_by'
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2'
    ];

    public function connaissement()
    {
        return $this->belongsTo(Connaissement::class);
    }

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }

    public function farmerList()
    {
        return $this->belongsTo(FarmerList::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
