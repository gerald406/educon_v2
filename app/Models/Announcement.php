<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'announcement_type',
        'target_audience',
        'publish_date',
        'expiration_date',
        'attachment_url',
        'published_by_user_id',
        'is_featured',
        'status',
    ];

    /**
     * Define los casts para los tipos de datos.
     */
    protected $casts = [
        'publish_date' => 'datetime',
        'expiration_date' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * El anuncio fue publicado por un usuario (administrador).
     */
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by_user_id');
    }
}
