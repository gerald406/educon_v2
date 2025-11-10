<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryLoan extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'library_resource_id',
        'user_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'datetime',
        'due_date' => 'date',
        'return_date' => 'datetime',
        'fine_amount' => 'decimal:2',
    ];

    /**
     * El préstamo es de un recurso de la biblioteca.
     */
    public function libraryResource()
    {
        return $this->belongsTo(LibraryResource::class);
    }

    /**
     * El préstamo fue hecho a un usuario (estudiante, docente, etc.).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
