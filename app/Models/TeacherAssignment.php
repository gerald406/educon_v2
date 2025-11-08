<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * La asignación pertenece a una unidad didáctica (curso).
     */
    public function didacticUnit()
    {
        return $this->belongsTo(DidacticUnit::class);
    }

    /**
     * La asignación pertenece a un periodo académico.
     */
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    /**
     * La asignación pertenece a un turno.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Una asignación (sección) tiene un horario (o varios).
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
