<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicRecord extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'student_id',
        'didactic_unit_id',
        'academic_period_id',
        'final_grade',
        'credits_earned',
        'course_status',
        'times_taken',
        'notes',
    ];

    protected $casts = [
        'final_grade' => 'decimal:2',
    ];

    /**
     * El récord pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * El récord es de una unidad didáctica (curso).
     */
    public function didacticUnit()
    {
        return $this->belongsTo(DidacticUnit::class);
    }

    /**
     * El récord se registró en un periodo académico.
     */
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }
}
