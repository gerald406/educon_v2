<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomResource extends Model
{
    /** @use HasFactory<\Database\Factories\ClassroomResourceFactory> */
    use HasFactory;

    protected $fillable = [
        'classroom_code',
        'name',
        'building',
        'floor',
        'capacity',
        'has_projector',
        'has_computers',
        'computer_count',
        'has_air_conditioning',
        'location',
        'status',
    ];

    /**
     * Define los casts para los booleanos y números.
     */
    protected $casts = [
        'capacity' => 'integer',
        'has_projector' => 'boolean',
        'has_computers' => 'boolean',
        'computer_count' => 'integer',
        'has_air_conditioning' => 'boolean',
    ];
}
