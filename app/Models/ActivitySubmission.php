<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySubmission extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'academic_activity_id',
        'registration_id',
        'submission_date',
        'submission_file_url',
        'student_comments',
        'teacher_comments',
        'grade',
        'review_date',
        'reviewed_by_user_id',
        'status',
    ];

    /**
     * Define los casts para los tipos de datos.
     */
    protected $casts = [
        'submission_date' => 'datetime',
        'review_date' => 'datetime',
        'grade' => 'decimal:2',
    ];

    /**
     * La entrega pertenece a una actividad académica.
     */
    public function academicActivity()
    {
        return $this->belongsTo(AcademicActivity::class);
    }

    /**
     * La entrega fue realizada por un estudiante (a través de su inscripción).
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * La entrega fue revisada por un usuario (docente).
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}
