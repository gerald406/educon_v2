<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id',
        'payment_concept_id',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * El ítem pertenece a un comprobante.
     */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * El ítem corresponde a un concepto de pago.
     */
    public function paymentConcept(): BelongsTo
    {
        return $this->belongsTo(PaymentConcept::class);
    }
}
