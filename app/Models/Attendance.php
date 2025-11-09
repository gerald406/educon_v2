<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'registration_id',
        'schedule_id',
        'registered_by_user_id',
        'class_date',
        'attendance_type',
        'late_minutes',
    ];

    protected $casts = [
        'class_date' => 'date',
    ];

    /**
     * La asistencia pertenece a una inscripción (estudiante-en-curso).
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * La asistencia corresponde a un bloque de horario específico.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * La asistencia fue registrada por un usuario (docente).
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}
