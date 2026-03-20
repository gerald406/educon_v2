<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamClassroomAssignment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'exam_classroom_id',
        'applicant_id',
        'assigned_at',
        'attended_at',      // ← AÑADIR
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'attended_at' => 'datetime',    // ← AÑADIR
    ];

    public function classroom()
    {
        return $this->belongsTo(ExamClassroom::class, 'exam_classroom_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    // ── Scopes útiles ──
    public function scopeAttended($query)
    {
        return $query->whereNotNull('attended_at');
    }

    public function scopeAbsent($query)
    {
        return $query->whereNull('attended_at');
    }
}
