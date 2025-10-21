<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicioImagen extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id',
        'imagen',
        'orden'
    ];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

    public function getImagenUrlAttribute()
    {
        return asset('storage/servicios/imagenes/' . $this->imagen);
    }
}
