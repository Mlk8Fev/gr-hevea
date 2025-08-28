<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactureTicketPesee extends Model
{
    use HasFactory;

    protected $table = 'facture_ticket_pesee';

    protected $fillable = [
        'facture_id',
        'ticket_pesee_id',
        'montant_ticket'
    ];

    protected $casts = [
        'montant_ticket' => 'decimal:2'
    ];

    /**
     * Relation avec la facture
     */
    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    /**
     * Relation avec le ticket de pesée
     */
    public function ticketPesee(): BelongsTo
    {
        return $this->belongsTo(TicketPesee::class);
    }
}
