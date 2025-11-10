<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institution_id',
        'code',
        'name',
        'duration_semesters',
        'degree_awarded',
        'authorization_resolution',
        'status',
    ];

    /**
     * Una carrera pertenece a una institución.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Una carrera tiene muchos planes de estudio.
     */
    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class);
    }
    
    /**
     * [NUEVA FUNCIÓN AÑADIDA]
     * Una carrera tiene muchos estudiantes.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
