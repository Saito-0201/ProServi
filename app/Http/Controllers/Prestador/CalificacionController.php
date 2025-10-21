<?php

namespace App\Http\Controllers\Prestador;

use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    public function index()
    {
        // Obtener las calificaciones de los servicios del prestador
        $calificaciones = Calificacion::with(['servicio', 'cliente'])
            ->whereHas('servicio', function($query) {
                $query->where('prestador_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calcular promedio de calificaciones
        $promedio = Calificacion::whereHas('servicio', function($query) {
                $query->where('prestador_id', Auth::id());
            })
            ->avg('puntuacion');

        $totalCalificaciones = Calificacion::whereHas('servicio', function($query) {
                $query->where('prestador_id', Auth::id());
            })
            ->count();

        return view('prestador.calificaciones.index', compact('calificaciones', 'promedio', 'totalCalificaciones'));
    }
}