<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryResource extends Model
{
    use HasFactory;

    // Esta tabla no usa softDeletes

    protected $fillable = [
        'code',
        'title',
        'author',
        'institution_id', // Añadido
        'career_id',      // Añadido
        'resource_type',
        'publisher',
        'publication_year',
        'isbn',
        'copies_available',
        'physical_location',
        'description',
        'cover_image_url',
        'digital_file_url',
        'status',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'copies_available' => 'integer',
    ];

    /**
     * El recurso pertenece a una institución.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * El recurso (opcionalmente) pertenece a una carrera.
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * Un recurso puede tener muchos préstamos.
     */
    public function loans()
    {
        return $this->hasMany(LibraryLoan::class);
    }
}
