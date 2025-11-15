<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id',
        'user_id',
        'cash_session_id',
        'reason',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * La nota de crédito anula un comprobante.
     */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * La nota de crédito fue emitida por un usuario (cajero/admin).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La nota de crédito pertenece a una sesión de caja.
     */
    public function cashSession(): BelongsTo
    {
        return $this->belongsTo(CashSession::class);
    }
}
