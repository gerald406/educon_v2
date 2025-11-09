<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'student_id',
        'certificate_type',
        'module_id',
        'code',
        'issue_date',
        'document_url',
        'issued_by_user_id',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * El certificado pertenece a un estudiante.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * El certificado (si es modular) pertenece a un módulo.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * El certificado fue emitido por un usuario (administrador).
     */
    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by_user_id');
    }
}
