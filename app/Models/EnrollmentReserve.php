<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentReserve extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes
    // Esta tabla no tenía academic_period_id en la migración, así que la omitimos aquí

    protected $fillable = [
        'student_id',
        'academic_period_id',
        'resolution_code',
        'reason',
        'start_date',
        'end_date',
        'supporting_document_url',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Una reserva pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }
}
