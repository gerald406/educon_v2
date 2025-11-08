<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// [NUEVO] Importar tipos de relación
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// [NUEVO] Importar los modelos relacionados
use App\Models\Teacher;
use App\Models\DidacticUnit;
use App\Models\AcademicPeriod;
use App\Models\Shift;
use App\Models\Schedule;

class TeacherAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'didactic_unit_id',
        'academic_period_id',
        'shift_id',
        'section',
        'max_capacity',
        'current_enrolled',
        'status',
    ];

    /**
     * La asignación pertenece a un docente.
     */
    public function teacher(): BelongsTo // <-- Tipo de retorno añadido
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * La asignación pertenece a una unidad didáctica (curso).
     */
    public function didacticUnit(): BelongsTo // <-- Tipo de retorno añadido
    {
        return $this->belongsTo(DidacticUnit::class);
    }

    /**
     * La asignación pertenece a un periodo académico.
     */
    public function academicPeriod(): BelongsTo // <-- Tipo de retorno añadido
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    /**
     * La asignación pertenece a un turno.
     */
    public function shift(): BelongsTo // <-- Tipo de retorno añadido
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Una asignación (sección) tiene un horario (o varios).
     */
    public function schedules(): HasMany // <-- Tipo de retorno añadido
    {
        return $this->hasMany(Schedule::class);
    }
}