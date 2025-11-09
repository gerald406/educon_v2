<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraduationProcess extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'student_id',
        'process_type',
        'title',
        'abstract',
        'advisor_id',
        'jury_president_id',
        'jury_secretary_id',
        'jury_member_id',
        'proposal_date',
        'approval_date',
        'defense_date',
        'final_grade',
        'document_url',
        'status',
    ];

    protected $casts = [
        'proposal_date' => 'date',
        'approval_date' => 'date',
        'defense_date' => 'date',
        'final_grade' => 'decimal:2',
    ];

    /**
     * El proceso de titulación pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * El Asesor (o Asesor) es un docente.
     */
    public function advisor()
    {
        return $this->belongsTo(Teacher::class, 'advisor_id');
    }

    /**
     * El Presidente del Jurado es un docente.
     */
    public function juryPresident()
    {
        return $this->belongsTo(Teacher::class, 'jury_president_id');
    }
    
    // (Puedes añadir relaciones similares para 'jury_secretary_id' y 'jury_member_id')

}
