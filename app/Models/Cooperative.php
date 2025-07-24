<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'secteur_id',
        'president',
        'contact',
        'sigle',
        'latitude',
        'longitude',
        'kilometrage',
        'compte_bancaire',
        'code_banque',
        'code_guichet',
        'nom_cooperative_banque',
    ];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function documents()
    {
        return $this->hasMany(CooperativeDocument::class);
    }

    public function producteurs()
    {
        return $this->belongsToMany(Producteur::class);
    }
}
