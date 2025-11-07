<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    use HasFactory;

    // Indicamos a Laravel que esta tabla pivote sí tiene timestamps
    public $timestamps = true;

    protected $fillable = [
        'didactic_unit_id',
        'prerequisite_unit_id',
        'type',
    ];

    // --- Relaciones (Opcional, pero útil) ---

    /**
     * Obtiene el curso principal.
     */
    public function unit()
    {
        return $this->belongsTo(DidacticUnit::class, 'didactic_unit_id');
    }

    /**
     * Obtiene el curso de prerrequisito.
     */
    public function prerequisiteUnit()
    {
        return $this->belongsTo(DidacticUnit::class, 'prerequisite_unit_id');
    }
}
