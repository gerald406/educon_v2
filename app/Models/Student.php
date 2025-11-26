<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'career_id',
        'study_plan_id',
        'code',
        'current_semester',
        'accumulated_credits',
        'weighted_average',
        'academic_status',
        'admission_date',
        'graduation_date',
        // Nuevos campos
        'phone',
        'address',
        'gender',
        'birthday',
        'ubigeo_birth_id',
        'origin_school_id',
        'school_graduation_year',
        'photo_url'
    ];

    protected $casts = [
        'admission_date' => 'date',
        'graduation_date' => 'date',
        'weighted_average' => 'decimal:2',
    ];

    /**
     * Un perfil de estudiante pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un estudiante (opcionalmente) viene de un registro de postulante.
     */


    /**
     * Un estudiante pertenece a una carrera.
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * Un estudiante pertenece a un plan de estudios.
     */
    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class);
    }

    /**
     * Un estudiante puede tener múltiples reservas de matrícula.
     */
    public function enrollmentReserves()
    {
        return $this->hasMany(EnrollmentReserve::class);
    }

    /**
     * Un estudiante puede tener múltiples matrículas (Enrollments).
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
