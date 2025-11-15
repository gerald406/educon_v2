<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opening_time',
        'opening_balance',
        'closing_time',
        'closing_balance_cash',
        'calculated_cash',
        'total_other_methods',
        'difference',
        'status',
        'notes',
    ];

    protected $casts = [
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
        'opening_balance' => 'decimal:2',
        'closing_balance_cash' => 'decimal:2',
        'calculated_cash' => 'decimal:2',
        'total_other_methods' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    /**
     * La sesión de caja pertenece a un usuario (cajero).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una sesión de caja tiene muchos comprobantes (vouchers).
     */
    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    /**
     * Una sesión de caja tiene muchas notas de crédito.
     */
    public function creditNotes(): HasMany
    {
        return $this->hasMany(CreditNote::class);
    }
}
