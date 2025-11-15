<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_session_id',
        'issuer_id',
        'client_id',
        'voucher_type',
        'series',
        'number',
        'total_amount',
        'payment_method',
        'transaction_code',
        'observations',
        'status',
        'issued_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    /**
     * El comprobante fue emitido por un usuario (cajero).
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issuer_id');
    }

    /**
     * El comprobante pertenece a una sesión de caja.
     */
    public function cashSession(): BelongsTo
    {
        return $this->belongsTo(CashSession::class);
    }

    /**
     * El comprobante pertenece a un cliente (Usuario).
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Un comprobante tiene uno o más ítems.
     */
    public function items(): HasMany
    {
        return $this->hasMany(VoucherItem::class);
    }

    /**
     * Un comprobante salda uno o más pagos/deudas.
     */
    public function studentPayments(): HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }

    /**
     * Un comprobante puede tener una nota de crédito (anulación).
     */
    public function creditNote(): HasOne
    {
        return $this->hasOne(CreditNote::class);
    }
}
