<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestador_id',
        'categoria_id',
        'subcategoria_id',
        'titulo',
        'descripcion',
        'tipo_precio',
        'precio',
        'imagen',
        'visitas',
        'fecha_publicacion',
        'latitud',
        'longitud',
        'direccion',
        'ciudad',
        'provincia',
        'pais',
        'estado',
        'calificacion_promedio', 
        'total_calificaciones',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'fecha_publicacion' => 'datetime',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'visitas' => 'integer',
        'calificacion_promedio' => 'decimal:2',
        'total_calificaciones' => 'integer'
    ];

    /**
     * Relación con el usuario prestador
     */
    public function prestador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prestador_id');
    }

    /**
     * Relación con la categoría
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Relación con la subcategoría
     */
    public function subcategoria(): BelongsTo
    {
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
    }

    /**
     * Relación con las calificaciones
     */
    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'servicio_id');
    }

    /**
     * Relación con los favoritos
     */
    public function favoritos(): HasMany
    {
        return $this->hasMany(Favorito::class, 'servicio_id');
    }

    /**
     * Relación con la información del prestador
     */
    public function prestadorInfo(): BelongsTo
    {
        return $this->belongsTo(PrestadorInfo::class, 'prestador_id', 'usuario_id');
    }

    /**
     * Scope para servicios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo')
                    ->whereHas('categoria', function($q) {
                        $q->where('estado', 'activo');
                    });
    }

    /**
     * Scope para servicios de una categoría específica
     */
    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    /**
     * Scope para servicios con coordenadas
     */
    public function scopeConCoordenadas($query)
    {
        return $query->whereNotNull('latitud')->whereNotNull('longitud');
    }

    /**
     * Obtener coordenadas para maps
     */
    public function getCoordenadasAttribute()
    {
        if ($this->latitud && $this->longitud) {
            return [
                'lat' => (float) $this->latitud,
                'lng' => (float) $this->longitud
            ];
        }
        return null;
    }

    /**
     * Calcular y actualizar el promedio de calificaciones del servicio
     */
    public function calcularPromedioCalificaciones()
    {
        $promedio = $this->calificaciones()->avg('puntuacion');
        $total = $this->calificaciones()->count();
        
        $this->update([
            'calificacion_promedio' => $promedio ? round($promedio, 2) : 0.00,
            'total_calificaciones' => $total
        ]);
        
        return $this;
    }

    /**
     * Obtener calificaciones con información del cliente
     */
    public function calificacionesConCliente()
    {
        return $this->calificaciones()->with('cliente');
    }

    /**
     * Verificar si un usuario ha marcado como favorito este servicio
     */
    public function esFavoritoDe($userId)
    {
        if (!$userId) return false;
        
        return $this->favoritos()->where('cliente_id', $userId)->exists();
    }

    /**
     * Obtener servicios relacionados (misma categoría)
     */
    public function serviciosRelacionados($limit = 4)
    {
        return self::with('prestadorInfo')
            ->where('categoria_id', $this->categoria_id)
            ->where('id', '!=', $this->id)
            ->where('estado', 'activo')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Incrementar contador de visitas
     */
    public function incrementarVisitas()
    {
        return $this->increment('visitas');
    }

    /**
     * Formatear precio para mostrar
     */
    public function getPrecioFormateadoAttribute()
    {
        if ($this->tipo_precio === 'cotizacion') {
            return 'A cotización';
        } elseif ($this->precio) {
            return 'Bs. ' . number_format($this->precio, 2);
        } else {
            return 'Consultar precio';
        }
    }

    /**
     * Obtener la URL de la imagen o imagen por defecto
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset('storage/' . $this->imagen);
        }
        return asset('images/default-service.jpg');
    }

    /**
     * Boot del modelo - Eventos
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear un servicio, inicializar calificaciones en 0
        static::creating(function ($servicio) {
            if (!isset($servicio->calificacion_promedio)) {
                $servicio->calificacion_promedio = 0.00;
            }
            if (!isset($servicio->total_calificaciones)) {
                $servicio->total_calificaciones = 0;
            }
        });
    }
}