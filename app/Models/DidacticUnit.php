<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DidacticUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['name_with_semester'];

    protected $fillable = [
        'module_id',
        'code',
        'name',
        'semester',
        'weekly_hours',
        'total_hours',
        'credits',
        'unit_type',
        'description',
        'specific_competencies',
        'semester_order',
        'status',
    ];

    /**
     * Una unidad didáctica pertenece a un módulo.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Relación de Prerrequisitos (Muchos a Muchos consigo misma).
     * Esto obtiene los cursos que ESTA unidad necesita. (Ej. "Cálculo II" necesita "Cálculo I")
     */
    public function prerequisites()
    {
        return $this->belongsToMany(
            DidacticUnit::class,      // Modelo relacionado
            'prerequisites',          // Tabla pivote
            'didactic_unit_id',       // Llave foránea de *este* modelo en la tabla pivote
            'prerequisite_unit_id'    // Llave foránea del *otro* modelo en la tabla pivote
        );
    }

    /**
     * Relación inversa de Prerrequisitos.
     * Esto obtiene los cursos para los cuales ESTA unidad es un prerrequisito. (Ej. "Cálculo I" es prerrequisito de "Cálculo II")
     */
    public function isPrerequisiteFor()
    {
        return $this->belongsToMany(
            DidacticUnit::class,
            'prerequisites',
            'prerequisite_unit_id',
            'didactic_unit_id'
        );
    }

    /**
     * [NUEVO] Accessor para el nombre con semestre.
     * Esto crea el atributo virtual 'name_with_semester'
     */
    protected function nameWithSemester(): Attribute
    {
        return Attribute::make(
            get: fn () => "(Sem {$this->semester}) - {$this->name}",
        );
    }

    /**
     * Historial de sílabos dictados en esta unidad didáctica.
     */
    public function syllabi()
    {
        return $this->hasManyThrough(Syllabus::class, TeacherAssignment::class);
    }
}
