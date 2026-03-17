<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'study_plan_id',
        'module_number',
        'name',
        'description',
        'minimum_credits_approval',
        'total_hours',
        'competencies',
        'sort_order',
        'status',
    ];

    /**
     * Un módulo pertenece a un plan de estudio.
     */
    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class);
    }

    /**
     * Un módulo tiene muchas unidades didácticas.
     */
    public function didacticUnits()
    {
        return $this->hasMany(DidacticUnit::class);
    }

    // Ya debe existir en DidacticUnit.php — verificar
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
