<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ServicioController extends Controller
{
    /**
     * Vista Index
     */
    public function index()
    {
        $servicios = Servicio::with(['prestador', 'categoria', 'subcategoria'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $estados = ['activo' => 'Activo', 'inactivo' => 'Inactivo'];
        $categorias = Categoria::activas()->get();
        $subcategorias = Subcategoria::whereIn('categoria_id', $categorias->pluck('id'))->get();
        $prestadores = User::role('prestador')->get();
        
        $tiposPrecio = [
            'fijo' => 'Precio Fijo',
            'cotizacion' => 'Por Cotización',
            'variable' => 'Variable',
            'diario' => 'Precio Diario',
            'por_servicio' => 'Por Servicio'
        ];

        $estados = [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo'
        ];

        return view('admin.servicios.create', compact('categorias', 'subcategorias', 'prestadores', 'tiposPrecio', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'prestador_id' => 'required|exists:users,id',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'required|exists:subcategorias,id',
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'tipo_precio' => ['required', Rule::in(['fijo', 'cotizacion', 'variable' , 'diario', 'por_servicio'])],
            'precio' => 'nullable|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:12288',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'direccion' => 'required|string',
            'ciudad' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'pais' => 'required|string|max:100',
            'estado' => ['required', Rule::in(['activo', 'inactivo'])]
        ]);

        $data = $request->all();

        // Subir imagen si existe
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('servicios/fotos', 'public');
            $data['imagen'] = $imagenPath;
        }

        $servicio = Servicio::create($data);

        return redirect()->route('admin.servicios.index')
            ->with('mensaje', 'Servicio creado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $servicio = Servicio::with(['prestador', 'categoria', 'subcategoria'])->findOrFail($id);
        return view('admin.servicios.show', compact('servicio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $servicio = Servicio::with(['categoria', 'subcategoria'])->findOrFail($id);
        $categorias = Categoria::activas()->get();
        $subcategorias = Subcategoria::where('categoria_id', $servicio->categoria_id)->get();
        $prestadores = User::role('prestador')->get();
        
        $tiposPrecio = [
            'fijo' => 'Precio Fijo',
            'cotizacion' => 'Por Cotización',
            'variable' => 'Variable',
            'diario' => 'Precio Diario',
            'por_servicio' => 'Por Servicio'
        ];

        $estados = [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo'
        ];

        return view('admin.servicios.edit', compact('servicio', 'categorias', 'subcategorias', 'prestadores', 'tiposPrecio', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'prestador_id' => 'required|exists:users,id',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'required|exists:subcategorias,id',
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'tipo_precio' => ['required', Rule::in(['fijo', 'cotizacion', 'variable', 'diario', 'por_servicio'])],
            'precio' => 'nullable|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:12288',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'direccion' => 'required|string',
            'ciudad' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'pais' => 'required|string|max:100',
            'estado' => ['required', Rule::in(['activo', 'inactivo'])]
        ]);

        $data = $request->all();

        // Actualizar imagen si se proporciona
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($servicio->imagen) {
                Storage::disk('public')->delete($servicio->imagen);
            }
            
            $imagenPath = $request->file('imagen')->store('servicios/fotos', 'public');
            $data['imagen'] = $imagenPath;
        }

        $servicio->update($data);

        return redirect()->route('admin.servicios.index')
            ->with('mensaje', 'Servicio actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        // Eliminar imagen si existe
        if ($servicio->imagen) {
            Storage::disk('public')->delete($servicio->imagen);
        }

        $servicio->delete();

        return redirect()->route('admin.servicios.index')
            ->with('mensaje', 'Servicio eliminado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Obtener subcategorías por categoría (AJAX)
     */
    public function getSubcategorias($categoria_id)
    {
        try {
            $subcategorias = Subcategoria::where('categoria_id', $categoria_id)->get();
            return response()->json($subcategorias);
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }

    /**
     * Cambiar el estado de un servicio (activo/inactivo)
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => ['required', Rule::in(['activo', 'inactivo'])]
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->estado = $request->estado;
        $servicio->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'nuevo_estado' => $servicio->estado
        ]);
    }
}