<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verificacion extends Model
{
    use HasFactory;

    protected $table = 'verificaciones';

    protected $fillable = [
        'usuario_id',
        'numero_carnet',
        'fecha_emision',
        'ruta_imagen_carnet',
        'ruta_reverso_carnet', 
        'ruta_foto_cara',
        'estado',
        'fecha_verificacion',
        'motivo_rechazo',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_verificacion' => 'datetime',
        'verificado' => 'boolean'
    ];

    /**
     * Relación con el usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Scope para verificaciones pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para verificaciones aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobado');
    }

    // Scope para solicitudes rechazadas
    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazado');
    }
}
