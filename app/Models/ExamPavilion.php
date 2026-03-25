<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPavilion extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'is_active'];

    public function classrooms()
    {
        return $this->hasMany(ExamClassroom::class);
    }
}
