<?php

namespace App\Http\Controllers\Prestador;

use App\Http\Controllers\Controller;
use App\Models\Verificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerificacionController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        
        // Verificar si ya tiene una verificación pendiente o aprobada
        $verificacionExistente = Verificacion::where('usuario_id', $user->id)
            ->whereIn('estado', ['pendiente', 'aprobado'])
            ->first();
            
        if ($verificacionExistente) {
            return redirect()->route('prestador.verificacion.estado')
                ->with('error', 'Ya tienes una solicitud de verificación ' . $verificacionExistente->estado);
        }
        
        // Pasar variable para saber si viene de un rechazo
        $fromRechazo = Verificacion::where('usuario_id', $user->id)
            ->where('estado', 'rechazado')
            ->exists();
        
        return view('prestador.verificacion.create', compact('fromRechazo'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Verificar si ya existe una verificación pendiente o aprobada
        $verificacionExistente = Verificacion::where('usuario_id', $user->id)
            ->whereIn('estado', ['pendiente', 'aprobado'])
            ->first();
            
        if ($verificacionExistente) {
            return redirect()->route('prestador.verificacion.estado')
                ->with('error', 'Ya tienes una solicitud de verificación ' . $verificacionExistente->estado);
        }

        $request->validate([
            'foto_cara' => 'required|image|max:5120',
            'carnet_frente' => 'required|image|max:5120',
            'carnet_reverso' => 'required|image|max:5120',
        ]);

        try {
            // Buscar si existe una verificación rechazada anterior
            $verificacionAnterior = Verificacion::where('usuario_id', $user->id)
                ->where('estado', 'rechazado')
                ->first();

            // Si existe una verificación rechazada, actualizarla
            if ($verificacionAnterior) {
                // Eliminar archivos antiguos si existen
                if ($verificacionAnterior->ruta_foto_cara) {
                    Storage::disk('public')->delete($verificacionAnterior->ruta_foto_cara);
                }
                if ($verificacionAnterior->ruta_imagen_carnet) {
                    Storage::disk('public')->delete($verificacionAnterior->ruta_imagen_carnet);
                }
                if ($verificacionAnterior->ruta_reverso_carnet) {
                    Storage::disk('public')->delete($verificacionAnterior->ruta_reverso_carnet);
                }

                // Subir nuevos archivos CON DISCO 'public'
                $rutaFotoCara = $request->file('foto_cara')->store('verificaciones/fotos', 'public');
                $rutaCarnetFrente = $request->file('carnet_frente')->store('verificaciones/carnets', 'public');
                $rutaCarnetReverso = $request->file('carnet_reverso')->store('verificaciones/carnets', 'public');

                // Actualizar la verificación existente
                $verificacionAnterior->update([
                    'ruta_foto_cara' => $rutaFotoCara,
                    'ruta_imagen_carnet' => $rutaCarnetFrente,
                    'ruta_reverso_carnet' => $rutaCarnetReverso,
                    'estado' => 'pendiente',
                    'motivo_rechazo' => null, // Limpiar el motivo de rechazo
                    'fecha_verificacion' => null, // Limpiar fecha de verificación
                    'created_at' => now(), // Actualizar fecha de creación
                ]);

                $verificacion = $verificacionAnterior;
            } else {
                // Crear nueva verificación si no existe una anterior CON DISCO 'public'
                $rutaFotoCara = $request->file('foto_cara')->store('verificaciones/fotos', 'public');
                $rutaCarnetFrente = $request->file('carnet_frente')->store('verificaciones/carnets', 'public');
                $rutaCarnetReverso = $request->file('carnet_reverso')->store('verificaciones/carnets', 'public');

                $verificacion = Verificacion::create([
                    'usuario_id' => $user->id,
                    'ruta_foto_cara' => $rutaFotoCara,
                    'ruta_imagen_carnet' => $rutaCarnetFrente,
                    'ruta_reverso_carnet' => $rutaCarnetReverso,
                    'estado' => 'pendiente',
                    'numero_carnet' => null, // Se llenará cuando el admin apruebe
                    'fecha_emision' => null, // Se llenará cuando el admin apruebe
                ]);
            }

            return redirect()->route('prestador.verificacion.estado')
                ->with('success', 'Solicitud de verificación enviada correctamente. Estará en revisión.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al subir los archivos: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function estado()
    {
        $user = Auth::user();
        $verificacion = Verificacion::where('usuario_id', $user->id)->latest()->first();
        
        return view('prestador.verificacion.estado', compact('verificacion'));
    }

    public function destroy()
    {
        $user = Auth::user();
        
        $verificacion = Verificacion::where('usuario_id', $user->id)
            ->where('estado', 'pendiente')
            ->first();

        if (!$verificacion) {
            return redirect()->route('prestador.verificacion.estado')
                ->with('error', 'No tienes una solicitud pendiente para cancelar.');
        }

        try {
            // Eliminar archivos del storage CON DISCO 'public'
            if ($verificacion->ruta_foto_cara) {
                Storage::disk('public')->delete($verificacion->ruta_foto_cara);
            }
            if ($verificacion->ruta_imagen_carnet) {
                Storage::disk('public')->delete($verificacion->ruta_imagen_carnet);
            }
            if ($verificacion->ruta_reverso_carnet) {
                Storage::disk('public')->delete($verificacion->ruta_reverso_carnet);
            }

            // Eliminar el registro
            $verificacion->delete();

            return redirect()->route('prestador.verificacion.estado')
                ->with('success', 'Solicitud de verificación cancelada correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('prestador.verificacion.estado')
                ->with('error', 'Error al cancelar la solicitud: ' . $e->getMessage());
        }
    }
}