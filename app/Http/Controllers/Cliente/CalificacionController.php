<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalificacionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        // Verificar que el usuario es cliente
        if (!Auth::user()->hasRole('Cliente')) {
            return redirect()->back()->with('error', 'Solo los clientes pueden calificar servicios.');
        }

        // Verificar si ya existe una calificación para actualizar
        $calificacionExistente = Calificacion::where('cliente_id', Auth::id())
            ->where('servicio_id', $request->servicio_id)
            ->first();

        DB::transaction(function () use ($request, $calificacionExistente) {
            if ($calificacionExistente) {
                // Actualizar calificación existente
                $calificacionExistente->update([
                    'puntuacion' => $request->puntuacion,
                    'comentario' => $request->comentario,
                    'fecha' => now(),
                ]);
                
                $servicio = $calificacionExistente->servicio;
            } else {
                // Crear nueva calificación
                $servicio = Servicio::findOrFail($request->servicio_id);
                
                $calificacion = Calificacion::create([
                    'cliente_id' => Auth::id(),
                    'prestador_id' => $servicio->prestador_id,
                    'servicio_id' => $request->servicio_id,
                    'puntuacion' => $request->puntuacion,
                    'comentario' => $request->comentario,
                    'fecha' => now(),
                ]);
            }

            // Actualizar el promedio de calificaciones del servicio
            $this->actualizarPromedioServicio($servicio);
        });

        $mensaje = $calificacionExistente ? 'Calificación actualizada correctamente.' : 'Calificación enviada correctamente.';
        return redirect()->back()->with('success', $mensaje);
    }

    /**
     * Obtener la calificación del usuario actual para un servicio
     */
    public function getUserRating($servicioId)
    {
        if (!Auth::check()) {
            return response()->json(['rating' => null]);
        }

        $calificacion = Calificacion::where('cliente_id', Auth::id())
            ->where('servicio_id', $servicioId)
            ->first();

        if ($calificacion) {
            return response()->json([
                'rating' => [
                    'puntuacion' => $calificacion->puntuacion,
                    'comentario' => $calificacion->comentario,
                    'id' => $calificacion->id
                ]
            ]);
        }

        return response()->json(['rating' => null]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $calificacion = Calificacion::where('id', $id)
            ->where('cliente_id', Auth::id())
            ->firstOrFail();

        $servicioId = $calificacion->servicio_id;
        
        DB::transaction(function () use ($calificacion, $servicioId) {
            $calificacion->delete();

            // Actualizar SOLO el promedio del servicio después de eliminar
            $servicio = Servicio::find($servicioId);
            if ($servicio) {
                $this->actualizarPromedioServicio($servicio);
            }
        });

        return redirect()->back()->with('success', 'Calificación eliminada correctamente.');
    }

    /**
     * Actualizar SOLO el promedio de calificaciones del servicio específico
     */
    private function actualizarPromedioServicio($servicio)
    {
        $calificaciones = Calificacion::where('servicio_id', $servicio->id)->get();
        
        if ($calificaciones->count() > 0) {
            $promedio = $calificaciones->avg('puntuacion');
            $total = $calificaciones->count();
            
            // Actualizar directamente en la tabla servicios
            $servicio->update([
                'calificacion_promedio' => $promedio ? round($promedio, 2) : 0.00,
                'total_calificaciones' => $total
            ]);
        } else {
            // Si no hay calificaciones, establecer en 0
            $servicio->update([
                'calificacion_promedio' => 0.00,
                'total_calificaciones' => 0
            ]);
        }
    }
}