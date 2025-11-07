<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un docente pertenece a una institución.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
