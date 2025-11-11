<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicActivity extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'teacher_assignment_id',
        'title',
        'description',
        'activity_type',
        'assigned_date',
        'due_date',
        'weight',
        'activity_file_url',
    ];

    /**
     * Define los casts para los tipos de datos.
     */
    protected $casts = [
        'assigned_date' => 'datetime',
        'due_date' => 'datetime',
        'weight' => 'decimal:2',
    ];

    /**
     * La actividad pertenece a una asignación (sección).
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(TeacherAssignment::class);
    }

    /**
     * Una actividad tiene muchas entregas (submissions).
     */
    public function submissions()
    {
        return $this->hasMany(ActivitySubmission::class);
    }
}
