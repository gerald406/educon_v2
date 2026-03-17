<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'gender',
        'birthday',
        'ubigeo_birth_id',
        'photo_url',
        'origin_school_id',
        'school_graduation_year',
        'admission_offering_id',
        'admission_modality_id',
        'financial_entity_id',
        'payment_operation_code',
        'code',
        'exam_score',
        'merit_position',
        'application_status',
        'registration_step',
        'notes',
    ];

    protected $casts = [
        'birthday' => 'date',
        'exam_score' => 'decimal:2',
    ];

    // --- RELACIONES ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Ubigeo de Nacimiento
    public function birthLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'ubigeo_birth_id', 'iddist');
    }

    public function originSchool(): BelongsTo
    {
        return $this->belongsTo(OriginSchool::class);
    }

    // Oferta (Carrera + Turno)
    public function admissionOffering(): BelongsTo
    {
        return $this->belongsTo(AdmissionOffering::class);
    }

    public function admissionModality(): BelongsTo
    {
        return $this->belongsTo(AdmissionModality::class);
    }

    public function financialEntity(): BelongsTo
    {
        return $this->belongsTo(FinancialEntity::class);
    }

    // Accessor para obtener el nombre de la carrera fácilmente
    public function getCareerNameAttribute()
    {
        return $this->admissionOffering?->career?->name;
    }

    public function examAssignment()
    {
        return $this->hasOne(ExamClassroomAssignment::class);
    }
}
