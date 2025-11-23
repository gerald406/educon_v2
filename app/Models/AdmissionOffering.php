<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionOffering extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_period_id',
        'career_id',
        'shift_id',
        'vacancies',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    // Una oferta tiene muchos postulantes
    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }
}
