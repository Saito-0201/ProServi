<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteInfo extends Model
{
    use HasFactory;

    protected $table = 'clientes_info';

    protected $fillable = [
        'usuario_id',
        'telefono',
        'foto_perfil',
        'genero',
    ];

    /**
     * Relación con el usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
