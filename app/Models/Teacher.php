<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
// (Asegúrate de que no haya NINGUNA importación 'use' 
// a 'ScheduleManager' o cualquier otro componente de Livewire)

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'institution_id',
        'code',
        'academic_degree',
        'specialty',
        'professional_experience',
        'cv_url',
        'contract_type',
        'hire_date',
        'preparation_day',
        'status',
    ];

    /**
     * Un perfil de docente pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un docente pertenece a una institución.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}