<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producteur extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'code_fphci', 'agronica_id', 'localite', 'secteur_id', 'genre', 'contact', 'superficie_totale'
    ];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function cooperatives()
    {
        return $this->belongsToMany(Cooperative::class);
    }

    public function documents()
    {
        return $this->hasMany(ProducteurDocument::class);
    }

    public function parcelles()
    {
        return $this->hasMany(Parcelle::class)->orderBy('ordre');
    }

    // Calcul automatique de la superficie totale
    public function calculateSuperficieTotale()
    {
        $this->superficie_totale = $this->parcelles()->sum('superficie');
        $this->save();
        return $this->superficie_totale;
    }

    // Vérifier si le producteur peut ajouter plus de parcelles
    public function canAddParcelle()
    {
        return $this->parcelles()->count() < 10;
    }

    // Obtenir le prochain ordre pour une nouvelle parcelle
    public function getNextParcelleOrdre()
    {
        return $this->parcelles()->max('ordre') + 1;
    }
}
