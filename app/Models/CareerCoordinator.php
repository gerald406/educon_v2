<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerCoordinator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'career_id',
        'is_active',
        'assigned_date',
        'notes',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'assigned_date' => 'date',
    ];

    /**
     * El usuario coordinador asignado.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La carrera que coordina.
     */
    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Solo coordinadores activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
