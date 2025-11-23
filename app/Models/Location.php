<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'iddist',
        'nombdep',
        'nombprov',
        'nombdist',
        'nom_capital',
        'cod_reg_nat',
        'region_natural'
    ];

    // Relación: Un distrito puede ser el lugar de nacimiento de muchos postulantes
    public function applicantsBornHere(): HasMany
    {
        return $this->hasMany(Applicant::class, 'ubigeo_birth_id', 'iddist');
    }

    // Relación: Un distrito puede tener muchos colegios
    public function schools(): HasMany
    {
        return $this->hasMany(OriginSchool::class, 'ubigeo_code', 'iddist');
    }

    // Accessor para nombre completo (ej. "Puno / Puno / Puno")
    public function getFullNameAttribute()
    {
        return "{$this->nombdep} / {$this->nombprov} / {$this->nombdist}";
    }
}
