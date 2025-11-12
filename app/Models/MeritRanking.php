<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeritRanking extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes
    // Timestamps están habilitados por defecto (created_at, updated_at)

    protected $fillable = [
        'student_id',
        'academic_period_id',
        'module_id',
        'weighted_average',
        'general_position',
        'module_position',
        'period_credits',
        'calculation_date',
    ];

    /**
     * Define los casts para los tipos de datos.
     */
    protected $casts = [
        'weighted_average' => 'decimal:2',
        'calculation_date' => 'datetime',
    ];

    /**
     * El registro del ranking pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * El registro del ranking pertenece a un periodo académico.
     */
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    /**
     * El registro del ranking (opcionalmente) pertenece a un módulo.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
