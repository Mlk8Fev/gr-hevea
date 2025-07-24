<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producteur extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'code_fphci', 'secteur_id', 'genre', 'contact', 'superficie_totale'
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
}
