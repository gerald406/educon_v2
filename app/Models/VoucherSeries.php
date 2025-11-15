<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherSeries extends Model
{
    use HasFactory;

    // Desactivamos timestamps (created_at, updated_at) para esta tabla
    public $timestamps = false;

    protected $fillable = [
        'institution_id',
        'voucher_type',
        'series',
        'current_number',
        'status',
    ];

    protected $casts = [
        'current_number' => 'integer',
    ];

    /**
     * La serie pertenece a una institución.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
