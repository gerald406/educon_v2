<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'description',
        'sort_order',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }

    /**
     * Un indicador tiene muchas sesiones programadas (semanas/clases)
     */
    public function units()
    {
        return $this->hasMany(SyllabusUnit::class)->orderBy('session_number');
    }
}
