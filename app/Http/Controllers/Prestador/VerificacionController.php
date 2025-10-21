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
        $verificacionExistente = Verificacion::where('usuario_id', $user->id)->first();
        
        // Si ya tiene una solicitud, redirigir al estado
        if ($verificacionExistente) {
            return redirect()->route('prestador.verificacion.estado');
        }

        return view('prestador.verificacion.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Verificar si ya existe una solicitud
        $verificacionExistente = Verificacion::where('usuario_id', $user->id)->first();
        if ($verificacionExistente) {
            return redirect()->route('prestador.verificacion.estado')
                ->with('info', 'Ya tienes una solicitud de verificación en proceso.');
        }

        $request->validate([
            'foto_cara' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'carnet_frente' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'carnet_reverso' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Guardar archivos
        $fotoCaraPath = $request->file('foto_cara')->store('verificaciones/fotos', 'public');
        $carnetFrentePath = $request->file('carnet_frente')->store('verificaciones/carnets', 'public');
        $carnetReversoPath = $request->file('carnet_reverso')->store('verificaciones/carnets', 'public');

        // Crear solicitud de verificación
        Verificacion::create([
            'usuario_id' => $user->id,
            'ruta_foto_cara' => $fotoCaraPath,
            'ruta_imagen_carnet' => $carnetFrentePath,
            'ruta_reverso_carnet' => $carnetReversoPath,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('prestador.verificacion.estado')
            ->with('success', 'Solicitud de verificación enviada correctamente. Revisaremos tu información pronto.');
    }

    public function estado()
    {
        $user = Auth::user();
        $verificacion = Verificacion::where('usuario_id', $user->id)->first();

        return view('prestador.verificacion.estado', compact('verificacion'));
    }

    public function destroy()
    {
        $user = Auth::user();
        $verificacion = Verificacion::where('usuario_id', $user->id)->first();

        if ($verificacion && $verificacion->estado === 'pendiente') {
            // Eliminar archivos
            if ($verificacion->ruta_foto_cara) {
                Storage::disk('public')->delete($verificacion->ruta_foto_cara);
            }
            if ($verificacion->ruta_imagen_carnet) {
                Storage::disk('public')->delete($verificacion->ruta_imagen_carnet);
            }
            if ($verificacion->ruta_reverso_carnet) {
                Storage::disk('public')->delete($verificacion->ruta_reverso_carnet);
            }

            $verificacion->delete();

            return redirect()->route('prestador.perfil.show')
                ->with('success', 'Solicitud de verificación cancelada correctamente.');
        }

        return redirect()->back()->with('error', 'No se puede cancelar la solicitud.');
    }
}