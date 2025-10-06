<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class Parcelle extends Model
{
    use Auditable;

    protected $fillable = [
        'producteur_id',
        'nom_parcelle',
        'latitude',
        'longitude',
        'superficie',
        'ordre'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'superficie' => 'decimal:2',
    ];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }
}
