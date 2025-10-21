<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestadorInfo extends Model
{
    use HasFactory;

    protected $table = 'prestadores_info';

    protected $fillable = [
        'usuario_id',
        'telefono',
        'foto_perfil',
        'genero',
        'descripcion',
        'experiencia',
        'especialidades',
        'verificado',
        'calificacion_promedio',
        'total_calificaciones',
        'disponibilidad'
    ];

    /**
     * Relación con el usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con la verificación
     */
    public function verificacion()
    {
        return $this->hasOne(Verificacion::class, 'usuario_id', 'usuario_id');
    }
}