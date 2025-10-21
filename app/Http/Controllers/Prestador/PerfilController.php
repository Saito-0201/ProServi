<?php

namespace App\Http\Controllers\Prestador;

use App\Http\Controllers\Controller;
use App\Models\PrestadorInfo;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // Obtener o crear información del prestador
        $info = $user->prestadorInfo ?: new PrestadorInfo();
        
        // Contar servicios activos del prestador
        $serviciosActivos = Servicio::where('prestador_id', $user->id)
            ->where('estado', 'activo')
            ->count();

        return view('prestador.perfil.show', compact('user', 'info', 'serviciosActivos'));
    }

    public function edit()
    {
        $user = Auth::user();
        
        // Obtener o crear información del prestador
        $info = $user->prestadorInfo ?: new PrestadorInfo();

        return view('prestador.perfil.edit', compact('user', 'info'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validaciones para el usuario
        $dataUser = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Validaciones para la información del prestador
        $dataInfo = $request->validate([
            'telefono' => 'nullable|string|max:20',
            'genero' => 'nullable|in:masculino,femenino,otro',
            'descripcion' => 'nullable|string',
            'experiencia' => 'nullable|string',
            'especialidades' => 'nullable|string|max:255',
            'disponibilidad' => 'nullable|string|max:255',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:12288',
        ]);

        // Validar contraseña solo si se proporciona y si el usuario no es de Google
        if ($request->filled('password') && !$user->google_id) {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
            
            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
            
            $dataUser['password'] = Hash::make($request->password);
        }

        // Actualizar usuario
        $user->update($dataUser);

        // Obtener o crear información del prestador
        $info = $user->prestadorInfo ?: new PrestadorInfo();
        $info->usuario_id = $user->id;

        // Manejar foto de perfil
        if ($request->hasFile('foto_perfil')) {
            // Eliminar foto anterior si existe
            if ($info->foto_perfil && Storage::disk('public')->exists($info->foto_perfil)) {
                Storage::disk('public')->delete($info->foto_perfil);
            }
            $dataInfo['foto_perfil'] = $request->file('foto_perfil')->store('prestadores/fotos', 'public');
        }

        // Llenar y guardar la información
        $info->fill($dataInfo);
        $info->save();

        return redirect()->route('prestador.perfil.show')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Update the user's profile photo.
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:12288',
        ]);

        $user = Auth::user();
        $info = $user->prestadorInfo ?: new PrestadorInfo();
        $info->usuario_id = $user->id;

        // Borrar foto anterior en disco 'public'
        if ($info->foto_perfil && Storage::disk('public')->exists($info->foto_perfil)) {
            Storage::disk('public')->delete($info->foto_perfil);
        }

        // Guardar nueva en 'public'
        $path = $request->file('foto_perfil')->store('prestadores/fotos', 'public');
        $info->foto_perfil = $path;
        $info->save();

        return redirect()->back()->with('success', 'Foto de perfil actualizada correctamente.');
    }

    /**
     * Remove the user's profile photo.
     */
    public function removeFoto()
    {
        $user = Auth::user();
        $info = $user->prestadorInfo;

        if ($info && $info->foto_perfil) {
            if (Storage::disk('public')->exists($info->foto_perfil)) {
                Storage::disk('public')->delete($info->foto_perfil);
            }
            $info->foto_perfil = null;
            $info->save();
        }

        return redirect()->back()->with('success', 'Foto de perfil eliminada correctamente.');
    }
}