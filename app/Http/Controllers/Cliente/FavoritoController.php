<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Favorito;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoritoController extends Controller
{
    /**
     * Mostrar lista de favoritos del usuario
     */
    public function index()
    {
        $favoritos = Favorito::with([
            'servicio.categoria', 
            'servicio.prestadorInfo'
        ])
        ->where('cliente_id', Auth::id())
        ->orderBy('fecha', 'desc')
        ->paginate(12);

        return view('cliente.favoritos.index', compact('favoritos'));
    }

    /**
     * Alternar favorito (agregar/eliminar)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id'
        ]);

        try {
            DB::beginTransaction();

            $servicioId = $request->servicio_id;
            $clienteId = Auth::id();

            // Verificar que el servicio existe y está activo
            $servicio = Servicio::where('id', $servicioId)
                ->where('estado', 'activo')
                ->first();

            if (!$servicio) {
                return response()->json([
                    'success' => false,
                    'message' => 'Servicio no disponible'
                ], 404);
            }

            $favorito = Favorito::where('cliente_id', $clienteId)
                ->where('servicio_id', $servicioId)
                ->first();

            if ($favorito) {
                // Eliminar de favoritos
                $favorito->delete();
                $isFavorite = false;
                $message = 'Servicio eliminado de favoritos';
            } else {
                // Agregar a favoritos
                Favorito::create([
                    'cliente_id' => $clienteId,
                    'servicio_id' => $servicioId,
                    'fecha' => now()
                ]);
                $isFavorite = true;
                $message = 'Servicio agregado a favoritos';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorite,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar favoritos'
            ], 500);
        }
    }

    /**
     * Eliminar favorito específico (solo para eliminación directa desde la lista)
     */
    public function destroy($id)
    {
        $favorito = Favorito::where('cliente_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $favorito->delete();

        return redirect()->route('cliente.favoritos.index')
            ->with('success', 'Servicio eliminado de favoritos');
    }

    /**
     * Verificar si un servicio es favorito
     */
    public function check($servicioId)
    {
        $isFavorite = Favorito::where('cliente_id', Auth::id())
            ->where('servicio_id', $servicioId)
            ->exists();

        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }
}