<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProducteurDocument extends Model
{
    protected $fillable = [
        'producteur_id', 'type', 'data', 'signature'
    ];

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }
}
