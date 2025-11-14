<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enrollment_id',
        'teacher_assignment_id',
        'registration_date',
        'registration_type',
        'status',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
    ];

    /**
     * Una inscripción pertenece a una matrícula.
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Una inscripción pertenece a una sección (carga académica).
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(TeacherAssignment::class);
    }

    /**
     * Una matrícula pertenece a un estudiante
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    /**
     * Una matrícula pertenece a un periodo académico
     */
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }
}
