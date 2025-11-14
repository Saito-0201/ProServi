<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServicioController extends Controller
{
    /**
     * Listado + Búsqueda/Filtros con carga inteligente
     */
    public function index(Request $request)
    {
        // Si viene como AJAX (por header o por query ?ajax=true) responder en JSON
        if ($request->ajax() || $request->boolean('ajax') || $request->expectsJson()) {
            return $this->getServiciosAjax($request);
        }

        // Primera carga (HTML) SIN servicios iniciales - se cargan via AJAX
        $categorias = Categoria::where('estado', 'activo')->get();
        $ciudades   = Servicio::select('ciudad')->distinct()->whereNotNull('ciudad')->pluck('ciudad');

        return view('cliente.servicios.index', compact('categorias', 'ciudades'));
    }

    /**
     * Obtener servicios via AJAX - VERSIÓN SIMPLIFICADA Y ESTABLE
     */
    public function getServiciosAjax(Request $request)
    {
        try {
            \Log::info('=== INICIANDO CARGA AJAX DE SERVICIOS ===');
            \Log::info('Parámetros recibidos:', $request->all());

            // Consulta base simplificada
            $query = Servicio::with(['categoria', 'subcategoria', 'prestadorInfo'])
                ->where('estado', 'activo');

            // Aplicar filtros básicos primero
            $this->applyBasicFilters($query, $request);

            // Detectar si hay filtros activos
            $hasFilters = $request->filled(['q', 'categoria', 'subcategoria', 'ciudad', 'tipo_precio', 
                                          'precio_min', 'precio_max', 'rating_min']) || 
                         $request->boolean('verificados');

            \Log::info('Filtros activos: ' . ($hasFilters ? 'SÍ' : 'NO'));

            // ORDENAMIENTO SIMPLIFICADO - eliminar algoritmo complejo temporalmente
            if ($hasFilters || $request->filled('orden')) {
                $this->applySimpleOrdering($query, $request);
            } else {
                // Orden por defecto cuando no hay filtros
                $query->orderBy('fecha_publicacion', 'desc')
                      ->orderBy('calificacion_promedio', 'desc')
                      ->inRandomOrder(); // Mezclar para variedad
            }

            // Paginación
            $page = max(1, (int)$request->get('page', 1));
            $perPage = 12;
            
            \Log::info('Ejecutando consulta paginada...');
            $servicios = $query->paginate($perPage, ['*'], 'page', $page);

            \Log::info('Resultados encontrados:', [
                'total' => $servicios->total(),
                'current_page' => $servicios->currentPage(),
                'count' => $servicios->count(),
                'has_more' => $servicios->hasMorePages()
            ]);

            // Generar HTML
            $viewType = $request->get('view', 'grid');
            $html = '';
            
            if ($servicios->count() > 0) {
                foreach ($servicios as $servicio) {
                    try {
                        $html .= view('cliente.servicios.partials.service-card', [
                            'servicio' => $servicio,
                            'view' => $viewType
                        ])->render();
                    } catch (\Exception $e) {
                        \Log::error('Error renderizando servicio ID ' . $servicio->id . ': ' . $e->getMessage());
                        continue;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $servicios->hasMorePages(),
                'nextPage' => $servicios->currentPage() + 1,
                'currentPage' => $servicios->currentPage(),
                'total' => $servicios->total(),
                'lastPage' => $servicios->lastPage(),
                'hasFilters' => $hasFilters
            ]);

        } catch (\Exception $e) {
            \Log::error('=== ERROR CRÍTICO EN getServiciosAjax ===');
            \Log::error('Mensaje: ' . $e->getMessage());
            \Log::error('Archivo: ' . $e->getFile());
            \Log::error('Línea: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor',
                'message' => 'No se pudieron cargar los servicios. Por favor, intenta nuevamente.',
                'debug' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Aplicar filtros básicos - VERSIÓN ESTABLE
     */
    private function applyBasicFilters($query, $request)
    {
        // Búsqueda por texto
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function($subquery) use ($q) {
                $subquery->where('titulo', 'like', "%{$q}%")
                         ->orWhere('descripcion', 'like', "%{$q}%")
                         ->orWhereHas('categoria', function($cat) use ($q) {
                             $cat->where('nombre_cat', 'like', "%{$q}%");
                         })
                         ->orWhereHas('subcategoria', function($subcat) use ($q) {
                             $subcat->where('nombre', 'like', "%{$q}%");
                         });
            });
        }
        
        // Filtros simples
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }
        
        if ($request->filled('subcategoria')) {
            $query->where('subcategoria_id', $request->subcategoria);
        }
        
        if ($request->filled('ciudad')) {
            $query->where('ciudad', $request->ciudad);
        }

        if ($request->filled('tipo_precio')) {
            $query->where('tipo_precio', $request->tipo_precio);
        }

        // Rango de precios
        if ($request->filled('precio_min')) {
            $query->whereNotNull('precio')
                  ->where('precio', '>=', (float)$request->precio_min);
        }
        
        if ($request->filled('precio_max')) {
            $query->whereNotNull('precio')
                  ->where('precio', '<=', (float)$request->precio_max);
        }

        // Prestadores verificados
        if ($request->boolean('verificados')) {
            $query->whereHas('prestadorInfo', function($q) {
                $q->where('verificado', 1);
            });
        }

        // Calificación mínima
        if ($request->filled('rating_min')) {
            $query->where('calificacion_promedio', '>=', (int)$request->rating_min);
        }
    }

    /**
     * Ordenamiento simple - VERSIÓN ESTABLE
     */
    private function applySimpleOrdering($query, $request)
    {
        switch ($request->orden) {
            case 'fecha_desc': 
                $query->orderBy('fecha_publicacion', 'desc'); 
                break;
            case 'precio_asc': 
                $query->orderByRaw('precio IS NULL, precio ASC'); 
                break;
            case 'precio_desc': 
                $query->orderByRaw('precio IS NULL, precio DESC'); 
                break;
            case 'rating_desc': 
                $query->orderBy('calificacion_promedio', 'desc'); 
                break;
            default: 
                // Orden por defecto cuando hay filtros
                $query->orderBy('fecha_publicacion', 'desc')
                      ->orderBy('calificacion_promedio', 'desc');
        }
    }

    /**
     * Endpoint para detectar ubicación del usuario
     */
    public function detectLocation(Request $request)
    {
        try {
            $request->validate([
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'ciudad' => 'nullable|string'
            ]);

            // Guardar ubicación en sesión para personalización futura
            session()->put('user_location', [
                'lat' => $request->lat,
                'lng' => $request->lng,
                'ciudad' => $request->ciudad ?? 'Ubicación detectada'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ubicación detectada correctamente',
                'location' => session('user_location')
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en detectLocation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la ubicación'
            ], 500);
        }
    }

    /**
     * Alias de búsqueda
     */
    public function buscar(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Detalle del servicio
     */
    public function show($id)
    {
        $servicio = Servicio::with([
            'categoria',
            'subcategoria',
            'prestador.prestadorInfo',
            'calificaciones.cliente'
        ])->findOrFail($id);

        // Incrementar visitas
        $servicio->increment('visitas');

        // ¿Es favorito del usuario?
        $esFavorito = false;
        $calificacionUsuario = null;
        
        if (Auth::check()) {
            $esFavorito = Favorito::where('cliente_id', Auth::id())
                ->where('servicio_id', $id)
                ->exists();
                
            // Obtener calificación del usuario actual
            $calificacionUsuario = Calificacion::where('cliente_id', Auth::id())
                ->where('servicio_id', $id)
                ->first();
        }

        // Calificaciones del servicio
        $calificaciones = $servicio->calificaciones()
            ->with('cliente')
            ->orderBy('fecha', 'desc')
            ->paginate(5);

        // Servicios relacionados (misma categoría con algoritmo de relevancia)
        $serviciosRelacionados = Servicio::where('categoria_id', $servicio->categoria_id)
            ->where('id', '!=', $servicio->id)
            ->where('estado', 'activo')
            ->orderBy('calificacion_promedio', 'desc')
            ->orderBy('visitas', 'desc')
            ->limit(4)
            ->get();

        return view('cliente.servicios.show', compact(
            'servicio',
            'esFavorito',
            'calificacionUsuario',
            'calificaciones',
            'serviciosRelacionados'
        ));
    }

    /**
     * Subcategorías por categoría (AJAX)
     */
    public function getSubcategorias($categoriaId)
    {
        try {
            $subcategorias = Subcategoria::where('categoria_id', $categoriaId)->get();
            return response()->json($subcategorias);
        } catch (\Exception $e) {
            \Log::error('Error en getSubcategorias: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Servicios cercanos (API para el mapa)
     */
    public function porUbicacion(Request $request)
    {
        try {
            $request->validate([
                'latitud'  => 'required|numeric',
                'longitud' => 'required|numeric',
                'radio'    => 'nullable|numeric|min:1'
            ]);

            $lat   = (float)$request->latitud;
            $lng   = (float)$request->longitud;
            $radio = $request->radio ? (float)$request->radio : 10; // km

            $servicios = Servicio::with(['categoria', 'subcategoria'])
                ->where('estado', 'activo')
                ->whereNotNull('latitud')
                ->whereNotNull('longitud')
                ->select('servicios.*')
                ->selectRaw("(6371 * acos(cos(radians(?)) * cos(radians(latitud)) * cos(radians(longitud) - radians(?)) + sin(radians(?)) * sin(radians(latitud)))) AS distancia", [$lat, $lng, $lat])
                ->having('distancia', '<=', $radio)
                ->orderBy('distancia')
                ->get();

            return response()->json($servicios);
        } catch (\Exception $e) {
            \Log::error('Error en porUbicacion: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Clave de la API de Google Maps
     */
    public function getGoogleMapsApiKey()
    {
        return response()->json([
            'api_key' => config('services.google.maps.key', '')
        ]);
    }
}