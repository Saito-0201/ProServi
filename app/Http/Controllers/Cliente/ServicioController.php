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

class ServicioController extends Controller
{
    /**
     * Listado + Búsqueda/Filtros
     */
    public function index(Request $request)
    {
        // Si viene como AJAX (por header o por query ?ajax=true) responder en JSON
        if ($request->ajax() || $request->boolean('ajax') || $request->expectsJson()) {
            return $this->getServiciosAjax($request);
        }

        // -------- Primera carga (HTML) con lote inicial ya renderizado --------
        $categorias = Categoria::where('estado', 'activo')->get();
        $ciudades   = Servicio::select('ciudad')->distinct()->whereNotNull('ciudad')->pluck('ciudad');

        return view('cliente.servicios.index', compact('categorias', 'ciudades'));
    }

    /**
     * Obtener servicios via AJAX para scroll infinito (CORREGIDO)
     */
    public function getServiciosAjax(Request $request)
    {
        try {
            $query = Servicio::with(['categoria', 'subcategoria', 'prestadorInfo'])
                ->where('estado', 'activo');

            // 🔥 CORRECCIÓN: Usar los mismos nombres de parámetros que en el formulario
            if ($request->filled('q')) {
                $q = trim($request->q);
                $query->where(function($sub) use ($q) {
                    $sub->where('titulo', 'like', "%{$q}%")
                        ->orWhere('descripcion', 'like', "%{$q}%");
                });
            }
            
            if ($request->filled('categoria')) $query->where('categoria_id', $request->categoria);
            if ($request->filled('subcategoria')) $query->where('subcategoria_id', $request->subcategoria);
            if ($request->filled('ciudad')) $query->where('ciudad', $request->ciudad);

            if ($request->filled('tipo_precio')) {
                        $query->where('tipo_precio', $request->tipo_precio);
                    }

            if ($request->filled('precio_min')) {
                $query->whereNotNull('precio')->where('precio', '>=', (float)$request->precio_min);
            }
            if ($request->filled('precio_max')) {
                $query->whereNotNull('precio')->where('precio', '<=', (float)$request->precio_max);
            }

            if ($request->boolean('verificados')) {
                $query->whereHas('prestadorInfo', function($q) {
                    $q->where('verificado', 1);
                });
            }

            if ($request->filled('rating_min')) {
                $query->where('calificacion_promedio', '>=', (int)$request->rating_min);
            }

            // Ordenamiento
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
                    // Orden por defecto: más recientes primero
                    $query->orderBy('fecha_publicacion', 'desc');
            }

            // 🔥 CORRECCIÓN CRÍTICA: Usar PAGINATE (no simplePaginate) para consistencia
            $page = max(1, (int)$request->get('page', 1));
            $perPage = 12;
            
            $servicios = $query->paginate($perPage, ['*'], 'page', $page);

            $viewType = $request->get('view', 'grid');
            $html = '';
            
            foreach ($servicios as $servicio) {
                $html .= view('cliente.servicios.partials.service-card', [
                    'servicio' => $servicio,
                    'view' => $viewType
                ])->render();
            }

            // 🔥 CORRECCIÓN: Devolver datos consistentes
            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $servicios->hasMorePages(),
                'nextPage' => $servicios->currentPage() + 1,
                'currentPage' => $servicios->currentPage(),
                'total' => $servicios->total(),
                'lastPage' => $servicios->lastPage()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading services AJAX: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'Error al cargar los servicios',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alias de búsqueda (mantiene una sola lógica)
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

        // visitas
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

        // calificaciones del servicio
        $calificaciones = $servicio->calificacionesConCliente()
            ->orderBy('fecha', 'desc')
            ->paginate(5);

        // relacionados (misma categoría)
        $serviciosRelacionados = $servicio->serviciosRelacionados(4);

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
            return response()->json([], 500);
        }
    }

    /**
     * Servicios cercanos (API para el mapa)
     */
    public function porUbicacion(Request $request)
    {
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