<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = [
            'categoria_id',
            'nombre',
            'descripcion'
        ];
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'subcategoria_id');
    }
}
