<?php
// app/Http/Controllers/Publico/ServicioController.php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Listado de servicios públicos
     */
    public function index(Request $request)
    {
        // Si es AJAX, devolver JSON
        if ($request->ajax() || $request->boolean('ajax') || $request->expectsJson()) {
            return $this->getServiciosAjax($request);
        }

        $categorias = Categoria::where('estado', 'activo')->get();
        $ciudades = Servicio::select('ciudad')->distinct()->whereNotNull('ciudad')->pluck('ciudad');

        return view('publico.servicios.index', compact('categorias', 'ciudades'));
    }

    /**
     * Obtener servicios via AJAX
     */
    public function getServiciosAjax(Request $request)
    {
        try {
            $query = Servicio::with(['categoria', 'subcategoria', 'prestador.prestadorInfo'])
                ->where('estado', 'activo');

            // Aplicar filtros
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

            if ($request->filled('precio_min')) {
                $query->whereNotNull('precio')->where('precio', '>=', (float)$request->precio_min);
            }
            if ($request->filled('precio_max')) {
                $query->whereNotNull('precio')->where('precio', '<=', (float)$request->precio_max);
            }

            if ($request->boolean('verificados')) {
                $query->whereHas('prestador.prestadorInfo', function($q) {
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
                    $query->orderBy('fecha_publicacion', 'desc');
            }

            $page = max(1, (int)$request->get('page', 1));
            $perPage = 12;
            
            $servicios = $query->paginate($perPage, ['*'], 'page', $page);

            $viewType = $request->get('view', 'grid');
            $html = '';
            
            // Renderizar las vistas parciales
            foreach ($servicios as $servicio) {
                $html .= view('publico.servicios.partials.service-card', [
                    'servicio' => $servicio,
                    'view' => $viewType
                ])->render();
            }

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
            \Log::error('Error loading public services AJAX: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al cargar los servicios',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detalle del servicio público
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

        // Calificaciones del servicio
        $calificaciones = $servicio->calificaciones()
            ->with('cliente')
            ->orderBy('fecha', 'desc')
            ->paginate(5);

        // Servicios relacionados
        $serviciosRelacionados = Servicio::where('categoria_id', $servicio->categoria_id)
            ->where('id', '!=', $servicio->id)
            ->where('estado', 'activo')
            ->with(['categoria', 'subcategoria'])
            ->limit(4)
            ->get();

        return view('publico.servicios.show', compact(
            'servicio',
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
            $subcategorias = Subcategoria::where('categoria_id', $categoriaId)
                ->where('estado', 'activo')
                ->get();
            return response()->json($subcategorias);
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }
}