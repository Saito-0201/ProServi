<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\Favorito;
use App\Models\Calificacion;
use App\Models\ClienteInfo;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Asegurarnos de que el clienteInfo exista
        $clienteInfo = ClienteInfo::where('usuario_id', $user->id)->first();
        
        // Si no existe, crear un registro vacío
        if (!$clienteInfo) {
            $clienteInfo = new ClienteInfo();
            $clienteInfo->usuario_id = $user->id;
            $clienteInfo->save();
        }
        
        // Total de favoritos
        $totalFavoritos = Favorito::where('cliente_id', $user->id)->count();
        
        // Total de calificaciones
        $totalCalificaciones = Calificacion::where('cliente_id', $user->id)->count();
        
        // Servicios vistos recientemente (usando sesión)
        $serviciosVistos = 0;
        $serviciosVistosIds = session('servicios_vistos', []);
        if (!empty($serviciosVistosIds)) {
            $serviciosVistos = count($serviciosVistosIds);
        }
        
        // Servicios populares cerca del usuario
        $serviciosPopulares = Servicio::with([
            'categoria', 
            'prestador.prestadorInfo'
        ])
            ->where('estado', 'activo')
            ->orderBy('visitas', 'desc')
            ->take(8)
            ->get();
            
        // Marcar servicios favoritos
        if ($user) {
            $favoritosIds = Favorito::where('cliente_id', $user->id)
                ->pluck('servicio_id')
                ->toArray();
                
            foreach ($serviciosPopulares as $servicio) {
                $servicio->esFavorito = in_array($servicio->id, $favoritosIds);
            }
        }

        return view('cliente.index', compact(
            'totalFavoritos', 
            'totalCalificaciones', 
            'serviciosVistos',
            'serviciosPopulares',
            'clienteInfo'
        ));
    }
}