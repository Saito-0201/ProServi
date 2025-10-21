<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre_cat',
        'descripcion_cat',
        'estado'
    ];

    protected $casts = [
        'estado' => 'string'
    ];

    /**
     * Relación con subcategorías
     */
    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'categoria_id');
    }

    /**
     * Relación con servicios
     */
    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'categoria_id');
    }

    /**
     * Relación con servicios activos
     */
    public function serviciosActivos()
    {
        return $this->hasMany(Servicio::class, 'categoria_id')->where('estado', 'activo');
    }

    /**
     * Obtener el conteo de servicios para esta categoría
     */
    public function getTotalServiciosAttribute()
    {
        return $this->servicios()->count();
    }

    /**
     * Obtener el conteo de servicios activos para esta categoría
     */
    public function getServiciosActivosCountAttribute()
    {
        return $this->servicios()->where('estado', 'activo')->count();
    }

    /**
     * Obtener el conteo de servicios inactivos para esta categoría
     */
    public function getServiciosInactivosCountAttribute()
    {
        return $this->servicios()->where('estado', 'inactivo')->count();
    }

    /**
     * Scope para categorías activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para categorías inactivas
     */
    public function scopeInactivas($query)
    {
        return $query->where('estado', 'inactivo');
    }

    /**
     * Scope para categorías con servicios
     */
    public function scopeConServicios($query)
    {
        return $query->whereHas('servicios');
    }

    /**
     * Scope para categorías populares (con más servicios)
     */
    public function scopePopulares($query, $limit = 5)
    {
        return $query->withCount('servicios')
                    ->orderBy('servicios_count', 'desc')
                    ->limit($limit);
    }

    /**
     * Verificar si la categoría tiene servicios
     */
    public function tieneServicios()
    {
        return $this->servicios()->exists();
    }

    /**
     * Verificar si la categoría tiene subcategorías
     */
    public function tieneSubcategorias()
    {
        return $this->subcategorias()->exists();
    }

    /**
     * Obtener la cantidad de subcategorías
     */
    public function getTotalSubcategoriasAttribute()
    {
        return $this->subcategorias()->count();
    }

    /**
     * Activar la categoría
     */
    public function activar()
    {
        $this->update(['estado' => 'activo']);
        return $this;
    }

    /**
     * Desactivar la categoría
     */
    public function desactivar()
    {
        $this->update(['estado' => 'inactivo']);
        return $this;
    }

    /**
     * Verificar si la categoría está activa
     */
    public function estaActiva()
    {
        return $this->estado === 'activo';
    }

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Al eliminar una categoría, manejar las relaciones
        static::deleting(function($categoria) {
            // Opcional: puedes decidir qué hacer con los servicios al eliminar una categoría
            // Por ejemplo, moverlos a una categoría por defecto o eliminarlos
            // $categoria->servicios()->update(['categoria_id' => 1]); // Mover a categoría por defecto
        });
    }

    /**
     * Obtener estadísticas completas de la categoría
     */
    public function getEstadisticasAttribute()
    {
        return [
            'total_servicios' => $this->total_servicios,
            'servicios_activos' => $this->servicios_activos_count,
            'servicios_inactivos' => $this->servicios_inactivos_count,
            'total_subcategorias' => $this->total_subcategorias,
            'estado' => $this->estado,
            'porcentaje_activos' => $this->total_servicios > 0 
                ? round(($this->servicios_activos_count / $this->total_servicios) * 100, 2) 
                : 0
        ];
    }
}