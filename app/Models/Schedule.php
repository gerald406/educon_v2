<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'teacher_assignment_id',
        'classroom_resource_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];
    
    protected $casts = [
        'start_time' => 'datetime:H:i', // Formatear como 14:30
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Un horario pertenece a una asignación (sección).
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(TeacherAssignment::class);
    }

    /**
     * Un horario se dicta en un aula/recurso (opcional).
     */
    public function classroomResource()
    {
        return $this->belongsTo(ClassroomResource::class);
    }
}
