<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutoring extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'student_id',
        'teacher_id',
        'tutoring_date',
        'tutoring_type',
        'reason',
        'session_development',
        'agreements_commitments',
        'follow_up_required',
        'status',
    ];

    /**
     * Define los casts para los tipos de datos.
     */
    protected $casts = [
        'tutoring_date' => 'datetime',
        'follow_up_required' => 'boolean',
    ];

    /**
     * La tutoría pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * La tutoría es impartida por un docente.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
