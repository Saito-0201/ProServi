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
        // Validación condicional basada en el estado
        $reglasValidacion = [
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'motivo_rechazo' => 'nullable|string|max:500'
        ];

        // Solo requerir número de carnet y fecha de emisión si no está rechazado
        if ($request->estado !== 'rechazado') {
            $reglasValidacion['numero_carnet'] = 'required|string|max:20';
            $reglasValidacion['fecha_emision'] = 'required|date';
        } else {
            $reglasValidacion['motivo_rechazo'] = 'required|string|max:500';
        }

        $request->validate($reglasValidacion);

        $verificacion = Verificacion::findOrFail($id);
        
        $data = $request->only(['estado', 'motivo_rechazo']);
        
        // Solo incluir número de carnet y fecha de emisión si no está rechazado
        if ($request->estado !== 'rechazado') {
            $data['numero_carnet'] = $request->numero_carnet;
            $data['fecha_emision'] = $request->fecha_emision;
        }
        
        // Si se aprueba o rechaza, registrar la fecha de verificación
        if ($request->estado !== 'pendiente') {
            $data['fecha_verificacion'] = now();
            
            // Limpiar motivo de rechazo si se aprueba
            if ($request->estado === 'aprobado') {
                $data['motivo_rechazo'] = null;
            }
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
            'fecha_verificacion' => now(),
            'motivo_rechazo' => null
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
    public function rechazar(Request $request, $id) // Recibe Request y ID
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|min:10|max:500' // Agregado mínimo de 10 caracteres
        ]);

        $verificacion = Verificacion::findOrFail($id); // Buscar la verificación por ID
        
        $verificacion->update([
            'estado' => 'rechazado',
            'fecha_verificacion' => now(),
            'motivo_rechazo' => $request->motivo_rechazo
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