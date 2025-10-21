<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table ='users';
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'google_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Determinar si se debe enviar el email de verificación
     * Solo enviar si NO es un usuario de Google (que ya está verificado)
     */
    public function sendEmailVerificationNotification()
    {
        if (is_null($this->google_id)) {
            // Solo enviar email de verificación para usuarios manuales
            $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
        }
    }

    /**
     * Relación con información de cliente
     */
    public function clienteInfo()
    {
        return $this->hasOne(ClienteInfo::class, 'usuario_id');
    }

    /**
     * Relación con información de prestador
     */
    public function prestadorInfo()
    {
        return $this->hasOne(PrestadorInfo::class, 'usuario_id');
    }

    

    /**
     * Relación con calificaciones como cliente
     */
    public function calificacionesComoCliente()
    {
        return $this->hasMany(Calificacion::class, 'cliente_id');
    }

    /**
     * Relación con calificaciones como prestador
     */
    public function calificacionesComoPrestador()
    {
        return $this->hasMany(Calificacion::class, 'prestador_id');
    }

    /**
     * Relación con servicios favoritos
     */
    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'cliente_id');
    }

    /**
     * Relación con servicios publicados (si es prestador)
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'prestador_id');
    }

    /**
     * Verificar si el usuario es cliente
     */
    public function esCliente()
    {
        return $this->hasRole('Cliente');
    }

    /**
     * Verificar si el usuario es prestador
     */
    public function esPrestador()
    {
        return $this->hasRole('Prestador');
    }


    // Relación con la tabla de verificaciones
    public function verificacion()
    {
        return $this->hasOne(Verificacion::class, 'usuario_id');
    }

    // Verificar si el usuario tiene una solicitud de verificación
    public function tieneSolicitudVerificacion()
    {
        return $this->verificacion !== null;
    }

    // Verificar si el usuario está verificado
    public function estaVerificado()
    {
        return $this->verificacion && $this->verificacion->estado === 'aprobado';
    }

    // Obtener el estado de verificación
    public function getEstadoVerificacionAttribute()
    {
        if (!$this->verificacion) {
            return 'Sin solicitud';
        }

        return $this->verificacion->estado;
    }
    public function getFechaCreacionFormateada()
    {
        return $this->created_at->locale('es')->translatedFormat('F Y');
    }
}