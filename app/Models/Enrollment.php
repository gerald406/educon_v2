<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'academic_period_id',
        'enrollment_date',
        'semester_enrolled',
        'enrollment_type',
        'amount_paid',
        'payment_status',
        'status',
        'notes',
    ];

    protected $casts = [
        'enrollment_date' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Una matrícula pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Una matrícula pertenece a un periodo académico.
     */
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    /**
     * Una matrícula tiene muchas inscripciones (cursos).
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
