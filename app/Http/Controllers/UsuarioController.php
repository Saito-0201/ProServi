<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClienteInfo;
use App\Models\PrestadorInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {           
        $usuarios = User::with('roles', 'clienteInfo', 'prestadorInfo')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view("admin.usuarios.create", compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:100',
        'email' => 'required|string|email|max:255|unique:users',
        'role' => 'required|exists:roles,name',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Asignar el rol al usuario
    $user->assignRole($request->role);

    return redirect()->route('admin.usuarios.index')
        ->with('mensaje', 'Usuario creado correctamente')
        ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $usuario = User::with('roles', 'clienteInfo', 'prestadorInfo', 'verificacion')->findOrFail($id);
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $usuario = User::with('roles', 'clienteInfo', 'prestadorInfo', 'verificacion')->findOrFail($id);
        $roles = Role::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:100',
            'email' => 'required|unique:users,email,'.$usuario->id,
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:8|confirmed',
            // Campos para cliente
            'telefono_cliente' => 'nullable|string|max:20',
            'genero_cliente' => 'nullable|in:masculino,femenino,otro',
            // Campos para prestador
            'telefono_prestador' => 'nullable|string|max:20',
            'genero_prestador' => 'nullable|in:masculino,femenino,otro',
            'descripcion' => 'nullable|string|max:500',
            'experiencia' => 'nullable|string|max:1000',
            'especialidades' => 'nullable|string|max:255',
            'disponibilidad' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
        ];

        // Solo actualizar contraseña si se proporcionó
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);
        
        // Sincronizar roles (eliminar todos y asignar el nuevo)
        $usuario->syncRoles([$request->role]);

        // Actualizar información específica según el rol
        if ($usuario->hasRole('Cliente')) {
            $this->actualizarInfoCliente($usuario, $request);
        } elseif ($usuario->hasRole('Prestador')) {
            $this->actualizarInfoPrestador($usuario, $request);
        }

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Actualizar información del cliente
     */
    private function actualizarInfoCliente(User $usuario, Request $request)
    {
        $clienteData = [
            'telefono' => $request->telefono_cliente,
            'genero' => $request->genero_cliente,
        ];

        if ($usuario->clienteInfo) {
            $usuario->clienteInfo->update($clienteData);
        } else {
            $clienteData['usuario_id'] = $usuario->id;
            ClienteInfo::create($clienteData);
        }
    }

    /**
     * Actualizar información del prestador
     */
    private function actualizarInfoPrestador(User $usuario, Request $request)
    {
        $prestadorData = [
            'telefono' => $request->telefono_prestador,
            'genero' => $request->genero_prestador,
            'descripcion' => $request->descripcion,
            'experiencia' => $request->experiencia,
            'especialidades' => $request->especialidades,
            'disponibilidad' => $request->disponibilidad,
        ];

        if ($usuario->prestadorInfo) {
            $usuario->prestadorInfo->update($prestadorData);
        } else {
            $prestadorData['usuario_id'] = $usuario->id;
            PrestadorInfo::create($prestadorData);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->delete();
        return redirect()->route('admin.usuarios.index')
               ->with('mensaje','Usuario eliminado correctamente')
               ->with('icono','success');
    }
}