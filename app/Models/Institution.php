<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Habilita la asignación masiva para estos campos.
     */
    protected $fillable = [
        'code',
        'name',
        'tax_id', // RUC
        'address',
        'phone',
        'email',
        'website',
        'logo_url',
        'status',
    ];

    /**
     * Una institución puede tener muchos años académicos.
     */
    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    
}
