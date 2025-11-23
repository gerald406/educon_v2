<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialEntity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code', // Código interno o cuenta
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }
}
