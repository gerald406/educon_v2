<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'career_id',
        'study_plan_id',
        'code',
        'admission_type',
        'exam_score',
        'merit_position',
        'application_status',
    ];

    protected $casts = [
        'exam_score' => 'decimal:2',
    ];

    /**
     * Un perfil de postulante pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un postulante aplica a una carrera.
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * Un postulante aplica a un plan de estudios.
     */
    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class);
    }
}
