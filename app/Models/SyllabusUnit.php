<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'syllabus_indicator_id',
        'session_number',
        'week_range',
        'execution_date',
        'name',
        'content',
        'learning_outcome',
        'evaluation_instrument',
    ];

    protected $casts = [
        'execution_date' => 'date',
    ];

    public function indicator()
    {
        return $this->belongsTo(SyllabusIndicator::class, 'syllabus_indicator_id');
    }

    /**
     * Relación 1 a 1 con el detalle operativo (Sesión de Aprendizaje)
     */
    public function learningSession()
    {
        return $this->hasOne(LearningSession::class);
    }
}
