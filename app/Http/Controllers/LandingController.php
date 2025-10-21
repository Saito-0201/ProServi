<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Calificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        try {
            // Obtener servicios destacados
            $serviciosDestacados = Servicio::with(['prestador', 'categoria', 'subcategoria'])
                ->where('estado', 'activo')
                ->orderBy('visitas', 'desc')
                ->orderBy('calificacion_promedio', 'desc')
                ->take(3)
                ->get();

            // Obtener categorías principales
            $categoriasPrincipales = Categoria::withCount(['servicios as total_servicios' => function($query) {
                $query->where('estado', 'activo');
            }])
            ->where('estado', 'activo')
            ->orderBy('total_servicios', 'desc')
            ->take(8)
            ->get();

            // Obtener calificaciones recientes para testimonios
            $testimoniosRecientes = Calificacion::with(['cliente', 'servicio'])
                ->whereNotNull('comentario')
                ->where('comentario', '!=', '')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            // Estadísticas para mostrar
            $estadisticas = [
                'total_servicios' => Servicio::where('estado', 'activo')->count(),
                'total_prestadores' => User::role('Prestador')->count(),
                'total_clientes' => User::role('Cliente')->count(),
                'total_calificaciones' => Calificacion::count(),
            ];

            // Rutas dinámicas según autenticación y rol
            $servicesIndex = $this->getServicesRoute();
            $servicesSearch = $this->getServicesSearchRoute();

            return view('home', compact(
                'serviciosDestacados',
                'categoriasPrincipales',
                'testimoniosRecientes',
                'estadisticas',
                'servicesIndex',
                'servicesSearch'
            ));

        } catch (\Exception $e) {
            // En caso de error, mostrar datos por defecto
            return $this->showDefaultLanding();
        }
    }

    /**
     * Mostrar landing page con datos por defecto en caso de error
     */
    private function showDefaultLanding()
    {
        // Servicios por defecto
        $serviciosDestacados = collect([]);

        $estadisticas = [
            'total_servicios' => 50,
            'total_prestadores' => 25,
            'total_clientes' => 100,
            'total_calificaciones' => 150,
        ];

        $servicesIndex = $this->getServicesRoute();
        $servicesSearch = $this->getServicesSearchRoute();

        return view('home', compact(
            'serviciosDestacados',
            'estadisticas',
            'servicesIndex',
            'servicesSearch'
        ));
    }

    /**
     * Obtener la ruta de servicios según autenticación y rol
     */
    private function getServicesRoute()
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('Prestador')) {
                return route('prestador.servicios.index');
            } else {
                return route('cliente.servicios.index');
            }
        } else {
            return route('public.servicios.index');
        }
    }

    /**
     * Obtener la ruta de búsqueda según autenticación y rol
     */
    private function getServicesSearchRoute()
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('Prestador')) {
                return route('prestador.servicios.index'); // Los prestadores no tienen búsqueda específica
            } else {
                return route('cliente.servicios.buscar');
            }
        } else {
            return route('public.servicios.index');
        }
    }

    public function buscarServicios(Request $request)
    {
        $query = $request->get('q');
        
        if (auth()->check()) {
            if (auth()->user()->hasRole('Prestador')) {
                return redirect()->route('prestador.servicios.index');
            } else {
                return redirect()->route('cliente.servicios.buscar', ['q' => $query]);
            }
        } else {
            return redirect()->route('public.servicios.index', ['q' => $query]);
        }
    }
}