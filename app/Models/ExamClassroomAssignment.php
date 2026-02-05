<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamClassroomAssignment extends Model
{
    public $timestamps = false; // Solo usamos assigned_at
    protected $fillable = ['exam_classroom_id', 'applicant_id', 'assigned_at'];

    public function classroom()
    {
        return $this->belongsTo(ExamClassroom::class, 'exam_classroom_id');
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
