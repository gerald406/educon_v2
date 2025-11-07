<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    /** @use HasFactory<\Database\Factories\AcademicYearFactory> */
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'year',
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Define los tipos de datos para Eloquent.
     */
    protected $casts = [
        'year' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relación inversa: Un año académico pertenece a una institución.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
