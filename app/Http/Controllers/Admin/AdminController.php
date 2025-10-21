<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Verificacion;
use App\Models\Calificacion;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalUsuarios = User::count();
        $totalPrestadores = User::role('Prestador')->count();
        $totalClientes = User::role('Cliente')->count();
        $totalServicios = Servicio::count();
        $totalCategorias = Categoria::count();
        
        // Estadísticas de verificaciones
        $solicitudesPendientes = Verificacion::where('estado', 'pendiente')->count();
        $solicitudesAprobadas = Verificacion::where('estado', 'aprobado')->count();
        $solicitudesRechazadas = Verificacion::where('estado', 'rechazado')->count();
        
        // Servicios por estado
        $serviciosActivos = Servicio::where('estado', 'activo')->count();
        $serviciosInactivos = Servicio::where('estado', 'inactivo')->count();
        
        // Calificaciones
        $totalCalificaciones = Calificacion::count();
        $promedioCalificaciones = Calificacion::avg('puntuacion') ?? 0;
        
        // Usuarios recientes (últimos 7 días)
        $usuariosRecientes = User::where('created_at', '>=', now()->subDays(7))->count();
        
        // Servicios recientes (últimos 7 días)
        $serviciosRecientes = Servicio::where('created_at', '>=', now()->subDays(7))->count();
        
        // Top categorías con más servicios
        $topCategorias = Categoria::withCount(['servicios as servicios_count'])
            ->orderBy('servicios_count', 'desc')
            ->take(5)
            ->get();
            
        // Últimas solicitudes de verificación pendientes
        $ultimasSolicitudes = Verificacion::with('usuario')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Servicios más populares (por visitas)
        $serviciosPopulares = Servicio::with('prestador', 'categoria')
            ->orderBy('visitas', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsuarios',
            'totalPrestadores',
            'totalClientes',
            'totalServicios',
            'totalCategorias',
            'solicitudesPendientes',
            'solicitudesAprobadas',
            'solicitudesRechazadas',
            'serviciosActivos',
            'serviciosInactivos',
            'totalCalificaciones',
            'promedioCalificaciones',
            'usuariosRecientes',
            'serviciosRecientes',
            'topCategorias',
            'ultimasSolicitudes',
            'serviciosPopulares'
        ));
    }
}