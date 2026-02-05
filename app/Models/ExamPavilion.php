<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamPavilion extends Model
{
    protected $fillable = ['name', 'location', 'is_active'];

    public function classrooms()
    {
        return $this->hasMany(ExamClassroom::class);
    }
}
