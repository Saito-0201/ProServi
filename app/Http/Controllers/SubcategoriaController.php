<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubcategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategorias = Subcategoria::with('categoria')->get();
        return view('admin.subcategorias.index', compact('subcategorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::where('estado', 'activo')->get();
        return view('admin.subcategorias.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:100|unique:subcategorias,nombre',
            'descripcion' => 'nullable|string|max:255'
        ]);

        Subcategoria::create([
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('admin.subcategorias.index')
            ->with('mensaje', 'Subcategoría creada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subcategoria = Subcategoria::with('categoria')->findOrFail($id);
        return view('admin.subcategorias.show', compact('subcategoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);
        $categorias = Categoria::where('estado', 'activo')->get();
        return view('admin.subcategorias.edit', compact('subcategoria', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $subcategoria = Subcategoria::findOrFail($id);
        
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:100|unique:subcategorias,nombre,' . $subcategoria->id,
            'descripcion' => 'nullable|string|max:255'
        ]);

        $subcategoria->update([
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('admin.subcategorias.index')
            ->with('mensaje', 'Subcategoría actualizada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);
        
        // Verificar si la subcategoría tiene servicios asociados
        if ($subcategoria->servicios && $subcategoria->servicios->count() > 0) {
            return redirect()->route('admin.subcategorias.index')
                ->with('mensaje', 'No se puede eliminar la subcategoría porque tiene servicios asociados')
                ->with('icono', 'error');
        }

        $subcategoria->delete();

        return redirect()->route('admin.subcategorias.index')
            ->with('mensaje', 'Subcategoría eliminada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Obtener subcategorías por categoría (para AJAX)
     */
    public function porCategoria($categoria_id)
    {
        $subcategorias = Subcategoria::where('categoria_id', $categoria_id)->get();
        return response()->json($subcategorias);
    }
}