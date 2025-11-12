<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institution_id',
        'academic_year_id',
        'code',
        'name',
        'start_date',
        'end_date',
        'enrollment_start_date',
        'enrollment_end_date',
        'classes_start_date',
        'classes_end_date',
        'grade_entry_start_date', // <-- [AÑADIR]
        'grade_entry_end_date',   // <-- [AÑADIR]
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'enrollment_start_date' => 'date',
        'enrollment_end_date' => 'date',
        'classes_start_date' => 'date',
        'classes_end_date' => 'date',
        'grade_entry_start_date' => 'datetime', // <-- [AÑADIR]
        'grade_entry_end_date' => 'datetime',   // <-- [AÑADIR]
    ];

    /**
     * Un periodo pertenece a una institución.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Un periodo pertenece a un año académico.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Un periodo tiene muchas asignaciones (secciones).
     */
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }
}
