<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'career_id',
        'code',
        'name',
        'version',
        'start_date',
        'end_date',
        'total_credits',
        'total_hours',
        'approval_resolution',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Un plan de estudio pertenece a una carrera.
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * Un plan de estudio tiene muchos módulos.
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
