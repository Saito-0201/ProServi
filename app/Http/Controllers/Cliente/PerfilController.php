<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\ClienteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Favorito;
use App\Models\Calificacion;

class PerfilController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        $clienteInfo = ClienteInfo::where('usuario_id', $user->id)->first();
        
        // Si no existe información del cliente, creamos un registro vacío
        if (!$clienteInfo) {
            $clienteInfo = new ClienteInfo();
            $clienteInfo->usuario_id = $user->id;
            $clienteInfo->save();
        }
        
        // Estadísticas del cliente
        $totalFavoritos = Favorito::where('cliente_id', $user->id)->count();
        $totalCalificaciones = Calificacion::where('cliente_id', $user->id)->count();
        
        // Obtener servicios favoritos recientes (últimos 3)
        $favoritosRecientes = Favorito::with('servicio')
            ->where('cliente_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('cliente.perfil.show', compact('user', 'clienteInfo', 'totalFavoritos', 'totalCalificaciones', 'favoritosRecientes'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
        $clienteInfo = ClienteInfo::where('usuario_id', $user->id)->first();
        
        // Si no existe información del cliente, creamos un objeto vacío
        if (!$clienteInfo) {
            $clienteInfo = new ClienteInfo();
        }

        return view('cliente.perfil.edit', compact('user', 'clienteInfo'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'genero' => 'nullable|in:masculino,femenino,otro',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Verificar contraseña actual si se quiere cambiar la contraseña (solo para usuarios no de Google)
        if ($request->filled('password') && !$user->google_id) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
            
            $user->password = Hash::make($request->password);
        }

        // Actualizar datos del usuario
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->save();

        // Actualizar o crear información del cliente
        $clienteInfo = ClienteInfo::updateOrCreate(
            ['usuario_id' => $user->id],
            [
                'telefono' => $request->telefono,
                'genero' => $request->genero,
            ]
        );

        return redirect()->route('cliente.perfil.show')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Update the user's profile photo.
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:12288',
        ]);

        $user = Auth::user();
        $clienteInfo = ClienteInfo::where('usuario_id', $user->id)->first();

        if (!$clienteInfo) {
            $clienteInfo = new ClienteInfo();
            $clienteInfo->usuario_id = $user->id;
        }

        // Borrar foto anterior en disco 'public'
        if ($clienteInfo->foto_perfil && Storage::disk('public')->exists($clienteInfo->foto_perfil)) {
            Storage::disk('public')->delete($clienteInfo->foto_perfil);
        }

        // Guardar nueva en 'public'
        $path = $request->file('foto_perfil')->store('clientes/fotos', 'public');
        $clienteInfo->foto_perfil = $path;
        $clienteInfo->save();

        return redirect()->back()->with('success', 'Foto de perfil actualizada correctamente.');
    }

    /**
     * Remove the user's profile photo.
     */
    public function removeFoto()
    {
        $user = Auth::user();
        $clienteInfo = ClienteInfo::where('usuario_id', $user->id)->first();

        if ($clienteInfo && $clienteInfo->foto_perfil) {
            if (Storage::disk('public')->exists($clienteInfo->foto_perfil)) {
                Storage::disk('public')->delete($clienteInfo->foto_perfil);
            }
            $clienteInfo->foto_perfil = null;
            $clienteInfo->save();
        }

        return redirect()->back()->with('success', 'Foto de perfil eliminada correctamente.');
    }
}