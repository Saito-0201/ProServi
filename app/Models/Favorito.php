<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorito extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cliente_id',
        'servicio_id',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    /**
     * Relación con el cliente
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    /**
     * Relación con el servicio
     */
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    /**
     * Relación con la información del prestador a través del servicio
     */
    public function prestadorInfo()
    {
        return $this->hasOneThrough(
            PrestadorInfo::class,
            Servicio::class,
            'id', // FK en Servicio
            'usuario_id', // FK en PrestadorInfo
            'servicio_id', // Local key en Favorito
            'prestador_id' // Local key en Servicio
        );
    }
}