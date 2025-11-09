<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'registration_id',
        'evaluation_type_id',
        'registered_by_user_id',
        'grade',
        'evaluation_date',
        'notes',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'evaluation_date' => 'date',
    ];

    /**
     * La nota pertenece a una inscripción (estudiante-en-curso).
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * La nota corresponde a un tipo de evaluación.
     */
    public function evaluationType()
    {
        return $this->belongsTo(EvaluationType::class);
    }

    /**
     * La nota fue registrada por un usuario (docente).
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}
