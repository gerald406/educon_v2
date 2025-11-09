<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'student_id',
        'company_name',
        'company_ruc',
        'company_address',
        'supervisor_name',
        'supervisor_position',
        'supervisor_email',
        'start_date',
        'end_date',
        'total_hours',
        'evaluation_score',
        'evaluation_file_url',
        'certificate_url',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'evaluation_score' => 'decimal:2',
    ];

    /**
     * La pasantía pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
