<?php

namespace App\Http\Controllers\Prestador;

use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestadorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Servicios del prestador
        $servicios = Servicio::where('prestador_id', $user->id)->latest()->get();

        $totalServicios  = $servicios->count();
        $serviciosActivos = $servicios->where('estado', 'activo')->count();
        $visitasTotales  = $servicios->sum('visitas');

        // ✅ CORREGIDO: Calcular rating promedio basado en las calificaciones reales
        $ratingPromedio = Calificacion::whereHas('servicio', function($query) use ($user) {
                $query->where('prestador_id', $user->id);
            })
            ->avg('puntuacion');

        // Si no hay calificaciones, establecer 0
        $ratingPromedio = $ratingPromedio ? (float) $ratingPromedio : 0.0;

        // Servicios recientes
        $serviciosRecientes = Servicio::where('prestador_id', $user->id)
            ->with('categoria')
            ->latest()
            ->take(5)
            ->get();

        // Calificaciones recientes recibidas
        $calificacionesRecientes = Calificacion::with(['servicio:id,titulo', 'cliente:id,name,lastname'])
            ->whereHas('servicio', function($query) use ($user) {
                $query->where('prestador_id', $user->id);
            })
            ->latest('fecha')
            ->take(5)
            ->get();

        return view('prestador.index', compact(
            'totalServicios', 
            'serviciosActivos', 
            'visitasTotales',
            'ratingPromedio', 
            'serviciosRecientes', 
            'calificacionesRecientes'
        ));
    }
}