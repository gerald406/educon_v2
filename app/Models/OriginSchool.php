<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OriginSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'modular_code',
        'name',
        'management_type',
        'ubigeo_code',
        'd_niv_mod',
        'address',
    ];

    // El colegio pertenece a un Ubigeo (Distrito)
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'ubigeo_code', 'iddist');
    }

    // Un colegio puede ser la procedencia de muchos postulantes
    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }
}
