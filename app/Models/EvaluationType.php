<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationType extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'weight_percentage',
        'is_droppable',
        'sort_order',
        'status',
    ];

    /**
     * Define los casts para números y booleanos.
     */
    protected $casts = [
        'weight_percentage' => 'decimal:2',
        'is_droppable' => 'boolean',
        'sort_order' => 'integer',
    ];
}
