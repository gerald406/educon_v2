<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_assignment_id',
        'general_competence',
        'specific_competencies',
        'terminal_capacities',
        'evaluation_criteria',
        'bibliography',
        'status',
        'approval_date',
        'approved_by_user_id',
        'file_url', // <-- El campo clave para el PDF
        'version',
    ];

    protected $casts = [
        'approval_date' => 'date',
    ];

    /**
     * Un sílabo pertenece a una asignación de carga.
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(TeacherAssignment::class);
    }

    /**
     * El sílabo fue aprobado por un usuario (admin/coordinador).
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }
}
