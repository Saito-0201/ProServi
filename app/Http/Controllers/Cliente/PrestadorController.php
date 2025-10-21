<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PrestadorController extends Controller
{
    public function show(User $prestador)
    {
        // Verificar que el usuario es realmente un prestador
        if (!$prestador->hasRole('Prestador')) {
            abort(404, 'Prestador no encontrado');
        }

        // Cargar la información del prestador
        $prestador->load('prestadorInfo');
        
        // Obtener servicios del prestador
        $servicios = $prestador->servicios()
            ->where('estado', 'activo')
            ->with(['categoria', 'subcategoria'])
            ->latest()
            ->get();

        return view('cliente.prestadores.show', compact('prestador', 'servicios'));
    }
}