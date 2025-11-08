<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
// (Nos aseguramos de que HasTeams NO esté aquí)
use Laravel\Sanctum\HasApiTokens;
// [NUEVO] Importamos las relaciones
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type', // Añadido en Fase 26
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Obtiene el perfil de docente asociado al usuario.
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }
    
    /**
     * Obtiene el perfil de estudiante asociado al usuario.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }
}