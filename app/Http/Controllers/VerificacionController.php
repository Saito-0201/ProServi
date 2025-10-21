<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Verificacion;
use App\Models\User;
use Illuminate\Http\Request;

class VerificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $verificaciones = Verificacion::with('usuario')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.verificaciones.index', compact('verificaciones'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $verificacion = Verificacion::with('usuario')->findOrFail($id);
        return view('admin.verificaciones.show', compact('verificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $verificacion = Verificacion::with('usuario')->findOrFail($id);
        return view('admin.verificaciones.edit', compact('verificacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'numero_carnet' => 'required|string|max:20',
            'fecha_emision' => 'required|date',
            'estado' => 'required|in:pendiente,aprobado,rechazado'
        ]);

        $verificacion = Verificacion::findOrFail($id);
        
        $data = $request->only(['numero_carnet', 'fecha_emision', 'estado']);
        
        // Si se aprueba o rechaza, registrar la fecha de verificación
        if ($request->estado !== 'pendiente') {
            $data['fecha_verificacion'] = now();
        }

        $verificacion->update($data);

        // Actualizar también el estado de verificación en la información del prestador
        if ($verificacion->usuario->prestadorInfo) {
            $verificacion->usuario->prestadorInfo->update([
                'verificado' => $request->estado === 'aprobado'
            ]);
        }

        return redirect()->route('admin.verificaciones.index')
            ->with('mensaje', 'Verificación actualizada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Aprobar una verificación
     */
    public function aprobar($id)
    {
        $verificacion = Verificacion::findOrFail($id);
        
        $verificacion->update([
            'estado' => 'aprobado',
            'fecha_verificacion' => now()
        ]);

        // Actualizar el estado de verificación del prestador
        if ($verificacion->usuario->prestadorInfo) {
            $verificacion->usuario->prestadorInfo->update([
                'verificado' => true
            ]);
        }

        return redirect()->route('admin.verificaciones.index')
            ->with('mensaje', 'Verificación aprobada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Rechazar una verificación
     */
    public function rechazar($id)
    {
        $verificacion = Verificacion::findOrFail($id);
        
        $verificacion->update([
            'estado' => 'rechazado',
            'fecha_verificacion' => now()
        ]);

        // Actualizar el estado de verificación del prestador
        if ($verificacion->usuario->prestadorInfo) {
            $verificacion->usuario->prestadorInfo->update([
                'verificado' => false
            ]);
        }

        return redirect()->route('admin.verificaciones.index')
            ->with('mensaje', 'Verificación rechazada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Obtener verificaciones pendientes (para AJAX)
     */
    public function pendientes()
    {
        $verificaciones = Verificacion::with('usuario')
            ->pendientes()
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($verificaciones);
    }
}
