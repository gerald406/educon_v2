<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'syllabus_unit_id',
        'transversal_competence',
        'activity_type',
        'sequence_activities', // JSON Clave
        'evaluation_criteria',
        'evaluation_technique',
        'evaluation_instrument',
        'evaluation_moment',
        'bibliography',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'sequence_activities' => 'array', // Convierte el JSON de momentos en Array
        'completed_at' => 'datetime',
    ];

    public function syllabusUnit()
    {
        return $this->belongsTo(SyllabusUnit::class);
    }
}
