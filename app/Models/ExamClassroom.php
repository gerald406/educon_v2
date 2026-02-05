<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamClassroom extends Model
{
    protected $fillable = ['exam_pavilion_id', 'room_number', 'capacity', 'description', 'is_active'];

    public function pavilion()
    {
        return $this->belongsTo(ExamPavilion::class, 'exam_pavilion_id');
    }

    public function assignments()
    {
        return $this->hasMany(ExamClassroomAssignment::class);
    }
}
